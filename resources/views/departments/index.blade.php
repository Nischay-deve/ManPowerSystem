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
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
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
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- end::GXON Page Scripts -->
@endpush

@section('content')

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div class="clearfix">
        <h1 class="app-page-title">Department</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Department</li>
            </ol>
        </nav>
    </div>

    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
        <i class="fi fi-rr-plus me-1"></i> Add Department
    </button>
</div>

{{-- Alerts --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fi fi-rr-check me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fi fi-rr-cross-circle me-1"></i> {{ $errors->first() }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card overflow-hidden">
            <div class="card-header d-flex gap-3 flex-wrap align-items-center justify-content-between border-0 pb-0">
                <h6 class="card-title mb-0">Department</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div id="dt_PageEmployeeLeave_Search"></div>
                </div>
            </div>

            <div class="card-body p-2">
                <div class="table-responsive">
                    <table id="dt_PageEmployeeLeave" class="table display table-row-rounded align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="minw-90px">ID</th>
                                <th class="minw-160px">Name</th>
                                <th class="minw-200px">Description</th>
                                <th class="minw-150px">Code</th>
                                <th class="minw-150px">Creation</th>
                                <th class="minw-150px">Updation</th>
                                <th class="minw-100px">Status</th>
                                <th class="minw-150px text-end">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($departments as $dept)
                            @php
                            $isActive = (int)($dept->is_active ?? 1) === 1;
                            $statusText = $isActive ? 'Active' : 'Inactive';
                            $statusClass = $isActive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';

                            $desc = $dept->notes ?? $dept->description ?? '-';
                            $created = $dept->created_at ? \Carbon\Carbon::parse($dept->created_at)->format('d-M-Y') : '-';
                            $updated = $dept->updated_at ? \Carbon\Carbon::parse($dept->updated_at)->format('d-M-Y') : '-';
                            $currentStatusValue = $isActive ? 'Active' : 'Inactive';
                            @endphp

                            <tr>
                                <td>
                                    <div class="d-flex align-items-center mw-175px">
                                        <div class="avatar avatar-xxs rounded-circle">
                                            <img src="{{ asset('assets/images/avatar/avatar1.jpg') }}" alt="">
                                        </div>
                                        <div class="ms-2 me-auto">{{ $dept->id }}</div>
                                    </div>
                                </td>

                                <td>
                                    <span class="text-success">{{ $dept->name ?? '-' }}</span>
                                </td>

                                <td>{{ $desc }}</td>

                                <td>{{ $dept->code ?? '-' }}</td>

                                <td>{{ $created }}</td>

                                <td>{{ $updated }}</td>

                                <td>
                                    <span class="badge badge-lg {{ $statusClass }}">{{ $statusText }}</span>
                                </td>

                                <td class="text-end">
                                    <button type="button"
                                        class="badge badge-lg bg-success-subtle text-success border-0"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDepartmentModal{{ $dept->id }}">
                                        Edit
                                    </button>

                                    <form action="{{ route('departments.destroy', $dept->id) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Deactivate this department?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="badge badge-lg bg-danger-subtle text-danger border-0">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- ✅ EDIT MODAL (Dynamic) --}}
                            @push('modals')
                            <div class="modal fade" id="editDepartmentModal{{ $dept->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header py-3">
                                            <h5 class="modal-title">Edit Department</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('departments.update', $dept->id) }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label class="form-label">Department Name</label>
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ old('name', $dept->name) }}" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <textarea name="notes" class="form-control" rows="3"
                                                        placeholder="Enter department description">{{ old('notes', $dept->notes ?? $dept->description) }}</textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Department Code</label>
                                                    <input type="text" name="code" class="form-control"
                                                        value="{{ old('code', $dept->code) }}" placeholder="Enter department code">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select" required>
                                                        <option value="Active" {{ old('status', $currentStatusValue) == 'Active' ? 'selected' : '' }}>Active</option>
                                                        <option value="Inactive" {{ old('status', $currentStatusValue) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>

                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            @endpush
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    No departments found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ✅ ADD MODAL (Dynamic) --}}
@push('modals')
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title">Add Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('departments.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Department Name</label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name') }}" placeholder="Enter department name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="notes" class="form-control" rows="3"
                            placeholder="Enter department description">{{ old('notes') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Department Code</label>
                        <input type="text" name="code" class="form-control"
                            value="{{ old('code') }}" placeholder="Enter department code">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="Active" {{ old('status','Active') == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
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
@endpush

{{-- ✅ Render all pushed modals here --}}
@stack('modals')

@endsection