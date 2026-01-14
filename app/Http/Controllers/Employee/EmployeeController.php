<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{

    public function index(Request $request)
    {
        $q = $request->get('q');

        $employees = Employee::query()
            ->when($q, function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                    ->orWhere('surname', 'like', "%{$q}%")
                    ->orWhere('employee_code', 'like', "%{$q}%")
                    ->orWhere('mobile', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.employee', compact('employees', 'q'));
    }
    public function create()
    {
        return view('employee.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => 'nullable|unique:employees,employee_code',
            'first_name'    => 'required|string|max:200',

            'surname'               => 'nullable|string|max:200',
            'gender'                => 'nullable|in:Male,Female,Other',
            'father_or_spouse_name' => 'nullable|string|max:255',
            'date_of_birth'         => 'nullable|date',
            'nationality'           => 'nullable|string|max:100',
            'education_level'       => 'nullable|string|max:100',

            // ✅ PHOTO VALIDATION
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'date_of_joining'  => 'nullable|date',
            'designation_id'   => 'nullable|exists:designations,id',
            'department_id'    => 'nullable|exists:departments,id',
            'category'         => 'nullable|string|max:100',
            'employment_type'  => 'required|in:Regular,Contract,Apprentice,Temporary',
            'salary'           => 'required|numeric|min:0',

            'mobile'  => 'nullable|string|max:30',
            'uan'     => 'nullable|string|max:50',
            'pan'     => 'nullable|string|max:20',
            'esic_ip' => 'nullable|string|max:100',
            'lwf'     => 'nullable|string|max:100',
            'aadhaar' => 'nullable|string|max:20',

            'present_address'   => 'nullable|string',
            'permanent_address' => 'nullable|string',
            'address_type'      => 'nullable|string|max:20',

            'date_of_exit'    => 'nullable|date',
            'reason_for_exit' => 'nullable|string|max:255',

            'mark_of_identification' => 'nullable|string|max:255',
            'remarks'                => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $validated['employee_code'] = $validated['employee_code'] ?? $this->generateEmployeeCode();
            $validated['created_by'] = Auth::id();
            $validated['row_version'] = 1;

            // ✅ STORE PHOTO (if uploaded)
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('employees', 'public');
            }

            Employee::create($validated);

            DB::commit();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create employee: ' . $e->getMessage()]);
        }
    }

    private function generateEmployeeCode(): string
    {
        $latestId = Employee::withTrashed()->max('id') + 1;

        return 'EMP-' . str_pad($latestId, 5, '0', STR_PAD_LEFT);
    }

    public function edit(Employee $employee)
    {
        return view('employee.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            // ✅ DO NOT allow changing employee_code from UI
            // (we will not validate/accept it from request)
            // 'employee_code' => 'sometimes'  ❌

            // PERSONAL
            'first_name'             => 'required|string|max:200',
            'surname'                => 'nullable|string|max:200',
            'gender'                 => 'nullable|in:Male,Female,Other',
            'father_or_spouse_name'  => 'nullable|string|max:255',
            'date_of_birth'          => 'nullable|date',
            'nationality'            => 'nullable|string|max:100',
            'education_level'        => 'nullable|string|max:100',

            // PHOTO
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            // JOB
            'date_of_joining'   => 'nullable|date',
            'designation_id'    => 'nullable|integer', // if you have table, use exists:designations,id
            'department_id'     => 'nullable|integer', // if you have table, use exists:departments,id
            'category'          => 'nullable|string|max:100',
            'address_type'      => 'nullable|string|max:20',
            'employment_type'   => 'required|in:Regular,Contract,Apprentice,Temporary',
            'salary'            => 'required|numeric|min:0',

            // CONTACT & STATUTORY
            'mobile'   => 'nullable|string|max:30',
            'uan'      => 'nullable|string|max:50',
            'pan'      => 'nullable|string|max:20',
            'esic_ip'  => 'nullable|string|max:100',
            'lwf'      => 'nullable|string|max:100',
            'aadhaar'  => 'nullable|string|max:20',

            // ADDRESS
            'present_address'   => 'nullable|string',
            'permanent_address' => 'nullable|string',

            // SERVICE & EXIT
            'service_book_no'   => 'nullable|string|max:100',
            'date_of_exit'      => 'nullable|date',
            'reason_for_exit'   => 'nullable|string|max:255',

            // OTHER
            'mark_of_identification' => 'nullable|string|max:255',
            'remarks'               => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // ✅ PHOTO UPLOAD (store & update DB)
            if ($request->hasFile('photo')) {

                // delete old file if exists
                if ($employee->photo && Storage::disk('public')->exists($employee->photo)) {
                    Storage::disk('public')->delete($employee->photo);
                }

                $validated['photo'] = $request->file('photo')->store('employees', 'public');
            }

            // ✅ Audit fields
            $validated['updated_by']  = Auth::id();
            $validated['row_version'] = ($employee->row_version ?? 0) + 1;

            // ✅ IMPORTANT: Don't allow employee_code overwrite even if someone sends it
            unset($validated['employee_code']);

            $employee->update($validated);

            DB::commit();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update employee.']);
        }
    }

    public function exitEmployee(Request $request, Employee $employee)
    {
        $request->validate([
            'date_of_exit'    => 'required|date',
            'reason_for_exit' => 'required|string|max:255',
        ]);

        $employee->update([
            'date_of_exit'    => $request->date_of_exit,
            'reason_for_exit' => $request->reason_for_exit,
            'updated_by'      => Auth::id(),
            'row_version'     => $employee->row_version + 1,
        ]);

        $employee->delete(); // soft delete

        return redirect()
            ->route('employees.index')
            ->with('success', 'Employee exited successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete(); // ✅ SOFT DELETE
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
