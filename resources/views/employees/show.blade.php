{{-- resources/views/employees/show.blade.php --}}
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">

<style>
    .compact-card .card-header {
        padding: .65rem 1rem;
    }

    .compact-card .card-body {
        padding: .85rem 1rem;
    }

    .kv span {
        display: block;
        font-size: 12px;
        color: #6c757d;
    }

    .kv p {
        margin: 0;
        font-weight: 600;
    }

    .kv {
        margin-bottom: .65rem;
    }
</style>
@endpush

@section('content')

@php
// ✅ Only active docs (latest first)
$docs = $employee->documents
? $employee->documents->where('is_active', 1)->sortByDesc('id')->values()
: collect();

// ✅ Helper: match by doc_type OR remarks (supports old rows where remarks is NULL)
$getDoc = function(array $docTypes = [], array $remarks = []) use ($docs) {
return $docs->first(function($d) use ($docTypes, $remarks) {
$byType = in_array((string)($d->doc_type ?? ''), $docTypes, true);
$byRemark = !empty($d->remarks) && in_array((string)$d->remarks, $remarks, true);
return $byType || $byRemark;
});
};

// ✅ Photo: support both old + new mappings
$photoDoc = $getDoc(['photo', 'profile_photo'], ['Profile photo']);
$photoUrlLocal = $photoDoc ? asset('public/storage/'.$photoDoc->file_path) : asset('assets/images/avatar/avatar-large3.jpg');

// If controller already sent $photoUrl, prefer it when available
$photoUrl = isset($photoUrl) && $photoUrl ? $photoUrl : $photoUrlLocal;

$fullName = trim(($employee->first_name ?? '').' '.($employee->surname ?? ''));

// ✅ Date helper: use uploaded_at (your DB) OR created_at (if later you add timestamps)
$docDate = function($doc) {
if (!$doc) return null;
if (!empty($doc->uploaded_at)) return \Carbon\Carbon::parse($doc->uploaded_at)->format('d M Y');
if (!empty($doc->created_at)) return \Carbon\Carbon::parse($doc->created_at)->format('d M Y');
return null;
};
@endphp

{{-- PAGE HEADER --}}
<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div>
        <h1 class="app-page-title">Employee Profile</h1>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('employees.index') }}">Employees</a>
                </li>
                <li class="breadcrumb-item active">View</li>
            </ol>
        </nav>
    </div>

    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
        Back
    </a>
</div>

{{-- PROFILE CARD --}}
<div class="card mb-3 compact-card">
    <div class="card-body">
        <div class="d-flex flex-wrap gap-4 align-items-center">

            <div class="text-center">
                <div class="avatar avatar-xxl rounded-circle mb-2">
                    <img src="{{ $photoUrl }}" alt="Employee Photo">
                </div>

                {{-- If profile photo not uploaded --}}
                @if(!$photoDoc)
                <a href="{{ route('documents.create', ['employee_id' => $employee->id, 'type' => 'profile_photo']) }}"
                    class="btn btn-sm btn-outline-primary">
                    <i class="fi fi-rr-camera me-1"></i> Add Profile Photo
                </a>
                @endif
            </div>

            <div>
                <h4 class="fw-bold mb-0">{{ $fullName ?: '-' }}</h4>
                <small class="text-muted">
                    {{ $employee->designation?->title ?? '—' }}
                </small>

                <div class="mt-2">
                    <span class="badge {{ ((int)($employee->is_active ?? 0) === 1) ? 'text-bg-success' : 'text-bg-danger' }}">
                        {{ ((int)($employee->is_active ?? 0) === 1) ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="badge text-bg-secondary">
                        Code: {{ $employee->employee_code ?? '-' }}
                    </span>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="row g-3">

    {{-- LEFT PANEL --}}
    <div class="col-lg-4">

        {{-- BASIC INFO --}}
        <div class="card mb-1 compact-card">
            <div class="card-header">
                <h4 class="card-title mb-0">Basic Information</h4>
            </div>

            <div class="card-body">
                <div class="kv">
                    <span>Full Name</span>
                    <p>{{ $fullName ?: '-' }}</p>
                </div>

                <div class="kv">
                    <span>Employee Code</span>
                    <p>{{ $employee->employee_code ?? '-' }}</p>
                </div>

                <div class="kv">
                    <span>Mobile</span>
                    <p>{{ $employee->mobile ?? '-' }}</p>
                </div>

                <div class="kv">
                    <span>Gender</span>
                    <p>{{ $employee->gender ?? '-' }}</p>
                </div>

                <div class="kv">
                    <span>Nationality</span>
                    <p>{{ $employee->nationality ?? '-' }}</p>
                </div>

                <div class="kv">
                    <span>Date of Birth</span>
                    <p>{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : '-' }}</p>
                </div>

                <div class="kv">
                    <span>Father / Spouse Name</span>
                    <p>{{ $employee->father_or_spouse_name ?? '-' }}</p>
                </div>

                <div class="kv">
                    <span>Category</span>
                    <p>{{ $employee->category ?? '-' }}</p>
                </div>

                <div class="kv">
                    <span>Remarks</span>
                    <p>{{ $employee->remarks ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- BANK INFO --}}
        <div class="card compact-card">
            <div class="card-header">
                <h4 class="card-title mb-0">Bank Information</h4>
            </div>

            <div class="card-body">
                <div class="kv">
                    <span>Account Holder</span>
                    <p>{{ $employee->primaryBankAccount?->account_holder_name ?? '—' }}</p>
                </div>

                <div class="kv">
                    <span>Account Number</span>
                    <p>
                        {{ $employee->primaryBankAccount?->account_number
                            ? '****' . substr($employee->primaryBankAccount->account_number, -4)
                            : '—' }}
                    </p>
                </div>

                <div class="kv">
                    <span>Bank Name</span>
                    <p>{{ $employee->primaryBankAccount?->bank_name ?? '—' }}</p>
                </div>

                <div class="kv">
                    <span>Branch</span>
                    <p>{{ $employee->primaryBankAccount?->branch ?? '—' }}</p>
                </div>

                <div class="kv mb-0">
                    <span>IFSC</span>
                    <p>{{ $employee->primaryBankAccount?->ifsc ?? '—' }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT PANEL --}}
    <div class="col-lg-8">

        {{-- EMPLOYMENT DETAILS --}}
        <div class="card mb-3 compact-card">
            <div class="card-header">
                <h4 class="card-title mb-0">Employment Details</h4>
            </div>

            <div class="card-body">
                <div class="row g-2">

                    <div class="col-md-4">
                        <div class="kv">
                            <span>Department</span>
                            <p>{{ $employee->department?->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="kv">
                            <span>Designation</span>
                            <p>{{ $employee->designation?->title ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="kv">
                            <span>Employment Type</span>
                            <p>{{ $employee->employment_type ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="kv">
                            <span>Salary</span>
                            <p>{{ isset($employee->salary) ? number_format((float)$employee->salary, 2) : '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="kv">
                            <span>Joining Date</span>
                            <p>{{ $employee->date_of_joining ? \Carbon\Carbon::parse($employee->date_of_joining)->format('d M Y') : '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="kv">
                            <span>Exit Date</span>
                            <p>{{ $employee->date_of_exit ? \Carbon\Carbon::parse($employee->date_of_exit)->format('d M Y') : '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="kv">
                            <span>Work Status</span>
                            <p>{{ ((int)($employee->is_active ?? 0) === 1) ? 'Active' : 'Inactive' }}</p>
                        </div>
                    </div>

                    <div class="col-12">
                        <hr class="my-2">
                    </div>

                    <div class="col-md-3">
                        <div class="kv">
                            <span>Aadhaar</span>
                            <p>{{ $employee->aadhaar ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="kv">
                            <span>PAN</span>
                            <p>{{ $employee->pan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="kv">
                            <span>UAN</span>
                            <p>{{ $employee->uan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="kv">
                            <span>ESIC IP</span>
                            <p>{{ $employee->esic_ip ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="kv">
                            <span>Present Address</span>
                            <p>{{ $employee->present_address ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="kv mb-0">
                            <span>Permanent Address</span>
                            <p>{{ $employee->permanent_address ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- DOCUMENTS --}}
        @php
        // ✅ We match documents by doc_type first, and remarks as fallback (old data)
        $requiredDocs = [
        [
        'label' => 'Profile Photo',
        'docTypes'=> ['photo', 'profile_photo'],
        'remarks' => ['Profile photo'],
        'type' => 'profile_photo',
        ],
        [
        'label' => 'Aadhaar Front',
        'docTypes'=> ['aadhaar_front', 'aadhaar'],
        'remarks' => ['Aadhaar front side'],
        'type' => 'aadhaar_front',
        ],
        [
        'label' => 'Aadhaar Back',
        'docTypes'=> ['aadhaar_back', 'aadhaar'],
        'remarks' => ['Aadhaar back side'],
        'type' => 'aadhaar_back',
        ],
        [
        'label' => 'Bank Proof',
        'docTypes'=> ['bank_proof'],
        'remarks' => ['Bank proof'],
        'type' => 'bank_proof',
        ],
        [
        'label' => 'Signature / Thumb',
        'docTypes'=> ['signature'],
        'remarks' => ['Specimen signature / Thumb impression'],
        'type' => 'signature',
        ],
        ];
        @endphp

        <div class="card compact-card">
            <div class="card-header">
                <h4 class="card-title mb-0">Documents</h4>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th width="170">Status</th>
                            <th width="220">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($requiredDocs as $item)
                        @php
                        $doc = $getDoc($item['docTypes'], $item['remarks']);
                        $isUploaded = (bool) $doc;
                        $uploadedAt = $docDate($doc);
                        @endphp

                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $item['label'] }}</div>
                                <small class="text-muted">{{ $item['remarks'][0] ?? '' }}</small>
                            </td>

                            <td>
                                @if($isUploaded)
                                <span class="badge bg-success-subtle text-success">Uploaded</span>
                                <div class="small text-muted mt-1">{{ $uploadedAt ?: '-' }}</div>
                                @else
                                <span class="badge bg-danger-subtle text-danger">Not Uploaded</span>
                                @endif
                            </td>

                            <td>
                                @if($isUploaded)
                                <a target="_blank" href="{{ asset('public/storage/'.$doc->file_path) }}"
                                    class="btn btn-sm btn-outline-primary me-1">
                                    View
                                </a>

                                <a href="{{ asset('public/storage/'.$doc->file_path) }}"
                                    class="btn btn-sm btn-primary" download>
                                    Download
                                </a>
                                @else
                                <a href="{{ route('documents.create', ['employee_id' => $employee->id, 'type' => $item['type']]) }}"
                                    class="btn btn-sm btn-success">
                                    <i class="fi fi-rr-upload me-1"></i> Upload
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach

                        {{-- ✅ Show extra uploaded docs not part of required list (exclude by doc_type OR remarks) --}}
                        @php
                        $requiredDocTypes = collect($requiredDocs)->pluck('docTypes')->flatten()->unique()->values()->toArray();
                        $requiredRemarks = collect($requiredDocs)->pluck('remarks')->flatten()->unique()->values()->toArray();

                        $extraDocs = $docs->filter(function($d) use ($requiredDocTypes, $requiredRemarks) {
                        $byType = in_array((string)($d->doc_type ?? ''), $requiredDocTypes, true);
                        $byRemark = !empty($d->remarks) && in_array((string)$d->remarks, $requiredRemarks, true);
                        return !$byType && !$byRemark;
                        });
                        @endphp

                        @if($extraDocs->count())
                        <tr class="table-light">
                            <td colspan="3" class="fw-bold">Other Uploaded Documents</td>
                        </tr>

                        @foreach($extraDocs as $doc)
                        <tr>
                            <td>{{ $doc->remarks ?? ($doc->doc_type ?? 'Document') }}</td>
                            <td>
                                <span class="badge bg-success-subtle text-success">Uploaded</span>
                                <div class="small text-muted mt-1">
                                    {{ $docDate($doc) ?: '-' }}
                                </div>
                            </td>
                            <td>
                                <a target="_blank" href="{{ asset('storage/'.$doc->file_path) }}"
                                    class="btn btn-sm btn-outline-primary me-1">
                                    View
                                </a>
                                <a href="{{ asset('storage/'.$doc->file_path) }}"
                                    class="btn btn-sm btn-primary" download>
                                    Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif

                    </tbody>
                </table>

                {{-- Optional button (kept commented like your original)
                <div class="p-3 text-end">
                    <a href="{{ route('documents.create', ['employee_id' => $employee->id]) }}"
                class="btn btn-sm btn-outline-secondary">
                <i class="fi fi-rr-plus me-1"></i> Upload Other Document
                </a>
            </div>
            --}}
        </div>
    </div>

</div>
</div>

@endsection