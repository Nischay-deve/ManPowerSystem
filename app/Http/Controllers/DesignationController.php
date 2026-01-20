<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::query()
            ->with(['department'])
            ->withCount('employees')
            ->latest()
            ->get();

        $departments = Department::where('is_active', 1)->orderBy('name')->get();

        $total    = Designation::count();
        $active   = Designation::where('is_active', 1)->count();
        $inactive = Designation::where('is_active', 0)->count();

        return view('designations.index', compact('designations', 'departments', 'total', 'active', 'inactive'));
    }

    public function store(Request $request)
    {
        // ✅ Validate according to BLADE inputs (title, notes, code, status)
        $validated = $request->validate([
            'title'   => 'required|string|max:200',
            'notes'   => 'nullable|string|max:500',
            'code'    => 'nullable|string|max:50',
            'status'  => 'required|in:Active,Inactive',

            // optional: if you add later (controller will only save if DB has column)
            'department_id' => 'nullable|integer|exists:departments,id',
            'grade'         => 'nullable|string|max:50',
        ]);

        $isActive = ($validated['status'] === 'Active') ? 1 : 0;

        // ✅ Build payload dynamically (only columns that exist in DB)
        $payload = [];

        if (Schema::hasColumn('designations', 'title')) {
            $payload['title'] = $validated['title'];
        }

        // Blade uses "notes". Some DBs have "notes", some have "description".
        if (Schema::hasColumn('designations', 'notes')) {
            $payload['notes'] = $validated['notes'] ?? null;
        } elseif (Schema::hasColumn('designations', 'description')) {
            $payload['description'] = $validated['notes'] ?? null;
        }

        if (Schema::hasColumn('designations', 'code')) {
            $payload['code'] = $validated['code'] ?? null;
        }

        if (Schema::hasColumn('designations', 'grade')) {
            $payload['grade'] = $validated['grade'] ?? null;
        }

        if (Schema::hasColumn('designations', 'department_id')) {
            $payload['department_id'] = $validated['department_id'] ?? null;
        }

        if (Schema::hasColumn('designations', 'is_active')) {
            $payload['is_active'] = $isActive;
        }

        if (Schema::hasColumn('designations', 'created_by')) {
            $payload['created_by'] = auth()->id();
        }

        // ✅ Unique check aligned with DB + Blade
        $query = Designation::query()->where('title', $validated['title']);

        // if DB has department_id, do uniqueness per department (otherwise global)
        if (Schema::hasColumn('designations', 'department_id')) {
            $query->where('department_id', $validated['department_id'] ?? null);
        }

        if ($query->exists()) {
            return back()
                ->withErrors(['title' => 'This designation already exists.'])
                ->withInput();
        }

        Designation::create($payload);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, Designation $designation)
    {
        // ✅ Validate according to BLADE inputs
        $validated = $request->validate([
            'title'   => 'required|string|max:200',
            'notes'   => 'nullable|string|max:500',
            'code'    => 'nullable|string|max:50',
            'status'  => 'required|in:Active,Inactive',

            // optional
            'department_id' => 'nullable|integer|exists:departments,id',
            'grade'         => 'nullable|string|max:50',
        ]);

        $isActive = ($validated['status'] === 'Active') ? 1 : 0;

        $payload = [];

        if (Schema::hasColumn('designations', 'title')) {
            $payload['title'] = $validated['title'];
        }

        if (Schema::hasColumn('designations', 'notes')) {
            $payload['notes'] = $validated['notes'] ?? null;
        } elseif (Schema::hasColumn('designations', 'description')) {
            $payload['description'] = $validated['notes'] ?? null;
        }

        if (Schema::hasColumn('designations', 'code')) {
            $payload['code'] = $validated['code'] ?? null;
        }

        if (Schema::hasColumn('designations', 'grade')) {
            $payload['grade'] = $validated['grade'] ?? null;
        }

        if (Schema::hasColumn('designations', 'department_id')) {
            $payload['department_id'] = $validated['department_id'] ?? null;
        }

        if (Schema::hasColumn('designations', 'is_active')) {
            $payload['is_active'] = $isActive;
        }

        if (Schema::hasColumn('designations', 'updated_by')) {
            $payload['updated_by'] = auth()->id();
        }

        // ✅ Unique check
        $query = Designation::query()
            ->where('title', $validated['title'])
            ->where('id', '!=', $designation->id);

        if (Schema::hasColumn('designations', 'department_id')) {
            $query->where('department_id', $validated['department_id'] ?? null);
        }

        if ($query->exists()) {
            return back()
                ->withErrors(['title' => 'This designation already exists.'])
                ->withInput();
        }

        $designation->update($payload);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $payload = [];

        if (Schema::hasColumn('designations', 'is_active')) {
            $payload['is_active'] = 0;
        }

        if (Schema::hasColumn('designations', 'updated_by')) {
            $payload['updated_by'] = auth()->id();
        }

        $designation->update($payload);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deactivated successfully.');
    }
}
