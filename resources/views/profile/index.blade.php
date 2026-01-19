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

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-4 align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="position-relative">
                            <div class="avatar avatar-xxl rounded-circle">
                                <img src="{{ $photoUrl }}" alt="">
                            </div>
                            <a href="javascript:void(0);" class="avatar avatar-xxs bg-primary rounded-circle text-white position-absolute top-0 end-0">
                                <i class="fi fi-rr-camera"></i>
                            </a>
                        </div>
                        <div class="ms-3">
                            <h4 class="fw-bold mb-0">{{ $fullName ?? 'Profile' }}</h4>
                            @if(!empty($designation))
                            <small class="mb-2">{{ $designation }}</small>
                            @endif

                            <div class="d-flex flex-wrap gap-1 mt-2">
                                @if(!empty($role))
                                <span class="badge badge-sm px-2 rounded-pill text-bg-primary">{{ $role }}</span>
                                @endif

                                @if(!empty($designation))
                                <span class="badge badge-sm px-2 rounded-pill text-bg-secondary">{{ $designation }}</span>
                                @endif

                                <span class="badge badge-sm px-2 rounded-pill {{ ($status ?? 'Active') === 'Exited' ? 'text-bg-danger' : 'text-bg-success' }}">
                                    {{ $status ?? 'Active' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 ms-md-auto">
                        <a href="{{ route('chat') }}" class="btn btn-primary waves-effect waves-light">Message</a>
                        <button type="button" class="btn btn-outline-secondary waves-effect waves-light">Follow</button>
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
                        <button class="btn btn-white btn-sm btn-shadow btn-icon waves-effect" type="button">
                            <i class="fi fi-rr-pencil"></i>
                        </button>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <span class="mb-1">Full Name</span>
                            <p class="text-dark fw-semibold mb-0">{{ $fullName ?? '-' }}</p>
                        </div>

                        @if(!empty($email))
                        <div class="mb-3">
                            <span class="mb-1">Email</span>
                            <p class="text-dark fw-semibold mb-0">{{ $email }}</p>
                        </div>
                        @endif

                        @if(!empty($phone))
                        <div class="mb-3">
                            <span class="mb-1">Phone</span>
                            <p class="text-dark fw-semibold mb-0">{{ $phone }}</p>
                        </div>
                        @endif

                        @if(!empty($dob))
                        <div class="mb-3">
                            <span class="mb-1">Date of Birth</span>
                            <p class="text-dark fw-semibold mb-0">{{ \Carbon\Carbon::parse($dob)->format('d M Y') }}</p>
                        </div>
                        @endif

                        @if(!empty($joinedDate))
                        <div class="mb-2">
                            <span class="mb-1">Joined Date</span>
                            <p class="text-dark fw-semibold mb-0">{{ \Carbon\Carbon::parse($joinedDate)->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Removed: Social Media Links / Expertise (no DB info provided) --}}
        </div>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="col-lg-8 col-sm-12">
        <div class="row">
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

                            @if(!empty($phone) || !empty($role))
                            <div class="row mb-3">
                                @if(!empty($phone))
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" value="{{ $phone }}" readonly>
                                </div>
                                @endif

                                @if(!empty($role))
                                <div class="col-md-6">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="{{ $role }}" readonly>
                                </div>
                                @endif
                            </div>
                            @endif

                            <div class="text-end">
                                <button type="button" class="btn btn-success waves-effect waves-light">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Danger Zone (kept same UI) --}}
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
                                <p class="mb-0 small">This action is <strong>permanent</strong> and cannot be undone. Please make sure you really want to delete your account.</p>
                            </div>
                            <button class="btn btn-danger waves-effect waves-light">Delete Account</button>
                        </div>
                        <hr class="border-danger my-3">
                        <div class="d-flex gap-3 justify-content-between align-items-start flex-wrap">
                            <div class="pe-3">
                                <h6 class="text-primary mb-1">Export Your Data</h6>
                                <p class="mb-0 small">Backup your data in case you decide to delete your account later.</p>
                            </div>
                            <button class="btn btn-outline-primary waves-effect waves-light">Export Data</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection