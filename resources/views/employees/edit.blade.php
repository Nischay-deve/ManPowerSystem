@extends('layouts.app')

@section('title', 'Edit Workforce')

@push('styles')
<!-- begin::GXON Required Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
<!-- end::GXON Required Stylesheet -->

<!-- ✅ Date picker -->
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">

<!-- begin::GXON CSS Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
<!-- end::GXON CSS Stylesheet -->
@endpush

@push('scripts')
<!-- begin::GXON Page Scripts -->
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<!-- ✅ Date picker -->
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (window.flatpickr) {
            flatpickr(".js-date", {
                dateFormat: "Y-m-d",
                allowInput: true
            });
        }
    });
</script>
<!-- end::GXON Page Scripts -->
@endpush

@section('content')

@php
    // Documents (if relation exists)
    $docs = $employee->documents ? $employee->documents->where('is_active', 1) : collect();

    $photo        = $docs->firstWhere('remarks', 'Profile photo');
    $aadhaarFront = $docs->firstWhere('remarks', 'Aadhaar front side');
    $aadhaarBack  = $docs->firstWhere('remarks', 'Aadhaar back side');
    $bankProof    = $docs->firstWhere('remarks', 'Bank proof');
    $signature    = $docs->firstWhere('remarks', 'Specimen signature / Thumb impression');

    $photoUrl = $photo ? asset('storage/'.$photo->file_path) : asset('assets/images/avatar/avatar-large3.jpg');

    $bank = $employee->primaryBankAccount;

    // Work Status mapping
    $defaultWorkStatus = 'Active';
    if (!empty($employee->date_of_exit)) {
        $defaultWorkStatus = 'Exited';
    } elseif ((int)($employee->is_active ?? 1) === 0) {
        $defaultWorkStatus = 'On Leave';
    }
    $workStatus = old('work_status', $defaultWorkStatus);
@endphp

