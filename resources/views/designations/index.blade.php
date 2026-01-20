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
    <!-- <script src="{{ asset('assets/js/dashboard.js') }}"></script> -->
    <script src="{{ asset('assets/js/appSettings.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <!-- end::GXON Page Scripts -->

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ✅ DataTable init (new table id)
            if (window.$ && $.fn.DataTable) {
                $('#dt_PageEmployeeLeave').DataTable({
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
                        $('#dt_PageEmployeeLeave_filter').appendTo('#dt_PageEmployeeLeave_Search');
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
                    const code = btn.dataset.code || '';

                    document.getElementById('edit_title').value = title;
                    document.getElementById('edit_notes').value = notes;
                    document.getElementById('edit_code').value = code;
                    document.getElementById('edit_status').value = status;

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

    <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
        <i class="fi fi-rr-plus me-1"></i> Add Designation
    </button>
</div>

{{-- (Optional) Stats row: kept from your current page --}}
<div class="row">
    <!-- <div class="col-xxl-4 col-md-6">
        <div class="card card-action action-border-primary">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div class="clearfix ps-2">
                    <div class="d-flex text-dark align-items-end gap-1 lh-1 mb-1">
                        <span class="fs-2 fw-bold">{{ $total ?? 0 }}</span>
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
                        <span class="fs-2 fw-bold">{{ $active ?? 0 }}</span>
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
                        <span class="fs-2 fw-bold">{{ $inactive ?? 0 }}</span>
                    </div>
                    <span class="text-danger">Inactive</span>
                </div>
                <div class="avatar avatar-md rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center">
                    <i class="fi fi-rr-ban"></i>
                </div>
            </div>
        </div>
    </div> -->

    {{-- TABLE --}}
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
                                $statusText = $d->is_active ? 'Active' : 'Inactive';
                                $badgeClass = $d->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
                                $created = $d->created_at ? $d->created_at->format('d-M-Y') : '-';
                                $updated = $d->updated_at ? $d->updated_at->format('d-M-Y') : '-';

                                // ✅ "code" column if exists, otherwise fallback to short name from title
                                $code = $d->code ?? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($d->title, 0, 3));
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
                                    <div class="select-status">
                                        <span class="badge badge-lg {{ $badgeClass }}">{{ $statusText }}</span>
                                    </div>
                                </td>

                                <td class="text-end">
                                    <a href="javascript:void(0);"
                                        class="badge badge-lg bg-success-subtle text-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editDesignationModal"
                                        data-edit-designation
                                        data-id="{{ $d->id }}"
                                        data-title="{{ $d->title }}"
                                        data-notes="{{ $d->notes }}"
                                        data-status="{{ $statusText }}"
                                        data-code="{{ $code }}">
                                        Edit
                                    </a>

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

{{-- ADD MODAL --}}
<div class="modal fade" id="addLeaveModal" tabindex="-1" aria-hidden="true">
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
                        <input type="text" name="title" class="form-control" placeholder="Enter designation name" value="{{ old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Enter designation description">{{ old('notes') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Designation Code (optional)</label>
                        <input type="text" name="code" class="form-control" placeholder="Enter designation code" value="{{ old('code') }}">
                        <small class="text-muted">If your DB doesn’t have this column, we can ignore it.</small>
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

{{-- EDIT MODAL --}}
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
                        <label class="form-label">Designation Name</label>
                        <input id="edit_title" type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="edit_notes" name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Designation Code (optional)</label>
                        <input id="edit_code" type="text" name="code" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="edit_status" name="status" class="form-select" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
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

@endsection
