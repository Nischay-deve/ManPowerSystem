<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $employee = null;

        // ✅ Attach employee if employees table has user_id
        if (Schema::hasTable('employees') && Schema::hasColumn('employees', 'user_id')) {
            $employee = Employee::with(['designation', 'department', 'documents', 'primaryBankAccount'])
                ->where('user_id', $user->id)
                ->first();
        }

        // ✅ Dynamic Name
        $fullName =
            trim(($employee->first_name ?? '') . ' ' . ($employee->surname ?? ''))
            ?: ($user->name ?? 'Profile');

        // ✅ Dynamic Role (from users table if exists)
        $role = $user->role ?? null; // if you don’t have role column, it will be null and Blade won’t show it

        // ✅ Dynamic Status
        $status = 'Active';
        if ($employee && !empty($employee->date_of_exit)) {
            $status = 'Exited';
        }

        // ✅ Designation
        $designation = $employee?->designation?->title ?? null;

        // ✅ Email / Phone
        $email = $user->email ?? null;
        $phone = $employee->mobile ?? ($user->phone ?? null);

        // ✅ DOB / Join Date (only if exists)
        $dob = $employee->date_of_birth ?? null;
        $joinedDate = $employee->date_of_joining ?? null;

        // ✅ Profile photo from employee_documents (optional)
        $photoUrl = asset('assets/images/avatar/avatar-large3.jpg');
        if ($employee && $employee->documents) {
            $photoDoc = $employee->documents
                ->where('is_active', 1)
                ->firstWhere('remarks', 'Profile photo');

            if ($photoDoc && !empty($photoDoc->file_path)) {
                $photoUrl = asset('storage/' . $photoDoc->file_path);
            }
        }

        return view('profile.index', compact(
            'user',
            'employee',
            'fullName',
            'role',
            'status',
            'designation',
            'email',
            'phone',
            'dob',
            'joinedDate',
            'photoUrl'
        ));
    }
}
