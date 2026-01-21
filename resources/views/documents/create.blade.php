@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@section('content')

@php
// Read from query string: /documents/create?employee_id=1&type=profile_photo
$type = request()->get('type');

// Must match your DocumentController::$typeMap
$typeMap = [
'profile_photo' => ['doc_type' => 'photo', 'remarks' => 'Profile photo', 'label' => 'Profile Photo'],
'aadhaar_front' => ['doc_type' => 'aadhaar_front', 'remarks' => 'Aadhaar front side', 'label' => 'Aadhaar Front'],
'aadhaar_back' => ['doc_type' => 'aadhaar_back', 'remarks' => 'Aadhaar back side', 'label' => 'Aadhaar Back'],
'bank_proof' => ['doc_type' => 'bank_proof', 'remarks' => 'Bank proof', 'label' => 'Bank Proof'],
'signature' => ['doc_type' => 'signature', 'remarks' => 'Specimen signature / Thumb impression', 'label' => 'Signature / Thumb'],
];

$isForced = $type && isset($typeMap[$type]);

// Forced values (when coming from Employee Profile "Upload" button)
$forcedDocType = $isForced ? $typeMap[$type]['doc_type'] : null;
$forcedRemarks = $isForced ? $typeMap[$type]['remarks'] : null;
$pageTitle = $isForced ? ('Upload ' . $typeMap[$type]['label']) : 'Upload Document';

// For dropdown (manual uploads)
$docTypeOptions = [
'photo' => 'Photo',
'aadhaar_front'=> 'Aadhaar Front',
'aadhaar_back' => 'Aadhaar Back',
'bank_proof' => 'Bank Proof',
'signature' => 'Signature / Thumb',
'other' => 'Other',
];
@endphp

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div class="clearfix">
        <h1 class="app-page-title">{{ $pageTitle }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
                <li class="breadcrumb-item active">Upload</li>
            </ol>
        </nav>
    </div>

    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
        <i class="fi fi-rr-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data" class="row g-3">
            @csrf

            {{-- ✅ IMPORTANT: send type to controller so it can enforce doc_type + remarks --}}
            @if($type)
            <input type="hidden" name="type" value="{{ $type }}">
            @endif

            <div class="col-md-6">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-select @error('employee_id') is-invalid @enderror" required>
                    <option value="">Select Employee</option>
                    @foreach(($employees ?? []) as $e)
                    @php
                    $name = trim(($e->first_name ?? '').' '.($e->surname ?? ''));
                    $label = trim(($e->employee_code ?? '').' - '.$name);
                    @endphp
                    <option value="{{ $e->id }}"
                        {{ (string)old('employee_id', $employeeId ?? request()->get('employee_id', '')) === (string)$e->id ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('employee_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- DOCUMENT TYPE --}}
            <div class="col-md-6">
                <label class="form-label">Document Type <span class="text-danger">*</span></label>

                @if($isForced)
                {{-- ✅ Forced upload: show readonly text + hidden doc_type --}}
                <input type="hidden" name="doc_type" value="{{ old('doc_type', $forcedDocType) }}">
                <input type="text" class="form-control" value="{{ $docTypeOptions[$forcedDocType] ?? $forcedDocType }}" readonly>
                <small class="text-muted">This is fixed for this upload.</small>
                @else
                {{-- Manual upload: choose doc_type --}}
                <select name="doc_type" class="form-select @error('doc_type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    @foreach($docTypeOptions as $val => $label)
                    <option value="{{ $val }}" {{ old('doc_type') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('doc_type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @endif
            </div>

            {{-- REMARKS --}}
            <div class="col-12">
                <label class="form-label">Remarks</label>

                @if($isForced)
                {{-- ✅ Forced upload: show readonly remarks + hidden remarks --}}
                <input type="hidden" name="remarks" value="{{ old('remarks', $forcedRemarks) }}">
                <input type="text" class="form-control" value="{{ old('remarks', $forcedRemarks) }}" readonly>
                <small class="text-muted">Remarks are auto-set to match required document mapping.</small>
                @else
                <input type="text" name="remarks" value="{{ old('remarks') }}"
                    class="form-control @error('remarks') is-invalid @enderror"
                    placeholder="Optional notes about this document">
                @error('remarks')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                @endif
            </div>

            {{-- STATUS --}}
            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active','1') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            {{-- FILE --}}
            <div class="col-md-6">
                <label class="form-label">File <span class="text-danger">*</span></label>
                <input type="file" name="file"
                    class="form-control @error('file') is-invalid @enderror"
                    accept=".jpg,.jpeg,.png,.webp,.pdf" required>
                <small class="text-muted">Allowed: jpg, jpeg, png, webp, pdf. Max 5MB.</small>
                @error('file')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="fi fi-rr-upload me-1"></i> Upload
                </button>
            </div>
        </form>

    </div>
</div>

@endsection