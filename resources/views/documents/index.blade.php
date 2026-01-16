@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div class="clearfix">
        <h1 class="app-page-title">Documents</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Documents</li>
            </ol>
        </nav>
    </div>

    <a href="{{ route('documents.create') }}" class="btn btn-primary">
        <i class="fi fi-rr-plus me-1"></i> Upload Document
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form class="row g-2 align-items-center" method="GET" action="{{ route('documents.index') }}">
            <div class="col-lg-5">
                <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                    placeholder="Search employee / doc_type / file / remarks">
            </div>

            <div class="col-lg-3">
                <select name="doc_type" class="form-select">
                    <option value="">All Types</option>
                    @foreach(['photo','aadhaar','bank_proof','signature'] as $t)
                    <option value="{{ $t }}" {{ ($docType ?? '')===$t ? 'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2">
                <select name="active" class="form-select">
                    <option value="">All</option>
                    <option value="1" {{ (string)($active ?? '')==='1' ? 'selected':'' }}>Active</option>
                    <option value="0" {{ (string)($active ?? '')==='0' ? 'selected':'' }}>Inactive</option>
                </select>
            </div>

            <div class="col-lg-2 d-grid">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fi fi-rr-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Remarks</th>
                    <th>File</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($documents as $doc)
                @php
                $emp = $doc->employee;
                $empName = $emp ? trim(($emp->first_name ?? '').' '.($emp->surname ?? '')) : '—';
                $code = $emp->employee_code ?? '—';
                $isImage = str_starts_with((string)$doc->mime_type, 'image/');
                $url = $doc->file_path ? asset('storage/'.$doc->file_path) : null;
                @endphp
                <tr>
                    <td>
                        <div class="fw-bold">{{ $empName }}</div>
                        <div class="text-muted text-sm">{{ $code }}</div>
                    </td>
                    <td>{{ $doc->doc_type }}</td>
                    <td>{{ $doc->remarks ?: '—' }}</td>
                    <td>
                        @if($url)
                        <a href="{{ $url }}" target="_blank">{{ $doc->file_name }}</a>
                        @if($isImage)
                        <div class="mt-1">
                            <img src="{{ $url }}" style="height:34px;width:34px;object-fit:cover;border-radius:6px;border:1px solid #eee;">
                        </div>
                        @endif
                        @else
                        —
                        @endif
                    </td>
                    <td>
                        @if($doc->is_active)
                        <span class="badge bg-success-subtle text-success">Active</span>
                        @else
                        <span class="badge bg-light text-body">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if($doc->is_active)
                        <form class="d-inline" method="POST" action="{{ route('documents.deactivate', $doc->id) }}">
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-sm btn-outline-warning" type="submit">Deactivate</button>
                        </form>
                        @endif

                        <form class="d-inline" method="POST" action="{{ route('documents.destroy', $doc->id) }}"
                            onsubmit="return confirm('Delete this document record?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="alert alert-warning mb-0">No documents found.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $documents->links() }}
        </div>
    </div>
</div>

@endsection