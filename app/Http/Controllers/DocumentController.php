<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $type = $request->get('type'); // photo/aadhaar/bank_proof/signature etc.
        $active = $request->get('active'); // 1/0

        $documents = EmployeeDocument::query()
            ->with(['employee.designation', 'employee.department'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('file_name', 'like', "%{$q}%")
                        ->orWhere('remarks', 'like', "%{$q}%")
                        ->orWhere('doc_type', 'like', "%{$q}%")
                        ->orWhereHas('employee', function ($e) use ($q) {
                            $e->where('employee_code', 'like', "%{$q}%")
                                ->orWhere('first_name', 'like', "%{$q}%")
                                ->orWhere('surname', 'like', "%{$q}%")
                                ->orWhere('mobile', 'like', "%{$q}%");
                        });
                });
            })
            ->when($type, fn($query) => $query->where('doc_type', $type))
            ->when($active !== null && $active !== '', fn($query) => $query->where('is_active', (int)$active))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $types = EmployeeDocument::query()
            ->select('doc_type')
            ->distinct()
            ->orderBy('doc_type')
            ->pluck('doc_type');

        return view('documents.index', compact('documents', 'q', 'type', 'active', 'types'));
    }

    public function create(Request $request)
    {
        $employeeId = $request->get('employee_id');

        $employees = Employee::query()
            ->orderByDesc('id')
            ->limit(200)
            ->get(['id', 'employee_code', 'first_name', 'surname']);

        return view('documents.create', compact('employees', 'employeeId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'doc_type' => 'required|string|max:50',
            'remarks' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,webp,pdf', // 5MB
        ]);

        return DB::transaction(function () use ($request, $validated) {

            $employeeId = (int)$validated['employee_id'];

            $file = $request->file('file');
            $dir = "uploads/employees/{$employeeId}/docs";
            $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $fileName = ($validated['doc_type'] ?: 'doc') . "_" . time() . "_" . $safeName . "." . $file->getClientOriginalExtension();

            $storedPath = $file->storeAs($dir, $fileName, 'public');

            EmployeeDocument::create([
                'employee_id' => $employeeId,
                'doc_type' => $validated['doc_type'],
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $storedPath, // store path only, display with asset('storage/'.$file_path)
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'remarks' => $validated['remarks'] ?? null,
                'is_active' => (int)($validated['is_active'] ?? 1),
            ]);

            return redirect()->route('documents.index')->with('success', 'Document uploaded successfully.');
        });
    }

    public function edit(EmployeeDocument $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, EmployeeDocument $document)
    {
        $validated = $request->validate([
            'doc_type' => 'required|string|max:50',
            'remarks' => 'nullable|string|max:255',
            'is_active' => 'required|in:0,1',
        ]);

        $document->update([
            'doc_type' => $validated['doc_type'],
            'remarks' => $validated['remarks'] ?? null,
            'is_active' => (int)$validated['is_active'],
        ]);

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(EmployeeDocument $document)
    {
        $document->delete(); // hard delete (table has no deleted_at)
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
