<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $docType = $request->get('doc_type');
        $active = $request->get('active');

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
            ->when($docType, fn($query) => $query->where('doc_type', $docType))
            ->when($active !== null && $active !== '', fn($query) => $query->where('is_active', (int) $active))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $types = EmployeeDocument::query()
            ->select('doc_type')
            ->distinct()
            ->orderBy('doc_type')
            ->pluck('doc_type');

        return view('documents.index', compact('documents', 'q', 'docType', 'active', 'types'));
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
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'doc_type'    => ['nullable', 'string', 'max:50'],
            'remarks'     => ['nullable', 'string', 'max:255'],
            'is_active'   => ['nullable'],
            'file'        => ['required', 'file', 'max:5120', 'mimes:jpg,jpeg,png,webp,pdf'],
        ]);

        // ✅ Fix: enforce doc_type + remarks based on "type" query param
        $type = $request->get('type');

        $typeMap = [
            'profile_photo' => ['doc_type' => 'photo',         'remarks' => 'Profile photo'],
            'aadhaar_front' => ['doc_type' => 'aadhaar_front', 'remarks' => 'Aadhaar front side'],
            'aadhaar_back'  => ['doc_type' => 'aadhaar_back',  'remarks' => 'Aadhaar back side'],
            'bank_proof'    => ['doc_type' => 'bank_proof',    'remarks' => 'Bank proof'],
            'signature'     => ['doc_type' => 'signature',     'remarks' => 'Specimen signature / Thumb impression'],
        ];

        if ($type && isset($typeMap[$type])) {
            $validated['doc_type'] = $typeMap[$type]['doc_type'];
            $validated['remarks']  = $typeMap[$type]['remarks'];
        }

        // Ensure doc_type exists (fallback)
        $validated['doc_type'] = $validated['doc_type'] ?? 'other';

        return DB::transaction(function () use ($request, $validated) {

            $employeeId = (int) $validated['employee_id'];
            $file = $request->file('file');

            $dir = "uploads/employees/{$employeeId}/docs";

            $originalBase = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeBase = Str::slug($originalBase) ?: 'document';

            $ext = strtolower($file->getClientOriginalExtension() ?: 'bin');

            $fileName = Str::slug($validated['doc_type'])
                . '_' . time()
                . '_' . $safeBase
                . '.' . $ext;

            $storedPath = $file->storeAs($dir, $fileName, 'public');

            EmployeeDocument::create([
                'employee_id' => $employeeId,
                'doc_type'    => $validated['doc_type'],
                'file_name'   => $file->getClientOriginalName(),
                'file_path'   => $storedPath,
                'file_size'   => $file->getSize(),
                'mime_type'   => $file->getMimeType(),
                'uploaded_by' => auth()->id(),
                'remarks'     => $validated['remarks'] ?? null,
                'is_active'   => (int) $request->boolean('is_active', true),
                'uploaded_at' => now(),
            ]);

            return redirect()
                ->route('employees.show', $employeeId)
                ->with('success', 'Document uploaded successfully.');
        });
    }

    public function edit(EmployeeDocument $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, EmployeeDocument $document)
    {
        $validated = $request->validate([
            'doc_type'  => ['required', 'string', 'max:50'],
            'remarks'   => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'in:0,1'],
        ]);

        $document->update([
            'doc_type'  => $validated['doc_type'],
            'remarks'   => $validated['remarks'] ?? null,
            'is_active' => (int) $validated['is_active'],
        ]);

        return redirect()
            ->route('employees.show', $document->employee_id)
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(EmployeeDocument $document)
    {
        // Optional: delete physical file
        // if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
        //     Storage::disk('public')->delete($document->file_path);
        // }

        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function deactivate(EmployeeDocument $document)
    {
        $document->update(['is_active' => 0]);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deactivated successfully.');
    }
}
