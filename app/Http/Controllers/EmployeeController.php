<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeBankAccount;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $employees = Employee::with(['department', 'designation', 'primaryBankAccount', 'documents'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('first_name', 'like', "%{$q}%")
                        ->orWhere('surname', 'like', "%{$q}%")
                        ->orWhere('employee_code', 'like', "%{$q}%")
                        ->orWhere('mobile', 'like', "%{$q}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('employees.index', compact('employees', 'q'));
    }


    public function create()
    {
        $departments = Department::where('is_active', 1)->orderBy('name')->get();
        $designations = Designation::where('is_active', 1)->orderBy('title')->get();

        return view('employees.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $data = $this->validateEmployee($request, false);

        return DB::transaction(function () use ($request, $data) {

            // ✅ work_status (UI) -> is_active (DB)
            $isActive = $this->workStatusToIsActive($data['employee']['work_status'] ?? null);

            // work_status is not DB column
            unset($data['employee']['work_status']);

            $employee = Employee::create([
                ...$data['employee'],
                'is_active'   => $isActive,
                'created_by'  => auth()->id(),
                'row_version' => 1,
            ]);

            // ✅ Bank (Primary)
            if (!empty($data['bank']) && !empty($data['bank']['account_number'])) {
                EmployeeBankAccount::where('employee_id', $employee->id)->update(['is_primary' => 0]);

                EmployeeBankAccount::create([
                    'employee_id' => $employee->id,
                    'account_number' => $data['bank']['account_number'],
                    'account_holder_name' => $data['bank']['account_holder_name'] ?? null,
                    'bank_name' => $data['bank']['bank_name'] ?? null,
                    'branch' => $data['bank']['branch'] ?? null,
                    'ifsc' => $data['bank']['ifsc'] ?? null,
                    'is_primary' => 1,
                    'verification_status' => 'unverified',
                    'created_by' => auth()->id(),
                ]);
            }

            // ✅ Documents
            $this->saveDocuments($request, $employee->id);

            return redirect()
                ->route('employees.index', $employee->id)
                ->with('success', 'Employee created successfully');
        });
    }

    public function edit($id)
    {
        $employee = Employee::with(['primaryBankAccount', 'documents'])->findOrFail($id);

        $departments = Department::where('is_active', 1)->orderBy('name')->get();
        $designations = Designation::where('is_active', 1)->orderBy('title')->get();

        return view('employees.edit', compact('employee', 'departments', 'designations'));
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        // ✅ Block employee_code updates
        if ($request->filled('employee_code') && trim($request->employee_code) !== $employee->employee_code) {
            return back()
                ->withErrors(['employee_code' => 'Employee Code cannot be updated.'])
                ->withInput();
        }

        $data = $this->validateEmployee($request, true, $employee->id);

        return DB::transaction(function () use ($request, $employee, $data) {

            // Safety: never update employee_code
            unset($data['employee']['employee_code']);

            // ✅ If work_status present -> update is_active
            if (array_key_exists('work_status', $data['employee'])) {
                $employee->is_active = $this->workStatusToIsActive($data['employee']['work_status']);
                unset($data['employee']['work_status']);
            }

            $employee->update([
                ...$data['employee'],
                'is_active'   => (int) $employee->is_active,
                'updated_by'  => auth()->id(),
                'row_version' => (int)($employee->row_version ?? 1) + 1,
            ]);

            // ✅ Bank update (Primary)
            if (!empty($data['bank']) && !empty($data['bank']['account_number'])) {
                EmployeeBankAccount::where('employee_id', $employee->id)->update(['is_primary' => 0]);

                EmployeeBankAccount::updateOrCreate(
                    ['employee_id' => $employee->id, 'is_primary' => 1],
                    [
                        'account_number' => $data['bank']['account_number'],
                        'account_holder_name' => $data['bank']['account_holder_name'] ?? null,
                        'bank_name' => $data['bank']['bank_name'] ?? null,
                        'branch' => $data['bank']['branch'] ?? null,
                        'ifsc' => $data['bank']['ifsc'] ?? null,
                        'verification_status' => 'unverified',
                        'updated_by' => auth()->id(),
                    ]
                );
            }

            $this->saveDocuments($request, $employee->id);

            return redirect()
                ->route('employees.edit', $employee->id)
                ->with('success', 'Employee updated successfully');
        });
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete(); // soft delete
        return redirect()->route('employees.index')->with('success', 'Employee deleted');
    }

    public function show(Employee $employee)
    {
        $employee->load([
            'designation',
            'department',
            'primaryBankAccount',
            'documents' => fn($q) => $q->orderByDesc('id'),
        ]);

        // Latest active profile photo: accept both doc_type and remarks (for old data)
        $photoDoc = $employee->documents
            ->where('is_active', 1)
            ->first(function ($d) {
                return $d->doc_type === 'photo' || $d->remarks === 'Profile photo';
            });

        $photoUrl = $photoDoc
            ? asset('public/storage/' . $photoDoc->file_path)
            : asset('assets/images/users/default-avatar.png');

        $status = ((int)$employee->is_active === 1) ? 'Active' : 'Inactive';
        $fullName = trim(($employee->first_name ?? '') . ' ' . ($employee->surname ?? ''));

        return view('employees.show', compact('employee', 'photoUrl', 'status', 'fullName'));
    }


    private function validateEmployee(Request $request, bool $isUpdate = false, ?int $employeeId = null): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:200'],
            'surname' => ['nullable', 'string', 'max:200'],
            'gender' => ['nullable', Rule::in(['Male', 'Female', 'Other'])],
            'father_or_spouse_name' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],

            'nationality' => ['nullable', Rule::in(['Indian', 'Non Indian'])],

            'date_of_joining' => ['nullable', 'date'],
            'designation_id' => ['nullable', 'integer'],
            'department_id' => ['nullable', 'integer'],

            'category' => ['nullable', 'string', 'max:100'],

            'mobile' => ['nullable', 'string', 'max:30'],
            'uan' => ['nullable', 'string', 'max:50'],
            'pan' => ['nullable', 'string', 'max:20'],
            'esic_ip' => ['nullable', 'string', 'max:100'],
            'aadhaar' => ['nullable', 'string', 'max:20'],

            'present_address' => ['nullable', 'string'],
            'permanent_address' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],

            'salary' => ['required', 'numeric', 'min:0'],

            // ✅ Accept BOTH: strings OR 1/0
            'work_status' => $isUpdate
                ? ['nullable', Rule::in(['Active', 'On Leave', 'Exited', '0', '1', 0, 1])]
                : ['required', Rule::in(['Active', 'On Leave', 'Exited', '0', '1', 0, 1])],

            'employment_type' => ['required', Rule::in(['Regular', 'Contract', 'Apprentice', 'Temporary'])],

            // Bank (nested)
            'bank.account_number' => ['nullable', 'string', 'min:6', 'max:30'],
            'bank.account_holder_name' => ['nullable', 'string', 'max:255'],
            'bank.bank_name' => ['nullable', 'string', 'max:255'],
            'bank.branch' => ['nullable', 'string', 'max:255'],
            'bank.ifsc' => ['nullable', 'string', 'max:20'],

            // Documents (optional)
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'aadhaar_front' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'aadhaar_back' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'bank_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
            'signature' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:4096'],
        ];

        if (!$isUpdate) {
            $rules['employee_code'] = [
                'required',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_code'),
            ];
        } else {
            $rules['employee_code'] = ['nullable', 'string', 'max:50'];
        }

        $validated = $request->validate($rules);

        return [
            'employee' => collect($validated)
                ->except(['bank', 'photo', 'aadhaar_front', 'aadhaar_back', 'bank_proof', 'signature'])
                ->toArray(),
            'bank' => $validated['bank'] ?? null,
        ];
    }

    /**
     * ✅ Converts work_status (Active/On Leave/Exited OR 1/0) to is_active (1/0)
     */
    private function workStatusToIsActive($workStatus): int
    {
        // numeric
        if ($workStatus === 1 || $workStatus === '1') return 1;
        if ($workStatus === 0 || $workStatus === '0') return 0;

        // string
        $workStatus = (string) $workStatus;
        return in_array($workStatus, ['Active', 'On Leave']) ? 1 : 0;
    }

    private function saveDocuments(Request $request, int $employeeId): void
    {
        $map = [
            'photo' => ['doc_type' => 'photo', 'remarks' => 'Profile photo'],
            'aadhaar_front' => ['doc_type' => 'aadhaar', 'remarks' => 'Aadhaar front side'],
            'aadhaar_back' => ['doc_type' => 'aadhaar', 'remarks' => 'Aadhaar back side'],
            'bank_proof' => ['doc_type' => 'bank_proof', 'remarks' => 'Bank proof'],
            'signature' => ['doc_type' => 'signature', 'remarks' => 'Specimen signature / Thumb impression'],
        ];

        foreach ($map as $input => $meta) {
            if (!$request->hasFile($input)) continue;

            $file = $request->file($input);

            $dir = "uploads/employees/{$employeeId}";
            $fileName = $meta['doc_type'] . "_" . time() . "." . $file->getClientOriginalExtension();
            $storedPath = $file->storeAs($dir, $fileName, 'public');

            EmployeeDocument::where('employee_id', $employeeId)
                ->where('doc_type', $meta['doc_type'])
                ->where('remarks', $meta['remarks'])
                ->update(['is_active' => 0]);

            EmployeeDocument::create([
                'employee_id' => $employeeId,
                'doc_type' => $meta['doc_type'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $storedPath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'remarks' => $meta['remarks'],
                'is_active' => 1,
            ]);
        }
    }
}