<div class="app-page-head">
    <h1 class="app-page-title">Edit Workforce</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('employees.index') }}">Workforce</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit Workforce</li>
        </ol>
    </nav>
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
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="card-title mb-0">Update Workforce Details</h6>
                <a href="{{ route('employees.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fi fi-rr-arrow-left me-1"></i> Back
                </a>
            </div>

            <div class="card-body">

                <form class="row"
                      method="POST"
                      action="{{ route('employees.update', $employee->id) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- ================= BASIC DETAILS ================= --}}
                    <div class="col-12">
                        <h6 class="mb-0">Basic Workforce Details</h6>
                        <hr class="mt-2">
                    </div>

                    {{-- ✅ Code (disabled UI, but send hidden for safety) --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Code</label>
                        <input type="text" class="form-control" value="{{ $employee->employee_code }}" disabled>
                        <input type="hidden" name="employee_code" value="{{ $employee->employee_code }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name"
                               class="form-control @error('first_name') is-invalid @enderror"
                               value="{{ old('first_name', $employee->first_name) }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Last Name</label>
                        <input type="text" name="surname" class="form-control"
                               value="{{ old('surname', $employee->surname) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Date of Birth</label>
                        <input type="text" name="date_of_birth" class="form-control js-date"
                               value="{{ old('date_of_birth', optional($employee->date_of_birth)->format('Y-m-d')) }}"
                               placeholder="YYYY-MM-DD">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Mobile Number</label>
                        <input type="text" name="mobile" class="form-control"
                               value="{{ old('mobile', $employee->mobile) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Nationality</label>
                        <select name="nationality" class="form-select">
                            <option value="">Select</option>
                            <option value="Indian" {{ old('nationality', $employee->nationality)=='Indian' ? 'selected' : '' }}>Indian</option>
                            <option value="Non Indian" {{ old('nationality', $employee->nationality)=='Non Indian' ? 'selected' : '' }}>Non Indian</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender', $employee->gender)=='Male'?'selected':'' }}>Male</option>
                            <option value="Female" {{ old('gender', $employee->gender)=='Female'?'selected':'' }}>Female</option>
                            <option value="Other" {{ old('gender', $employee->gender)=='Other'?'selected':'' }}>Other</option>
                        </select>
                    </div>

                    {{-- ✅ Work Status (mapped in controller) --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Work Status</label>
                        <select name="work_status" class="form-select">
                            <option value="Active" {{ $workStatus=='Active'?'selected':'' }}>Active</option>
                            <option value="On Leave" {{ $workStatus=='On Leave'?'selected':'' }}>On Leave</option>
                            <option value="Exited" {{ $workStatus=='Exited'?'selected':'' }}>Exited</option>
                        </select>
                        <small class="text-muted">Tip: If Exited, fill Date of Exit below.</small>
                    </div>

                    {{-- ✅ Current Photo + Upload --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Current Photo</label><br>
                        <img src="{{ $photoUrl }}" class="rounded border" style="height:70px;width:70px;object-fit:cover;">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Replace Photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/*">
                    </div>

                    {{-- ================= EMPLOYMENT DETAILS ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Employment Details</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Date of Joining</label>
                        <input type="text" name="date_of_joining" class="form-control js-date"
                               value="{{ old('date_of_joining', optional($employee->date_of_joining)->format('Y-m-d')) }}"
                               placeholder="YYYY-MM-DD">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Designation</label>
                        <select name="designation_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($designations as $d)
                                <option value="{{ $d->id }}" {{ old('designation_id', $employee->designation_id)==$d->id?'selected':'' }}>
                                    {{ $d->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id)==$dept->id?'selected':'' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Employment Type</label>
                        <select name="employment_type" class="form-select" required>
                            <option value="Regular" {{ old('employment_type', $employee->employment_type)=='Regular'?'selected':'' }}>Regular</option>
                            <option value="Contract" {{ old('employment_type', $employee->employment_type)=='Contract'?'selected':'' }}>Contract</option>
                            <option value="Apprentice" {{ old('employment_type', $employee->employment_type)=='Apprentice'?'selected':'' }}>Apprentice</option>
                            <option value="Temporary" {{ old('employment_type', $employee->employment_type)=='Temporary'?'selected':'' }}>Temporary</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Employment Category</label>
                        <select name="category" class="form-select">
                            <option value="">Select</option>
                            <option value="Skilled" {{ old('category', $employee->category)=='Skilled' ? 'selected' : '' }}>Skilled</option>
                            <option value="Semi-skilled" {{ old('category', $employee->category)=='Semi-skilled' ? 'selected' : '' }}>Semi-skilled</option>
                            <option value="Unskilled" {{ old('category', $employee->category)=='Unskilled' ? 'selected' : '' }}>Unskilled</option>
                            <option value="Trainee" {{ old('category', $employee->category)=='Trainee' ? 'selected' : '' }}>Trainee</option>
                            <option value="Other" {{ old('category', $employee->category)=='Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Salary <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="salary"
                               class="form-control @error('salary') is-invalid @enderror"
                               value="{{ old('salary', $employee->salary) }}" required>
                        @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ================= STATUTORY & IDENTITY ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Statutory & Identity</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Aadhaar Number</label>
                        <input type="text" name="aadhaar" class="form-control" value="{{ old('aadhaar', $employee->aadhaar) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">PAN Number</label>
                        <input type="text" name="pan" class="form-control" value="{{ old('pan', $employee->pan) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">UAN Number</label>
                        <input type="text" name="uan" class="form-control" value="{{ old('uan', $employee->uan) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">ESIC IP Number</label>
                        <input type="text" name="esic_ip" class="form-control" value="{{ old('esic_ip', $employee->esic_ip) }}">
                    </div>

                    {{-- ================= BANK DETAILS ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Bank Details</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Account Holder Name</label>
                        <input type="text" name="bank[account_holder_name]" class="form-control"
                               value="{{ old('bank.account_holder_name', optional($bank)->account_holder_name) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Bank Account Number</label>
                        <input type="text" name="bank[account_number]" class="form-control"
                               value="{{ old('bank.account_number') }}">
                        @if($bank && $bank->account_last4)
                            <small class="text-muted">Saved: ****{{ $bank->account_last4 }}</small>
                        @endif
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Bank Name</label>
                        <input type="text" name="bank[bank_name]" class="form-control"
                               value="{{ old('bank.bank_name', optional($bank)->bank_name) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Branch</label>
                        <input type="text" name="bank[branch]" class="form-control"
                               value="{{ old('bank.branch', optional($bank)->branch) }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">IFSC Code</label>
                        <input type="text" name="bank[ifsc]" class="form-control"
                               value="{{ old('bank.ifsc', optional($bank)->ifsc) }}">
                    </div>

                    <!-- <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Bank Proof</label>
                        @if($bankProof)
                            <div><a target="_blank" href="{{ asset('storage/'.$bankProof->file_path) }}">View current</a></div>
                        @endif
                        <input type="file" name="bank_proof" class="form-control" accept="image/*,.pdf">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Signature / Thumb</label>
                        @if($signature)
                            <div><a target="_blank" href="{{ asset('storage/'.$signature->file_path) }}">View current</a></div>
                        @endif
                        <input type="file" name="signature" class="form-control" accept="image/*,.pdf">
                    </div> -->

                    {{-- ================= ADDRESS & EXIT ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Address </h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-dark">Present Address</label>
                        <textarea name="present_address" class="form-control" rows="3">{{ old('present_address', $employee->present_address) }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-dark">Permanent Address</label>
                        <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-dark">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $employee->remarks) }}</textarea>
                    </div>

                    <!-- <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Date of Exit</label>
                        <input type="text" name="date_of_exit" class="form-control js-date"
                               value="{{ old('date_of_exit', optional($employee->date_of_exit)->format('Y-m-d')) }}"
                               placeholder="YYYY-MM-DD">
                    </div>

                    <div class="col-md-9 mb-3">
                        <label class="form-label fw-bold text-dark">Reason for Exit</label>
                        <input type="text" name="reason_for_exit" class="form-control"
                               value="{{ old('reason_for_exit', $employee->reason_for_exit) }}">
                    </div> -->

                    <!-- {{-- Aadhaar uploads --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Uploads</h6>
                        <hr class="mt-2">
                    </div> -->

                    <!-- <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Aadhaar Front</label>
                        @if($aadhaarFront)
                            <div><a target="_blank" href="{{ asset('storage/'.$aadhaarFront->file_path) }}">View current</a></div>
                        @endif
                        <input type="file" name="aadhaar_front" class="form-control" accept="image/*,.pdf">
                    </div> -->

                    <!-- <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-dark">Aadhaar Back</label>
                        @if($aadhaarBack)
                            <div><a target="_blank" href="{{ asset('storage/'.$aadhaarBack->file_path) }}">View current</a></div>
                        @endif
                        <input type="file" name="aadhaar_back" class="form-control" accept="image/*,.pdf">
                    </div> -->

                    {{-- Submit --}}
                    <div class="col-12 d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-success waves-effect waves-light">
                            <i class="fi fi-rr-check me-1"></i> Update
                        </button>
                        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
