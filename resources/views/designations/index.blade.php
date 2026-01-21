@extends('layouts.app')

@push('styles')
<!-- begin::GXON Required Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<!-- end::GXON Required Stylesheet -->

<!-- begin::GXON CSS Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
<!-- end::GXON CSS Stylesheet -->
@endpush

@push('scripts')
<!-- begin::GXON Page Scripts -->
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/chartjs/chart.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- end::GXON Page Scripts -->
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Validation Error:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div class="clearfix">
        <h1 class="app-page-title">Designation</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Designation</li>
            </ol>
        </nav>
    </div>

    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
        <i class="fi fi-rr-plus me-1"></i> Add Designation
    </button>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card overflow-hidden">
            <div class="card-header d-flex gap-3 flex-wrap align-items-center justify-content-between border-0 pb-0">
                <h6 class="card-title mb-0">Designation</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div id="dt_PageEmployeeLeave_Search"></div>
                </div>
            </div>

            <div class="card-body p-2">
                <table id="dt_PageEmployeeLeave" class="table display table-row-rounded">
                    <thead class="table-light">
                        <tr>
                            <th class="minw-120px">ID</th>
                            <th class="minw-180px">Name</th>
                            <th class="minw-220px">Description</th>
                            <th class="minw-120px">Code</th>
                            <th class="minw-150px">Creation</th>
                            <th class="minw-150px">Updation</th>
                            <th class="minw-120px">Status</th>
                            <th class="minw-140px text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($designations as $d)
                        @php
                        $statusText = (int)($d->is_active ?? 1) === 1 ? 'Active' : 'Inactive';
                        $badgeClass = $statusText === 'Active'
                        ? 'bg-success-subtle text-success'
                        : 'bg-danger-subtle text-danger';

                        $created = $d->created_at ? $d->created_at->format('d-M-Y') : '-';
                        $updated = $d->updated_at ? $d->updated_at->format('d-M-Y') : '-';

                        // show DB code if exists else fallback
                        $code = $d->code ?? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr((string)$d->title, 0, 3));
                        @endphp

                        <tr>
                            <td>
                                <div class="d-flex align-items-center mw-175px">
                                    <div class="avatar avatar-xxs rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                                        <i class="fi fi-rr-id-badge"></i>
                                    </div>
                                    <div class="ms-2 me-auto">{{ $d->id }}</div>
                                </div>
                            </td>

                            <td>
                                <span class="text-success">{{ $d->title }}</span>
                            </td>

                            <td>{{ $d->notes ?: '—' }}</td>

                            <td>{{ $code }}</td>

                            <td>{{ $created }}</td>

                            <td>{{ $updated }}</td>

                            <td>
                                <span class="badge badge-lg {{ $badgeClass }}">{{ $statusText }}</span>
                            </td>

                            <td class="text-end">
                                <button type="button"
                                    class="badge badge-lg bg-success-subtle text-success border-0"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDesignationModal{{ $d->id }}">
                                    Edit
                                </button>

                                <form class="d-inline" method="POST" action="{{ route('designations.destroy', $d->id) }}"
                                    onsubmit="return confirm('Delete this designation?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="badge badge-lg bg-danger-subtle text-danger border-0">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- ✅ EDIT MODAL (PER ROW) --}}
                        <div class="modal fade" id="editDesignationModal{{ $d->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header py-3">
                                        <h5 class="modal-title">Edit Designation</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <div class="modal-body">
                                        <form method="POST" action="{{ route('designations.update', $d->id) }}">
                                            @csrf
                                            @method('PUT')

                                            <div class="mb-3">
                                                <label class="form-label">Designation Name</label>
                                                <input type="text" name="title" class="form-control"
                                                    value="{{ old('title', $d->title) }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="notes" class="form-control" rows="3"
                                                    placeholder="Enter designation description">{{ old('notes', $d->notes) }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Designation Code (optional)</label>
                                                <input type="text" name="code" class="form-control"
                                                    value="{{ old('code', $d->code) }}" placeholder="Enter designation code">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-select" required>
                                                    <option value="Active" {{ old('status', $statusText)=='Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive" {{ old('status', $statusText)=='Inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No designations found.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

{{-- ✅ ADD MODAL --}}
<div class="modal fade" id="addDesignationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title">Add Designation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('designations.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Designation Name</label>
                        <input type="text" name="title" class="form-control"
                            placeholder="Enter designation name" value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Enter designation description">{{ old('notes') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Designation Code (optional)</label>
                        <input type="text" name="code" class="form-control"
                            placeholder="Enter designation code" value="{{ old('code') }}">
                        <small class="text-muted">If your DB doesn’t have this column, controller ignores it.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Active" {{ old('status','Active')=='Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status')=='Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection