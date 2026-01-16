<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\EmployeeBankAccount;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = $this->validateEmployee($request);

        return DB::transaction(function () use ($request, $data) {

            // Auto generate employee_code like EMP-0007
            $nextId = (Employee::max('id') ?? 0) + 1;
            $employeeCode = 'EMP-' . str_pad((string)$nextId, 4, '0', STR_PAD_LEFT);

            $employee = Employee::create([
                ...$data['employee'],
                'employee_code' => $employeeCode,
                'sl_no' => $data['employee']['sl_no'] ?? $nextId,
                'created_by' => auth()->id(),
                'row_version' => 1,
            ]);

            // Bank (primary)
            if (!empty($data['bank']) && !empty($data['bank']['account_number'])) {
                EmployeeBankAccount::where('employee_id', $employee->id)->update(['is_primary' => 0]);

                EmployeeBankAccount::create([
                    'employee_id' => $employee->id,
                    'account_number' => $data['bank']['account_number'], // model mutator will encrypt + last4
                    'account_holder_name' => $data['bank']['account_holder_name'] ?? null,
                    'bank_name' => $data['bank']['bank_name'] ?? null,
                    'branch' => $data['bank']['branch'] ?? null,
                    'ifsc' => $data['bank']['ifsc'] ?? null,
                    'is_primary' => 1,
                    'verification_status' => 'unverified',
                    'created_by' => auth()->id(),
                ]);
            }

            // Documents
            $this->saveDocuments($request, $employee->id);

            return redirect()->route('employees.edit', $employee->id)
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
        $data = $this->validateEmployee($request, true);

        return DB::transaction(function () use ($request, $employee, $data) {

            $employee->update([
                ...$data['employee'],
                'updated_by' => auth()->id(),
                'row_version' => (int)($employee->row_version ?? 1) + 1,
            ]);

            // Bank update (primary)
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

            return redirect()->route('employees.edit', $employee->id)
                ->with('success', 'Employee updated successfully');
        });
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete(); // soft delete (deleted_at)
        return redirect()->route('employees.index')->with('success', 'Employee deleted');
    }

    private function validateEmployee(Request $request, bool $isUpdate = false): array
    {
        $validated = $request->validate([
            // employees
            'first_name' => 'required|string|max:200',
            'surname' => 'nullable|string|max:200',
            'gender' => 'nullable|in:Male,Female,Other',
            'father_or_spouse_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'education_level' => 'nullable|string|max:100',
            'date_of_joining' => 'nullable|date',
            'designation_id' => 'nullable|integer',
            'department_id' => 'nullable|integer',

            'category' => 'nullable|string|max:100',
            'address_type' => 'nullable|string|max:20',
            'employment_type' => 'required|in:Regular,Contract,Apprentice,Temporary',

            'mobile' => 'nullable|string|max:30',
            'uan' => 'nullable|string|max:50',
            'pan' => 'nullable|string|max:20',
            'esic_ip' => 'nullable|string|max:100',
            'lwf' => 'nullable|string|max:100',
            'aadhaar' => 'nullable|string|max:20',

            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',

            'service_book_no' => 'nullable|string|max:100',
            'date_of_exit' => 'nullable|date',
            'reason_for_exit' => 'nullable|string|max:255',

            'mark_of_identification' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'salary' => 'required|numeric|min:0',

            // bank (NESTED)
            'bank.account_number' => 'nullable|string|min:6|max:30',
            'bank.account_holder_name' => 'nullable|string|max:255',
            'bank.bank_name' => 'nullable|string|max:255',
            'bank.branch' => 'nullable|string|max:255',
            'bank.ifsc' => 'nullable|string|max:20',

            // documents
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'aadhaar_front' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'aadhaar_back' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'bank_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:4096',
        ]);

        return [
            'employee' => collect($validated)->except(['bank', 'photo', 'aadhaar_front', 'aadhaar_back', 'bank_proof', 'signature'])->toArray(),
            'bank' => $validated['bank'] ?? null,
        ];
    }

    private function saveDocuments(Request $request, int $employeeId): void
    {
        // DB sample uses doc_type: photo, aadhaar, bank_proof (doc_type is varchar so signature is also OK)
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

            // store in public disk -> storage/app/public/uploads/...
            $dir = "uploads/employees/{$employeeId}";
            $fileName = $meta['doc_type'] . "_" . time() . "." . $file->getClientOriginalExtension();
            $storedPath = $file->storeAs($dir, $fileName, 'public'); // returns uploads/employees/{id}/x.ext

            // Deactivate old same doc slot (doc_type + remarks)
            EmployeeDocument::where('employee_id', $employeeId)
                ->where('doc_type', $meta['doc_type'])
                ->where('remarks', $meta['remarks'])
                ->update(['is_active' => 0]);

            EmployeeDocument::create([
                'employee_id' => $employeeId,
                'doc_type' => $meta['doc_type'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $storedPath, // IMPORTANT: store WITHOUT 'storage/' prefix
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'remarks' => $meta['remarks'],
                'is_active' => 1,
            ]);
        }
    }
}
