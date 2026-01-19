@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@section('content')

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
        <h1 class="app-page-title">Upload Document</h1>
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
                        {{ (string)old('employee_id', $employeeId ?? '') === (string)$e->id ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('employee_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Document Type <span class="text-danger">*</span></label>
                <select name="doc_type" class="form-select @error('doc_type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    @foreach(['photo','aadhaar','bank_proof','signature'] as $t)
                    <option value="{{ $t }}" {{ old('doc_type') === $t ? 'selected' : '' }}>
                        {{ $t }}
                    </option>
                    @endforeach
                </select>
                @error('doc_type')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <label class="form-label">Remarks</label>
                <input type="text" name="remarks" value="{{ old('remarks') }}"
                    class="form-control @error('remarks') is-invalid @enderror"
                    placeholder="Optional notes about this document">
                @error('remarks')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active','1') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

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