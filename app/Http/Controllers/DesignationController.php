<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'department_id' => 'nullable|integer|exists:departments,id',
            'title'         => 'required|string|max:200',
            'code'          => 'nullable|string|max:50',
            'grade'         => 'nullable|string|max:50',
            'description'   => 'nullable|string|max:500',
            'is_active'     => 'required|in:0,1',
        ]);

        $validated['created_by'] = auth()->id();

        // Unique is (department_id, title)
        $exists = Designation::where('department_id', $validated['department_id'])
            ->where('title', $validated['title'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['title' => 'This designation already exists in selected department.'])->withInput();
        }

        Designation::create($validated);

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'department_id' => 'nullable|integer|exists:departments,id',
            'title'         => 'required|string|max:200',
            'code'          => 'nullable|string|max:50',
            'grade'         => 'nullable|string|max:50',
            'description'   => 'nullable|string|max:500',
            'is_active'     => 'required|in:0,1',
        ]);

        $exists = Designation::where('department_id', $validated['department_id'])
            ->where('title', $validated['title'])
            ->where('id', '!=', $designation->id)
            ->exists();

        if ($exists) {
            return back()->withErrors(['title' => 'This designation already exists in selected department.'])->withInput();
        }

        $designation->update($validated);

        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    // Deactivate instead of hard delete (FK safe)
    public function destroy(Designation $designation)
    {
        $designation->update(['is_active' => 0]);

        return redirect()->route('designations.index')->with('success', 'Designation deactivated successfully.');
    }
}
