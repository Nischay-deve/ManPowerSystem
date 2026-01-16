@extends('layouts.app')

@section('title', 'Edit Employee')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        if (window.flatpickr) {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d"
            });
        }

        const tabButtons = Array.from(document.querySelectorAll('[data-step-btn]'));
        const prevBtn = document.getElementById('btnPrev');
        const nextBtn = document.getElementById('btnNext');
        const submitBtn = document.getElementById('btnSubmit');

        function getActiveIndex() {
            return tabButtons.findIndex(btn => btn.classList.contains('active'));
        }

        function activateStep(index) {
            if (index < 0 || index >= tabButtons.length) return;
            const tab = new bootstrap.Tab(tabButtons[index]);
            tab.show();

            prevBtn.classList.toggle('d-none', index === 0);
            nextBtn.classList.toggle('d-none', index === tabButtons.length - 1);
            submitBtn.classList.toggle('d-none', index !== tabButtons.length - 1);
        }

        prevBtn.addEventListener('click', e => {
            e.preventDefault();
            activateStep(getActiveIndex() - 1);
        });
        nextBtn.addEventListener('click', e => {
            e.preventDefault();
            activateStep(getActiveIndex() + 1);
        });

        activateStep(getActiveIndex());
    });
</script>
@endpush

@section('content')

@php
// pick active docs by slot
$docs = $employee->documents->where('is_active', 1);

$photo = $docs->firstWhere('remarks', 'Profile photo');
$aadhaarFront = $docs->firstWhere('remarks', 'Aadhaar front side');
$aadhaarBack = $docs->firstWhere('remarks', 'Aadhaar back side');
$bankProof = $docs->firstWhere('remarks', 'Bank proof');
$signature = $docs->firstWhere('remarks', 'Specimen signature / Thumb impression');

$photoUrl = $photo ? asset('storage/'.$photo->file_path) : asset('assets/images/avatar/avatar-large3.jpg');
@endphp

<div class="container-fluid">

    <div class="app-page-head mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('employees.index') }}">Employees</a></li>
                <li class="breadcrumb-item active">Edit Employee</li>
            </ol>
        </nav>
        <h1 class="fw-bold mb-1">Edit Employee</h1>
        <p class="text-muted mb-0">Update employee details step by step</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fi fi-rr-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fi fi-rr-cross-circle me-1"></i> {{ $errors->first() }}
    </div>
    @endif

    <form id="employeeForm" method="POST" action="{{ route('employees.update', $employee->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow-sm">
            <div class="card-body p-4">

                <ul class="nav nav-tabs mb-4" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" data-step-btn data-bs-toggle="tab" data-bs-target="#step1">
                            Personal
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" data-step-btn data-bs-toggle="tab" data-bs-target="#step2">
                            Job
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" data-step-btn data-bs-toggle="tab" data-bs-target="#step3">
                            Statutory + Bank
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" data-step-btn data-bs-toggle="tab" data-bs-target="#step4">
                            Address + Exit + Uploads
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- STEP 1 --}}
                    <div class="tab-pane fade show active" id="step1">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Employee Code</label>
                                <input class="form-control" value="{{ $employee->employee_code }}" disabled>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">First Name *</label>
                                <input name="first_name" class="form-control" value="{{ old('first_name', $employee->first_name) }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Surname</label>
                                <input name="surname" class="form-control" value="{{ old('surname', $employee->surname) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender', $employee->gender)=='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ old('gender', $employee->gender)=='Female'?'selected':'' }}>Female</option>
                                    <option value="Other" {{ old('gender', $employee->gender)=='Other'?'selected':'' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Father / Spouse Name</label>
                                <input name="father_or_spouse_name" class="form-control" value="{{ old('father_or_spouse_name', $employee->father_or_spouse_name) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Date of Birth</label>
                                <input name="date_of_birth" class="form-control flatpickr-date" value="{{ old('date_of_birth', optional($employee->date_of_birth)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Nationality</label>
                                <input name="nationality" class="form-control" value="{{ old('nationality', $employee->nationality) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Education Level</label>
                                <input name="education_level" class="form-control" value="{{ old('education_level', $employee->education_level) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Current Photo</label><br>
                                <img src="{{ $photoUrl }}" class="rounded border" style="height:70px;width:70px;object-fit:cover;">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Replace Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div class="tab-pane fade" id="step2">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Joining</label>
                                <input name="date_of_joining" class="form-control flatpickr-date" value="{{ old('date_of_joining', optional($employee->date_of_joining)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id', $employee->department_id)==$dept->id?'selected':'' }}>
                                        {{ $dept->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Designation</label>
                                <select name="designation_id" class="form-select">
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $d)
                                    <option value="{{ $d->id }}" {{ old('designation_id', $employee->designation_id)==$d->id?'selected':'' }}>
                                        {{ $d->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category</label>
                                <input name="category" class="form-control" value="{{ old('category', $employee->category) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Address Type</label>
                                <select name="address_type" class="form-select">
                                    <option value="">Select</option>
                                    <option value="HS" {{ old('address_type',$employee->address_type)=='HS'?'selected':'' }}>HS</option>
                                    <option value="S" {{ old('address_type',$employee->address_type)=='S'?'selected':'' }}>S</option>
                                    <option value="SS" {{ old('address_type',$employee->address_type)=='SS'?'selected':'' }}>SS</option>
                                    <option value="US" {{ old('address_type',$employee->address_type)=='US'?'selected':'' }}>US</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Employment Type *</label>
                                <select name="employment_type" class="form-select" required>
                                    <option value="Regular" {{ old('employment_type',$employee->employment_type)=='Regular'?'selected':'' }}>Regular</option>
                                    <option value="Contract" {{ old('employment_type',$employee->employment_type)=='Contract'?'selected':'' }}>Contract</option>
                                    <option value="Apprentice" {{ old('employment_type',$employee->employment_type)=='Apprentice'?'selected':'' }}>Apprentice</option>
                                    <option value="Temporary" {{ old('employment_type',$employee->employment_type)=='Temporary'?'selected':'' }}>Temporary</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Salary *</label>
                                <input name="salary" type="number" step="0.01" class="form-control" value="{{ old('salary', $employee->salary) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Service Book No</label>
                                <input name="service_book_no" class="form-control" value="{{ old('service_book_no', $employee->service_book_no) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mark of Identification</label>
                                <input name="mark_of_identification" class="form-control" value="{{ old('mark_of_identification', $employee->mark_of_identification) }}">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div class="tab-pane fade" id="step3">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mobile</label>
                                <input name="mobile" class="form-control" value="{{ old('mobile', $employee->mobile) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">UAN</label>
                                <input name="uan" class="form-control" value="{{ old('uan', $employee->uan) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">PAN</label>
                                <input name="pan" class="form-control" value="{{ old('pan', $employee->pan) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">ESIC IP</label>
                                <input name="esic_ip" class="form-control" value="{{ old('esic_ip', $employee->esic_ip) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">LWF</label>
                                <input name="lwf" class="form-control" value="{{ old('lwf', $employee->lwf) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Aadhaar</label>
                                <input name="aadhaar" class="form-control" value="{{ old('aadhaar', $employee->aadhaar) }}">
                            </div>

                            <hr class="my-2">

                            @php $bank = $employee->primaryBankAccount; @endphp

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank A/C No.</label>
                                <input name="bank[account_number]" class="form-control" value="{{ old('bank.account_number') }}">
                                @if($bank && $bank->account_last4)
                                <small class="text-muted">Saved: ****{{ $bank->account_last4 }}</small>
                                @endif
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Account Holder Name</label>
                                <input name="bank[account_holder_name]" class="form-control" value="{{ old('bank.account_holder_name', optional($bank)->account_holder_name) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank Name</label>
                                <input name="bank[bank_name]" class="form-control" value="{{ old('bank.bank_name', optional($bank)->bank_name) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Branch</label>
                                <input name="bank[branch]" class="form-control" value="{{ old('bank.branch', optional($bank)->branch) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">IFSC</label>
                                <input name="bank[ifsc]" class="form-control" value="{{ old('bank.ifsc', optional($bank)->ifsc) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank Proof</label>
                                @if($bankProof)
                                <div><a target="_blank" href="{{ asset('storage/'.$bankProof->file_path) }}">View current</a></div>
                                @endif
                                <input type="file" name="bank_proof" class="form-control" accept="image/*,.pdf">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Specimen Signature / Thumb</label>
                                @if($signature)
                                <div><a target="_blank" href="{{ asset('storage/'.$signature->file_path) }}">View current</a></div>
                                @endif
                                <input type="file" name="signature" class="form-control" accept="image/*,.pdf">
                            </div>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div class="tab-pane fade" id="step4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_address" class="form-control" rows="3">{{ old('present_address', $employee->present_address) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Exit</label>
                                <input name="date_of_exit" class="form-control flatpickr-date" value="{{ old('date_of_exit', optional($employee->date_of_exit)->format('Y-m-d')) }}">
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="form-label">Reason for Exit</label>
                                <input name="reason_for_exit" class="form-control" value="{{ old('reason_for_exit', $employee->reason_for_exit) }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $employee->remarks) }}</textarea>
                            </div>

                            <hr class="my-2">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Aadhaar Front</label>
                                @if($aadhaarFront)
                                <div><a target="_blank" href="{{ asset('storage/'.$aadhaarFront->file_path) }}">View current</a></div>
                                @endif
                                <input type="file" name="aadhaar_front" class="form-control" accept="image/*,.pdf">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Aadhaar Back</label>
                                @if($aadhaarBack)
                                <div><a target="_blank" href="{{ asset('storage/'.$aadhaarBack->file_path) }}">View current</a></div>
                                @endif
                                <input type="file" name="aadhaar_back" class="form-control" accept="image/*,.pdf">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Back</a>

                <div class="d-flex gap-2">
                    <button type="button" id="btnPrev" class="btn btn-outline-primary d-none">Previous</button>
                    <button type="button" id="btnNext" class="btn btn-primary">Next</button>
                    <button type="submit" id="btnSubmit" class="btn btn-success d-none">
                        <i class="fi fi-rr-check"></i> Update
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection