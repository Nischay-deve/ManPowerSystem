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
        // ✅ Date dropdown / picker
        flatpickr(".js-date", {
            dateFormat: "Y-m-d",
            allowInput: true
        });
    });
</script>
<!-- end::GXON Page Scripts -->
@endpush

@section('content')

<div class="app-page-head">
    <h1 class="app-page-title">Add Workforce</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('employees.index') }}">Workforce</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Add Workforce</li>
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
            <div class="card-header">
                <h6 class="card-title">Basic Workforce Details</h6>
            </div>

            <div class="card-body">

                {{-- ✅ IMPORTANT: add action + method + enctype --}}
                <form class="row" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- ✅ Code --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Code <span class="text-danger">*</span></label>
                        <input type="text" name="employee_code"
                            class="form-control @error('employee_code') is-invalid @enderror"
                            value="{{ old('employee_code') }}" required>
                        @error('employee_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ✅ First Name --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">First Name <span class="text-danger">*</span></label>
                        <input type="text" name="first_name"
                            class="form-control @error('first_name') is-invalid @enderror"
                            value="{{ old('first_name') }}" required>
                        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ✅ Last Name (Surname) --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Last Name</label>
                        <input type="text" name="surname" class="form-control" value="{{ old('surname') }}">
                    </div>

                    {{-- ✅ Date of Birth (Date dropdown/picker) --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Date of Birth</label>
                        <input type="text" name="date_of_birth" class="form-control js-date"
                            value="{{ old('date_of_birth') }}" placeholder="YYYY-MM-DD">
                    </div>

                    {{-- ✅ Mobile --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Mobile Number</label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}">
                    </div>

                    {{-- ✅ Nationality --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Nationality</label>
                        <select name="nationality" class="form-select">
                            <option value="">Select</option>
                            <option value="Indian" {{ old('nationality','Indian')=='Indian' ? 'selected' : '' }}>Indian</option>
                            <option value="Non Indian" {{ old('nationality')=='Non Indian' ? 'selected' : '' }}>Non Indian</option>
                        </select>
                    </div>

                    {{-- ✅ Gender --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                            <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                            <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
                        </select>
                    </div>

                    {{-- ✅ Work Status (UI) -> mapped to is_active in controller --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Work Status</label>
                        <select name="work_status" class="form-select">
                            <option value="Active" {{ old('work_status','Active')=='Active'?'selected':'' }}>Active</option>
                            <option value="On Leave" {{ old('work_status')=='On Leave'?'selected':'' }}>On Leave</option>
                            <option value="Exited" {{ old('work_status')=='Exited'?'selected':'' }}>Exited</option>
                        </select>
                    </div>

                    {{-- ================= EMPLOYMENT DETAILS ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Employment Details</h6>
                        <hr class="mt-2">
                    </div>

                    {{-- ✅ Date of Joining (Date dropdown/picker) --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Date of Joining</label>
                        <input type="text" name="date_of_joining" class="form-control js-date"
                            value="{{ old('date_of_joining') }}" placeholder="YYYY-MM-DD">
                    </div>

                    {{-- ✅ Designation --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Designation</label>
                        <select name="designation_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($designations as $d)
                            <option value="{{ $d->id }}" {{ old('designation_id')==$d->id?'selected':'' }}>
                                {{ $d->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ✅ Department --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">Select</option>
                            @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id')==$dept->id?'selected':'' }}>
                                {{ $dept->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- ✅ Employment Type --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Employment Type</label>
                        <select name="employment_type" class="form-select" required>
                            <option value="Regular" {{ old('employment_type','Regular')=='Regular'?'selected':'' }}>Regular</option>
                            <option value="Contract" {{ old('employment_type')=='Contract'?'selected':'' }}>Contract</option>
                            <option value="Apprentice" {{ old('employment_type')=='Apprentice'?'selected':'' }}>Apprentice</option>
                            <option value="Temporary" {{ old('employment_type')=='Temporary'?'selected':'' }}>Temporary</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Employment Category</label>
                        <select name="category" class="form-select">
                            <option value="">Select</option>
                            <option value="Skilled" {{ old('category')=='Skilled' ? 'selected' : '' }}>Skilled</option>
                            <option value="Semi-skilled" {{ old('category')=='Semi-skilled' ? 'selected' : '' }}>Semi-skilled</option>
                            <option value="Unskilled" {{ old('category')=='Unskilled' ? 'selected' : '' }}>Unskilled</option>
                            <option value="Trainee" {{ old('category')=='Trainee' ? 'selected' : '' }}>Trainee</option>
                            <option value="Other" {{ old('category')=='Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    {{-- ✅ Salary --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Salary <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="salary"
                            class="form-control @error('salary') is-invalid @enderror"
                            value="{{ old('salary') }}" required>
                        @error('salary') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- ================= STATUTORY & IDENTITY ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Statutory & Identity</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Aadhaar Number</label>
                        <input type="text" name="aadhaar" class="form-control" value="{{ old('aadhaar') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">PAN Number</label>
                        <input type="text" name="pan" class="form-control" value="{{ old('pan') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">UAN Number</label>
                        <input type="text" name="uan" class="form-control" value="{{ old('uan') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">ESIC IP Number</label>
                        <input type="text" name="esic_ip" class="form-control" value="{{ old('esic_ip') }}">
                    </div>

                    {{-- ================= BANK DETAILS ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Bank Details</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Account Holder Name</label>
                        <input type="text" name="bank[account_holder_name]" class="form-control"
                            value="{{ old('bank.account_holder_name') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Bank Account Number</label>
                        <input type="text" name="bank[account_number]" class="form-control"
                            value="{{ old('bank.account_number') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Bank Name</label>
                        <input type="text" name="bank[bank_name]" class="form-control"
                            value="{{ old('bank.bank_name') }}">
                    </div>

                    {{-- ✅ Branch --}}
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">Branch</label>
                        <input type="text" name="bank[branch]" class="form-control"
                            value="{{ old('bank.branch') }}">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold text-dark">IFSC Code</label>
                        <input type="text" name="bank[ifsc]" class="form-control"
                            value="{{ old('bank.ifsc') }}">
                    </div>

                    {{-- ================= ADDRESS ================= --}}
                    <div class="col-12 mt-2">
                        <h6 class="mb-0">Address</h6>
                        <hr class="mt-2">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-dark">Present Address</label>
                        <textarea name="present_address" class="form-control" rows="3">{{ old('present_address') }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-dark">Permanent Address</label>
                        <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address') }}</textarea>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-dark">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection