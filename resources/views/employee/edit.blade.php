@extends('layouts.app')

@section('title', 'Edit Employee')

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
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // flatpickr
        if (window.flatpickr) {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d"
            });
        }

        // Tab wizard (Next/Prev)
        const tabButtons = Array.from(document.querySelectorAll('[data-step-btn]'));
        const prevBtn = document.getElementById('btnPrev');
        const nextBtn = document.getElementById('btnNext');
        const submitBtn = document.getElementById('btnSubmit');

        function getActiveIndex() {
            return tabButtons.findIndex(btn => btn.classList.contains('active'));
        }

        function activateStep(index) {
            if (index < 0 || index >= tabButtons.length) return;

            const trigger = tabButtons[index];
            const tab = new bootstrap.Tab(trigger);
            tab.show();

            // Toggle footer buttons
            if (index === 0) {
                prevBtn.classList.add('d-none');
            } else {
                prevBtn.classList.remove('d-none');
            }

            if (index === tabButtons.length - 1) {
                nextBtn.classList.add('d-none');
                submitBtn.classList.remove('d-none');
            } else {
                nextBtn.classList.remove('d-none');
                submitBtn.classList.add('d-none');
            }
        }

        prevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            activateStep(getActiveIndex() - 1);
        });

        nextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            activateStep(getActiveIndex() + 1);
        });

        // Prevent accidental submit on Enter (common reason for "auto save")
        const form = document.getElementById('employeeForm');
        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
            }
        });

        // Init state
        activateStep(getActiveIndex());

        // Photo preview
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');
        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', function() {
                const file = this.files?.[0];
                if (!file) return;
                const url = URL.createObjectURL(file);
                photoPreview.src = url;
            });
        }
    });
</script>
@endpush

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
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

    {{-- SUCCESS --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fi fi-rr-check me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ERRORS --}}
    @if($errors->any())
    <div class="alert alert-danger">
        <i class="fi fi-rr-cross-circle me-1"></i> {{ $errors->first() }}
    </div>
    @endif

    <form id="employeeForm"
        method="POST"
        action="{{ route('employees.update', $employee->id) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow-sm">
            <div class="card-body p-4">

                {{-- STEP TABS --}}
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
                            Statutory
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" data-step-btn data-bs-toggle="tab" data-bs-target="#step4">
                            Address & Exit
                        </button>
                    </li>
                </ul>

                <div class="tab-content">

                    {{-- STEP 1 : PERSONAL --}}
                    <div class="tab-pane fade show active" id="step1">
                        <div class="row">

                            {{-- EMPLOYEE CODE (DISABLED) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Employee Code</label>
                                <input type="text" class="form-control" value="{{ $employee->employee_code }}" disabled>
                                {{-- If you want to submit it (not required), keep hidden --}}
                                <input type="hidden" name="employee_code" value="{{ $employee->employee_code }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">First Name *</label>
                                <input name="first_name" class="form-control"
                                    value="{{ old('first_name', $employee->first_name) }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Surname</label>
                                <input name="surname" class="form-control"
                                    value="{{ old('surname', $employee->surname) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Father / Spouse Name</label>
                                <input name="father_or_spouse_name" class="form-control"
                                    value="{{ old('father_or_spouse_name', $employee->father_or_spouse_name) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Date of Birth</label>
                                <input name="date_of_birth" class="form-control flatpickr-date"
                                    value="{{ old('date_of_birth', $employee->date_of_birth) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Nationality</label>
                                <input name="nationality" class="form-control"
                                    value="{{ old('nationality', $employee->nationality) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Education Level</label>
                                <input name="education_level" class="form-control"
                                    value="{{ old('education_level', $employee->education_level) }}">
                            </div>

                            {{-- PHOTO --}}
                            @php
                            $photoUrl = !empty($employee->photo)
                            ? asset('storage/'.$employee->photo)
                            : asset('assets/images/avatar/avatar-large3.jpg');
                            @endphp
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Profile Photo</label>
                                <input id="photoInput" type="file" name="photo" class="form-control" accept="image/*">
                                <div class="mt-2">
                                    <img id="photoPreview" src="{{ $photoUrl }}" alt="photo"
                                        style="width: 72px; height: 72px; object-fit: cover; border-radius: 12px;">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- STEP 2 : JOB --}}
                    <div class="tab-pane fade" id="step2">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Date of Joining</label>
                                <input name="date_of_joining" class="form-control flatpickr-date"
                                    value="{{ old('date_of_joining', $employee->date_of_joining) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Department ID</label>
                                <input name="department_id" class="form-control"
                                    value="{{ old('department_id', $employee->department_id) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Designation ID</label>
                                <input name="designation_id" class="form-control"
                                    value="{{ old('designation_id', $employee->designation_id) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Employment Type *</label>
                                <select name="employment_type" class="form-select" required>
                                    @foreach(['Regular','Contract','Apprentice','Temporary'] as $type)
                                    <option value="{{ $type }}"
                                        {{ old('employment_type', $employee->employment_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Category</label>
                                <input name="category" class="form-control"
                                    value="{{ old('category', $employee->category) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Salary *</label>
                                <input name="salary" type="number" step="0.01" class="form-control"
                                    value="{{ old('salary', $employee->salary) }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Service Book No</label>
                                <input name="service_book_no" class="form-control"
                                    value="{{ old('service_book_no', $employee->service_book_no) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Address Type</label>
                                <input name="address_type" class="form-control"
                                    value="{{ old('address_type', $employee->address_type) }}">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 3 : STATUTORY --}}
                    <div class="tab-pane fade" id="step3">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Mobile</label>
                                <input name="mobile" class="form-control"
                                    value="{{ old('mobile', $employee->mobile) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">UAN</label>
                                <input name="uan" class="form-control"
                                    value="{{ old('uan', $employee->uan) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">PAN</label>
                                <input name="pan" class="form-control"
                                    value="{{ old('pan', $employee->pan) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">ESIC IP</label>
                                <input name="esic_ip" class="form-control"
                                    value="{{ old('esic_ip', $employee->esic_ip) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">LWF</label>
                                <input name="lwf" class="form-control"
                                    value="{{ old('lwf', $employee->lwf) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-medium">Aadhaar</label>
                                <input name="aadhaar" class="form-control"
                                    value="{{ old('aadhaar', $employee->aadhaar) }}">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 4 : ADDRESS & EXIT --}}
                    <div class="tab-pane fade" id="step4">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Present Address</label>
                                <textarea name="present_address" class="form-control" rows="3">{{ old('present_address', $employee->present_address) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address', $employee->permanent_address) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Mark of Identification</label>
                                <input name="mark_of_identification" class="form-control"
                                    value="{{ old('mark_of_identification', $employee->mark_of_identification) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks', $employee->remarks) }}</textarea>
                            </div>

                            <hr class="my-3">

                            {{-- EXIT FLOW FIELDS --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Date of Exit</label>
                                <input name="date_of_exit" class="form-control flatpickr-date"
                                    value="{{ old('date_of_exit', $employee->date_of_exit) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Reason for Exit</label>
                                <input name="reason_for_exit" class="form-control"
                                    value="{{ old('reason_for_exit', $employee->reason_for_exit) }}">
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER (NEXT/PREV/SUBMIT) --}}
            <div class="card-footer d-flex justify-content-between px-4 py-3">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>

                <div class="d-flex gap-2">
                    <button id="btnPrev" type="button" class="btn btn-outline-primary d-none">
                        <i class="fi fi-rr-angle-left me-1"></i> Previous
                    </button>

                    <button id="btnNext" type="button" class="btn btn-primary">
                        Next <i class="fi fi-rr-angle-right ms-1"></i>
                    </button>

                    <button id="btnSubmit" type="submit" class="btn btn-success d-none">
                        <i class="fi fi-rr-check me-1"></i> Update Employee
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection