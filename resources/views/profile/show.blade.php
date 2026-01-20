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
@endpush

@section('content')

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div class="clearfix">
        <h1 class="app-page-title">Profile</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('index') }}">Dashboard</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Profile</li>
            </ol>
        </nav>
    </div>
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
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="position-relative">
                            <div class="avatar avatar-xxl rounded-circle">
                                <img src="{{ $photoUrl ?? asset('assets/images/users/default-avatar.png') }}" alt="Profile Photo">
                            </div>
                        </div>

                        <div class="ms-3">
                            <h4 class="fw-bold mb-0">{{ $fullName ?? 'Profile' }}</h4>
                            @if(!empty($username))
                            <small class="mb-2 text-muted">{{ $username }}</small>
                            @endif

                            <div class="d-flex flex-wrap gap-1 mt-2">
                                <span class="badge badge-sm px-2 rounded-pill {{ ($status ?? 'Active') === 'Inactive' ? 'text-bg-danger' : 'text-bg-success' }}">
                                    {{ $status ?? 'Active' }}
                                </span>
                                @if(!empty($lastLogin))
                                <span class="badge badge-sm px-2 rounded-pill text-bg-secondary">
                                    Last Login: {{ \Carbon\Carbon::parse($lastLogin)->format('d M Y, h:i A') }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 ms-md-auto">
                        <a href="{{ route('index') }}" class="btn btn-outline-secondary waves-effect waves-light">
                            Back
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- LEFT SIDE --}}
    <div class="col-lg-4 col-sm-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Basic Information</h4>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <span class="mb-1">Full Name</span>
                            <p class="text-dark fw-semibold mb-0">{{ $fullName ?? '-' }}</p>
                        </div>

                        <div class="mb-3">
                            <span class="mb-1">Username</span>
                            <p class="text-dark fw-semibold mb-0">{{ $username ?? '-' }}</p>
                        </div>

                        <div class="mb-3">
                            <span class="mb-1">Email</span>
                            <p class="text-dark fw-semibold mb-0">{{ $email ?? '-' }}</p>
                        </div>

                        <div class="mb-2">
                            <span class="mb-1">Status</span>
                            <p class="text-dark fw-semibold mb-0">{{ $status ?? '-' }}</p>
                        </div>

                        @if(!empty($lastLogin))
                        <div class="mb-2">
                            <span class="mb-1">Last Login</span>
                            <p class="text-dark fw-semibold mb-0">{{ \Carbon\Carbon::parse($lastLogin)->format('d M Y, h:i A') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="col-lg-8 col-sm-12">
        <div class="row">

            {{-- Account Settings (Read-only info) --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Account Settings</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="{{ $fullName ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" value="{{ $email ?? '' }}" readonly>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control" value="{{ $username ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <input type="text" class="form-control" value="{{ $status ?? '' }}" readonly>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            {{-- ✅ Change Password --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Change Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.password.update') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                    <input type="password"
                                        name="current_password"
                                        class="form-control @error('current_password') is-invalid @enderror"
                                        placeholder="Enter current password"
                                        required>
                                    @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">New Password <span class="text-danger">*</span></label>
                                    <input type="password"
                                        name="new_password"
                                        class="form-control @error('new_password') is-invalid @enderror"
                                        placeholder="Enter new password"
                                        required>
                                    @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password <span class="text-danger">*</span></label>
                                    <input type="password"
                                        name="new_password_confirmation"
                                        class="form-control"
                                        placeholder="Confirm new password"
                                        required>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="submit" class="btn btn-success w-100 waves-effect waves-light">
                                        <i class="fi fi-rr-lock me-1"></i> Update Password
                                    </button>
                                </div>
                            </div>

                            <small class="text-muted">
                                Password must be at least 8 characters.
                            </small>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Danger Zone (optional - keep UI) --}}
            <div class="col-12">
                <div class="card border border-danger bg-danger-subtle border-2">
                    <div class="card-header border-0 pb-0">
                        <h5 class="text-danger fw-bold mb-0">Danger Zone</h5>
                        <small>Critical actions that affect your account.</small>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-between align-items-start mb-4 flex-wrap">
                            <div class="pe-3">
                                <h6 class="text-danger mb-1">Delete Account</h6>
                                <p class="mb-0 small">This action is <strong>permanent</strong> and cannot be undone.</p>
                            </div>
                            <button class="btn btn-danger waves-effect waves-light" type="button" disabled>
                                Delete Account
                            </button>
                        </div>
                        <hr class="border-danger my-3">
                        <div class="d-flex gap-3 justify-content-between align-items-start flex-wrap">
                            <div class="pe-3">
                                <h6 class="text-primary mb-1">Export Your Data</h6>
                                <p class="mb-0 small">Backup your data in case you need it later.</p>
                            </div>
                            <button class="btn btn-outline-primary waves-effect waves-light" type="button" disabled>
                                Export Data
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">
                            (These buttons are disabled unless you implement routes/actions for them.)
                        </small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection