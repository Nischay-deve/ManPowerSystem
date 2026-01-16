<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $employees = Employee::query()
            ->with('designation')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($subQuery) use ($q) {
                    $subQuery->where('name', 'like', "%{$q}%")
                        ->orWhere('surname', 'like', "%{$q}%")
                        ->orWhere('employee_code', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.employee', compact('employees', 'q'));
    }


    public function create()
    {
        $designations = Designation::where('is_active', '1')
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('employee.create', compact('designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Manual employee code
            'employee_code' => 'required|string|max:100|unique:employees,employee_code',

            // Personal
            'name' => 'required|string|max:200',
            'surname' => 'nullable|string|max:200',
            'gender' => 'nullable|in:Male,Female,Other',
            'father_or_spouse_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'education_level' => 'nullable|string|max:100',

            // Job
            'date_of_joining' => 'nullable|date',
            'designation_id' => 'nullable|exists:designations,id',
            'category_address' => 'nullable|in:HS,S,SS,US',
            'employment_type' => 'required|in:Regular,Contract,Apprentice,Temporary',
            'salary' => 'required|numeric|min:0',

            // Contact / statutory
            'mobile' => 'nullable|string|max:30',
            'uan' => 'nullable|string|max:50',
            'pan' => 'nullable|string|max:20',
            'esic_ip' => 'nullable|string|max:100',
            'lwf' => 'nullable|string|max:100',
            'aadhaar' => 'nullable|string|max:20',

            // Bank
            'bank_account_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:150',
            'bank_ifsc' => 'nullable|string|max:30',

            // Address
            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',

            // Service / Exit
            'service_book_no' => 'nullable|string|max:100',
            'date_of_exit' => 'nullable|date',
            'reason_for_exit' => 'nullable|string|max:255',

            // Other
            'mark_of_identification' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',

            // Uploads
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'specimen_signature' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();
        try {
            // ✅ Photo upload
            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
            }

            // ✅ Signature upload
            if ($request->hasFile('specimen_signature')) {
                $validated['specimen_signature'] = $request->file('specimen_signature')->store('employees/signatures', 'public');
            }

            Employee::create($validated);

            DB::commit();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create employee: ' . $e->getMessage()]);
        }
    }

    public function edit(Employee $employee)
    {
        $designations = Designation::where('is_active', 1)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('employee.edit', compact('employee', 'designations'));
    }


    public function update(Request $request, Employee $employee)
    {

        // dd($request);
        $validated = $request->validate([
            // unique ignore current row
            'employee_code' => 'required|string|max:100|unique:employees,employee_code,' . $employee->id,

            'name' => 'required|string|max:200',
            'surname' => 'nullable|string|max:200',
            'gender' => 'nullable|in:Male,Female,Other',
            'father_or_spouse_name' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:100',
            'education_level' => 'nullable|string|max:100',

            'date_of_joining' => 'nullable|date',
            'designation_id' => 'nullable|exists:designations,id',

            'category_address' => 'nullable|in:HS,S,SS,US',
            'employment_type' => 'required|in:Regular,Contract,Apprentice,Temporary',
            'salary' => 'required|numeric|min:0',

            'mobile' => 'nullable|string|max:30',
            'uan' => 'nullable|string|max:50',
            'pan' => 'nullable|string|max:20',
            'esic_ip' => 'nullable|string|max:100',
            'lwf' => 'nullable|string|max:100',
            'aadhaar' => 'nullable|string|max:20',

            'bank_account_no' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:150',
            'bank_ifsc' => 'nullable|string|max:30',

            'present_address' => 'nullable|string',
            'permanent_address' => 'nullable|string',

            'service_book_no' => 'nullable|string|max:100',
            'date_of_exit' => 'nullable|date',
            'reason_for_exit' => 'nullable|string|max:255',

            'mark_of_identification' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',

            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'specimen_signature' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // PHOTO upload
            if ($request->hasFile('photo')) {
                if (!empty($employee->photo) && Storage::disk('public')->exists($employee->photo)) {
                    Storage::disk('public')->delete($employee->photo);
                }
                $validated['photo'] = $request->file('photo')->store('employees/photos', 'public');
            }

            // SIGNATURE upload
            if ($request->hasFile('specimen_signature')) {
                if (!empty($employee->specimen_signature) && Storage::disk('public')->exists($employee->specimen_signature)) {
                    Storage::disk('public')->delete($employee->specimen_signature);
                }
                $validated['specimen_signature'] = $request->file('specimen_signature')->store('employees/signatures', 'public');
            }

            $employee->update($validated);

            DB::commit();

            return redirect()
                ->route('employees.index')
                ->with('success', 'Employee updated successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update employee: ' . $e->getMessage()]);
        }
    }

    public function destroy(Employee $employee)
    {
        $employee->delete(); // soft delete
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
