@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/chartjs/chart.js') }}"></script>
<script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        if (window.$ && $.fn.DataTable) {
            const dt = $('#dt_PageDesignations').DataTable({
                pageLength: 10,
                lengthChange: true,
                ordering: true,
                searching: true,
                info: true,
                autoWidth: false,
                dom: "<'row align-items-center g-2'<'col-md-6'l><'col-md-6'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row align-items-center g-2'<'col-md-6'i><'col-md-6'p>>",
                initComplete: function() {
                    $('#dt_PageDesignations_filter').appendTo('#dt_PageDesignations_Search');
                }
            });
        }

        // ✅ Edit Modal Populate + Action set
        document.querySelectorAll('[data-edit-designation]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const title = btn.dataset.title || '';
                const notes = btn.dataset.notes || '';
                const status = btn.dataset.status || 'Active';
                const employees = btn.dataset.employees || 0;

                document.getElementById('edit_title').value = title;
                document.getElementById('edit_notes').value = notes;
                document.getElementById('edit_status').value = status;
                document.getElementById('edit_employees').value = employees;

                const form = document.getElementById('editDesignationForm');
                form.action = "{{ url('/designations') }}/" + id;
            });
        });

    });
</script>
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
        <h1 class="app-page-title">Designations</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Designations</li>
            </ol>
        </nav>
    </div>

    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addDesignationModal">
        <i class="fi fi-rr-plus me-1"></i> Add Designation
    </button>
</div>

<div class="row">
    {{-- Stats Cards (Dynamic) --}}
    <div class="col-xxl-4 col-md-6">
        <div class="card card-action action-border-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="clearfix ps-2">
                    <div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
                        <span class="fs-2 fw-bold">{{ $total }}</span>
                    </div>
                    <span class="text-primary">Total Designations</span>
                </div>
                <div class="avatar avatar-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                    <i class="fi fi-rr-id-badge"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-4 col-md-6">
        <div class="card card-action action-border-success">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="clearfix ps-2">
                    <div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
                        <span class="fs-2 fw-bold">{{ $active }}</span>
                    </div>
                    <span class="text-success">Active</span>
                </div>
                <div class="avatar avatar-md rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center">
                    <i class="fi fi-rr-check"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xxl-4 col-md-6">
        <div class="card card-action action-border-danger">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="clearfix ps-2">
                    <div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
                        <span class="fs-2 fw-bold">{{ $inactive }}</span>
                    </div>
                    <span class="text-danger">Inactive</span>
                </div>
                <div class="avatar avatar-md rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center">
                    <i class="fi fi-rr-ban"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="col-lg-12">
        <div class="card overflow-hidden">
            <div class="card-header d-flex gap-3 flex-wrap align-items-center justify-content-between border-0 pb-0">
                <h6 class="card-title mb-0">Designation List</h6>
                <div class="d-flex gap-3 flex-wrap">
                    <div id="dt_PageDesignations_Search"></div>
                    <button type="button" class="btn btn-sm btn-outline-light btn-shadow waves-effect">
                        <i class="fi fi-rr-download me-1"></i> Download Report
                    </button>
                    <select class="selectpicker" data-style="btn-sm btn-outline-light btn-shadow waves-effect">
                        <option selected>2026</option>
                        <option>2025</option>
                        <option>2024</option>
                        <option>2023</option>
                    </select>
                </div>
            </div>

            <div class="card-body p-2">
                <table id="dt_PageDesignations" class="table display table-row-rounded">
                    <thead class="table-light">
                        <tr>
                            <th class="minw-220px">Designation</th>
                            <th class="minw-120px">Employees</th>
                            <th class="minw-140px">Created</th>
                            <th class="minw-120px">Status</th>
                            <th class="minw-100px text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($designations as $d)
                        @php
                        $statusText = $d->is_active ? 'Active' : 'Inactive';
                        $badgeClass = $d->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                        $created = $d->created_at ? $d->created_at->format('d M Y') : '-';
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xxs rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                                        <i class="fi fi-rr-user"></i>
                                    </div>
                                    <div class="ms-2">
                                        <div class="fw-semibold">{{ $d->title }}</div>
                                        <small class="text-muted">{{ $d->notes ?: '—' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $d->employees_count }}</td>
                            <td>{{ $created }}</td>
                            <td><span class="badge {{ $badgeClass }}">{{ $statusText }}</span></td>
                            <td class="text-end">
                                <div class="btn-group float-end">
                                    <button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fi fi-rr-menu-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item"
                                                href="javascript:void(0);"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editDesignationModal"
                                                data-edit-designation
                                                data-id="{{ $d->id }}"
                                                data-title="{{ $d->title }}"
                                                data-notes="{{ $d->notes }}"
                                                data-status="{{ $statusText }}"
                                                data-employees="{{ $d->employees_count }}">
                                                Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('designations.destroy', $d->id) }}"
                                                onsubmit="return confirm('Delete this designation?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item text-danger" type="submit">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No designations found.</td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Designation Modal --}}
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
                        <label class="form-label">Designation Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter designation title" value="{{ old('title') }}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Active" {{ old('status','Active')=='Active'?'selected':'' }}>Active</option>
                                <option value="Inactive" {{ old('status')=='Inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Optional notes...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Save Designation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Edit Designation Modal --}}
<div class="modal fade" id="editDesignationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title">Edit Designation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="editDesignationForm" method="POST" action="">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Designation Title</label>
                        <input id="edit_title" type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="edit_status" name="status" class="form-select" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    {{-- Keep this column (not editable), as per your design --}}
                    <div class="mb-3">
                        <label class="form-label">Employees (count)</label>
                        <input id="edit_employees" type="number" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea id="edit_notes" name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection