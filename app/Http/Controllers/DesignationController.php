<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $designations = Designation::query()
            ->withCount('employees') // employees_count
            ->latest()
            ->get();

        $total    = Designation::count();
        $active   = Designation::where('is_active', 1)->count();
        $inactive = Designation::where('is_active', 0)->count();

        return view('designations.index', compact('designations', 'total', 'active', 'inactive'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:150|unique:designations,title',
            'is_active' => 'required|in:0,1',
            'notes'     => 'nullable|string',
        ]);

        Designation::create($validated);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation created successfully.');
    }

    public function update(Request $request, Designation $designation)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:150|unique:designations,title,' . $designation->id,
            'is_active' => 'required|in:0,1',
            'notes'     => 'nullable|string',
        ]);

        $designation->update($validated);

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation updated successfully.');
    }

    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()
            ->route('designations.index')
            ->with('success', 'Designation deleted successfully.');
    }
}
