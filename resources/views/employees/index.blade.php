@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
@endpush

@section('content')

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div class="clearfix">
        <h1 class="app-page-title">
            <span class="text-primary"></span> Workforce
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Workforce</li>
            </ol>
        </nav>
    </div>

    <a href="{{ route('employees.create') }}" class="btn btn-primary waves-effect waves-light">
        <i class="fi fi-rr-plus me-1"></i> Add Employee
    </a>
</div>

<div class="card d-flex flex-row flex-wrap align-items-center h-auto mb-5">
    <ul class="nav nav-underline me-auto px-3 gap-2">
        <li class="nav-item">
            <h1 class="app-page-title mb-0">
                <span class="text-primary"></span> Workforce
                <span class="text-muted fw-normal fs-6 ms-2">({{ $employees->total() }})</span>
            </h1>
        </li>
    </ul>

    <div class="d-flex ps-3">
        <div class="d-flex align-items-center me-4">
            <button class="btn btn-link p-0 me-3 text-primary" type="button">
                <i class="fi fi-rr-apps text-sm"></i>
            </button>
            <button class="btn btn-link p-0 text-body" type="button">
                <i class="fi fi-br-list text-sm"></i>
            </button>
        </div>

        <div class="vr"></div>

        <form class="d-flex align-items-center h-100 w-150px w-lg-300px position-relative"
            action="{{ route('employees.index') }}"
            method="GET">
            <button type="submit" class="btn btn-sm border-0 position-absolute start-0 ms-3 p-0">
                <i class="fi fi-rr-search"></i>
            </button>

            <input type="text"
                name="q"
                value="{{ $q }}"
                class="form-control form-control-lg ps-5 rounded-start-0 border-0 shadow-none bg-transparent"
                placeholder="Search Employee">
        </form>
    </div>
</div>

<div class="row">
    @forelse($employees as $employee)
    @php
    $isActive = ((int)($employee->is_active ?? 0) === 1) && empty($employee->date_of_exit);

    $badgeClass = $isActive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';
    $badgeText = $isActive ? 'Active' : 'On Leave';

    $fullName = trim(($employee->first_name ?? '') . ' ' . ($employee->surname ?? ''));
    $designation = $employee->designation->title ?? $employee->designation->name ?? '-';
    $department = $employee->department->name ?? '-';

    $joiningDate = $employee->date_of_joining
    ? \Carbon\Carbon::parse($employee->date_of_joining)->format('d M Y')
    : '-';

    $mobile = $employee->mobile ?? '-';

    // ✅ Address (Location)
    $addressText = $employee->present_address ?: $employee->permanent_address;
    $addressText = $addressText ? \Illuminate\Support\Str::limit(strip_tags($addressText), 60) : null;

    // ✅ Profile photo from documents (remarks: "Profile photo") OR fallback
    $profileDoc = $employee->documents
    ? $employee->documents->firstWhere('remarks', 'Profile photo')
    : null;

    $photoUrl = $profileDoc && !empty($profileDoc->file_path)
    ? asset('storage/' . ltrim($profileDoc->file_path, '/'))
    : asset('assets/images/avatar/avatar-large1.jpg');

    $cardClass = $isActive ? '' : 'bg-danger-subtle border-0';
    @endphp

    <div class="col-xxl-3 col-lg-4 col-md-6 mb-4">
        <div class="card {{ $cardClass }}">
            <div class="card-header d-flex align-items-center justify-content-between border-0 pb-0 p-3">
                <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>

                <div class="clearfix">
                    <div class="btn-group">
                        <button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect dropdown-toggle"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fi fi-rr-menu-dots"></i>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(\Illuminate\Support\Facades\Route::has('employees.edit'))
                            <li>
                                <a class="dropdown-item" href="{{ route('employees.edit', $employee->id) }}">Edit</a>
                            </li>
                            @endif

                            @if(\Illuminate\Support\Facades\Route::has('employees.destroy'))
                            <li>
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="dropdown-item text-danger" type="submit">Delete</button>
                                </form>
                            </li>
                            @endif

                            @if(!\Illuminate\Support\Facades\Route::has('employees.edit') && !\Illuminate\Support\Facades\Route::has('employees.destroy'))
                            <li><a class="dropdown-item" href="javascript:void(0);">Edit</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);">Delete</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card-body p-2 pt-0">
                <div class="text-center mb-3">
                    <div class="avatar avatar-xxl rounded-4 mx-auto mb-3">
                        <img src="{{ $photoUrl }}" alt="{{ $fullName }}">
                    </div>

                    <h5 class="mb-0 fw-bold">
                        <a href="{{ route('employees.show', $employee->id) }}" class="text-dark text-decoration-none">
                            {{ $fullName ?: '-' }}
                        </a>
                    </h5>
                    <p class="text-primary mb-0 text-uppercase">{{ $designation }}</p>
                </div>

                <div class="p-3 {{ $isActive ? 'bg-light' : 'bg-body' }} rounded">
                    <div class="d-flex gap-3">
                        <div class="w-50">
                            <span class="text-1xs">Department</span>
                            <h6 class="mb-0">{{ $department }}</h6>
                        </div>
                        <div class="w-50">
                            <span class="text-1xs">Joining Date</span>
                            <h6 class="mb-0">{{ $joiningDate }}</h6>
                        </div>
                    </div>

                    <hr class="border-dashed">

                    <div class="d-grid gap-2">
                        @if($addressText)
                        <span class="d-block text-dark">
                            <i class="fi fi-rr-envelope me-2 text-primary"></i>
                            {{ $addressText }}
                        </span>
                        @endif

                        <span class="d-block text-dark">
                            <i class="fi fi-rr-phone-call me-2 text-primary"></i>
                            {{ $mobile }}
                        </span>


                    </div>
                </div>
            </div>

        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center py-5">
                <h5 class="mb-2">No employees found</h5>
                <p class="text-muted mb-0">Try searching by name, employee code, or mobile.</p>
            </div>
        </div>
    </div>
    @endforelse
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="float-end">
            {{ $employees->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- Keeping your modal as it is --}}
<!-- <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="modal-title">Add Employee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    @csrf
                    <div class="mb-3">
                        <label for="fullName" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="fullName" placeholder="Enter full name">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" placeholder="example@email.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" placeholder="+91 9876543210">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-select" id="department">
                                <option selected disabled>Select Department</option>
                                <option>HR</option>
                                <option>Development</option>
                                <option>Sales</option>
                                <option>Marketing</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="designation" placeholder="e.g. Software Engineer">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="joiningDate" class="form-label">Joining Date</label>
                            <input type="date" class="form-control flatpickr-date" id="joiningDate">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Employment Status</label>
                            <select class="form-select" id="status">
                                <option>Active</option>
                                <option>Inactive</option>
                                <option>Probation</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" rows="2" placeholder="Enter address"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Profile Photo</label>
                        <input class="form-control" type="file" id="photo">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-success">Add Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> -->

@endsection