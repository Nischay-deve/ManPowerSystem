<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::query()  
            ->latest()
            ->get();

        $total    = Department::count();
        $active   = Department::where('is_active', 1)->count();
        $inactive = Department::where('is_active', 0)->count();

        return view('departments.index', compact('departments', 'total', 'active', 'inactive'));
    }

    public function store(Request $request)
    {
        // ✅ Validate according to Blade inputs (name, notes, code, status)
        $validated = $request->validate([
            'name'   => 'required|string|max:200',
            'notes'  => 'nullable|string|max:500',
            'code'   => 'nullable|string|max:50',
            'status' => 'required|in:Active,Inactive',
        ]);

        $isActive = ($validated['status'] === 'Active') ? 1 : 0;

        // ✅ Build payload dynamically (save only if DB column exists)
        $payload = [];

        if (Schema::hasColumn('departments', 'name')) {
            $payload['name'] = $validated['name'];
        }

        // Blade uses notes; DB can be notes or description
        if (Schema::hasColumn('departments', 'notes')) {
            $payload['notes'] = $validated['notes'] ?? null;
        } elseif (Schema::hasColumn('departments', 'description')) {
            $payload['description'] = $validated['notes'] ?? null;
        }

        if (Schema::hasColumn('departments', 'code')) {
            $payload['code'] = $validated['code'] ?? null;
        }

        if (Schema::hasColumn('departments', 'is_active')) {
            $payload['is_active'] = $isActive;
        }

        if (Schema::hasColumn('departments', 'created_by')) {
            $payload['created_by'] = auth()->id();
        }

        // ✅ Unique check (name)
        $existsQuery = Department::query()->where('name', $validated['name']);

        if ($existsQuery->exists()) {
            return back()
                ->withErrors(['name' => 'This department already exists.'])
                ->withInput();
        }

        Department::create($payload);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:200',
            'notes'  => 'nullable|string|max:500',
            'code'   => 'nullable|string|max:50',
            'status' => 'required|in:Active,Inactive',
        ]);

        $isActive = ($validated['status'] === 'Active') ? 1 : 0;

        $payload = [];

        if (Schema::hasColumn('departments', 'name')) {
            $payload['name'] = $validated['name'];
        }

        if (Schema::hasColumn('departments', 'notes')) {
            $payload['notes'] = $validated['notes'] ?? null;
        } elseif (Schema::hasColumn('departments', 'description')) {
            $payload['description'] = $validated['notes'] ?? null;
        }

        if (Schema::hasColumn('departments', 'code')) {
            $payload['code'] = $validated['code'] ?? null;
        }

        if (Schema::hasColumn('departments', 'is_active')) {
            $payload['is_active'] = $isActive;
        }

        if (Schema::hasColumn('departments', 'updated_by')) {
            $payload['updated_by'] = auth()->id();
        }

        // ✅ Unique check (name) excluding current
        $existsQuery = Department::query()
            ->where('name', $validated['name'])
            ->where('id', '!=', $department->id);

        if ($existsQuery->exists()) {
            return back()
                ->withErrors(['name' => 'This department already exists.'])
                ->withInput();
        }

        $department->update($payload);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    // ✅ Deactivate instead of hard delete
    public function destroy(Department $department)
    {
        $payload = [];

        if (Schema::hasColumn('departments', 'is_active')) {
            $payload['is_active'] = 0;
        }

        if (Schema::hasColumn('departments', 'updated_by')) {
            $payload['updated_by'] = auth()->id();
        }

        $department->update($payload);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deactivated successfully.');
    }
}
