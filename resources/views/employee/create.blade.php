@extends('layouts.app')

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

        // Flatpickr
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d"
        });

        // Tabs
        const tabButtons = Array.from(document.querySelectorAll('[data-bs-toggle="tab"]'));
        const tabs = tabButtons.map(btn => new bootstrap.Tab(btn));
        let currentIndex = 0;

        function showTab(index) {
            currentIndex = index;
            tabs[currentIndex].show();

            document.getElementById("prevBtn").classList.toggle("d-none", currentIndex === 0);
            document.getElementById("nextBtn").classList.toggle("d-none", currentIndex === tabs.length - 1);
            document.getElementById("submitBtn").classList.toggle("d-none", currentIndex !== tabs.length - 1);
        }

        // Next / Prev
        window.goNext = function() {
            if (currentIndex < tabs.length - 1) showTab(currentIndex + 1);
        };

        window.goPrev = function() {
            if (currentIndex > 0) showTab(currentIndex - 1);
        };

        // ✅ Prevent "Enter" key from submitting early (except last step)
        const form = document.getElementById('employeeForm');
        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && currentIndex !== tabs.length - 1) {
                e.preventDefault();
                goNext();
            }
        });

        // ✅ Prevent clicking tabs directly (optional) - comment if you want free tab click
        tabButtons.forEach((btn, idx) => {
            btn.addEventListener('click', function(e) {
                // allow click but keep buttons correct
                setTimeout(() => showTab(idx), 0);
            });
        });

        // initial
        showTab(0);
    });
</script>
@endpush

@section('content')

<div class="container-fluid">

    {{-- PAGE HEADER --}}
    <div class="app-page-head mb-4">
        <h1 class="fw-bold">Add Employee</h1>
        <p class="text-muted">Fill employee details step by step</p>
    </div>

    {{-- ALERTS --}}
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

    <form id="employeeForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-body">

                {{-- STEP TABS --}}
                <ul class="nav nav-tabs mb-4" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active"
                            data-bs-toggle="tab" data-bs-target="#step1" role="tab">
                            Personal
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#step2" role="tab">
                            Job
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#step3" role="tab">
                            Statutory & Bank
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#step4" role="tab">
                            Address & Other
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    {{-- STEP 1 : PERSONAL --}}
                    <div class="tab-pane fade show active" id="step1" role="tabpanel">
                        <div class="row">

                            {{-- Employee Code (Manual, required) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Employee Code <span class="text-danger">*</span></label>
                                <input name="employee_code" class="form-control"
                                    value="{{ old('employee_code') }}" placeholder="EMP-0001" required>
                            </div>

                            {{-- Name --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input name="name" class="form-control"
                                    value="{{ old('name') }}" placeholder="Enter name" required>
                            </div>

                            {{-- Surname --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Surname</label>
                                <input name="surname" class="form-control"
                                    value="{{ old('surname') }}" placeholder="Enter surname">
                            </div>

                            {{-- Gender --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                                    <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
                                </select>
                            </div>

                            {{-- Father / Spouse Name --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Father's / Spouse name</label>
                                <input name="father_or_spouse_name" class="form-control"
                                    value="{{ old('father_or_spouse_name') }}" placeholder="Enter name">
                            </div>

                            {{-- DOB --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input name="date_of_birth" class="form-control flatpickr-date"
                                    value="{{ old('date_of_birth') }}" placeholder="YYYY-MM-DD">
                            </div>

                            {{-- Nationality --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nationality</label>
                                <input name="nationality" class="form-control"
                                    value="{{ old('nationality') }}" placeholder="Indian">
                            </div>

                            {{-- Education Level --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Education Level</label>
                                <input name="education_level" class="form-control"
                                    value="{{ old('education_level') }}" placeholder="Graduate">
                            </div>

                            {{-- Photo --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                                <small class="text-muted">jpg / png / webp (max 2MB)</small>
                            </div>

                        </div>
                    </div>

                    {{-- STEP 2 : JOB --}}
                    <div class="tab-pane fade" id="step2" role="tabpanel">
                        <div class="row">

                            {{-- Date of Joining --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Joining</label>
                                <input name="date_of_joining" class="form-control flatpickr-date"
                                    value="{{ old('date_of_joining') }}" placeholder="YYYY-MM-DD">
                            </div>

                            {{-- Designation (string) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Designation</label>
                                <select name="designation" class="form-select">
                                    <option value="">Select Designation</option>

                                    @foreach($designations as $d)
                                    <option value="{{ $d->id }}"
                                        {{ old('designation') == $d->title ? 'selected' : '' }}>
                                        {{ $d->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category --}}


                            {{-- Address Type (HS/S/SS/US) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category Address (HS / S / SS / US)</label>
                                <select name="category_address" class="form-select">
                                    <option value="">Select</option>
                                    <option value="HS" {{ old('address_type')=='HS'?'selected':'' }}>HS</option>
                                    <option value="S" {{ old('address_type')=='S'?'selected':'' }}>S</option>
                                    <option value="SS" {{ old('address_type')=='SS'?'selected':'' }}>SS</option>
                                    <option value="US" {{ old('address_type')=='US'?'selected':'' }}>US</option>
                                </select>
                            </div>

                            {{-- Employment Type --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Type of Employment <span class="text-danger">*</span></label>
                                <select name="employment_type" class="form-select" required>
                                    <option value="Regular" {{ old('employment_type')=='Regular'?'selected':'' }}>Regular</option>
                                    <option value="Contract" {{ old('employment_type')=='Contract'?'selected':'' }}>Contract</option>
                                    <option value="Apprentice" {{ old('employment_type')=='Apprentice'?'selected':'' }}>Apprentice</option>
                                    <option value="Temporary" {{ old('employment_type')=='Temporary'?'selected':'' }}>Temporary</option>
                                </select>
                            </div>

                            {{-- Salary --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">SALARY <span class="text-danger">*</span></label>
                                <input name="salary" type="number" step="0.01" class="form-control"
                                    value="{{ old('salary') }}" placeholder="0.00" required>
                            </div>

                            {{-- Service Book No --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Service Book No.</label>
                                <input name="service_book_no" class="form-control"
                                    value="{{ old('service_book_no') }}" placeholder="Service book number">
                            </div>

                            {{-- Mark of Identification --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mark of Identification</label>
                                <input name="mark_of_identification" class="form-control"
                                    value="{{ old('mark_of_identification') }}" placeholder="Identification mark">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 3 : STATUTORY & BANK --}}
                    <div class="tab-pane fade" id="step3" role="tabpanel">
                        <div class="row">

                            {{-- Mobile --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mobile</label>
                                <input name="mobile" class="form-control"
                                    value="{{ old('mobile') }}" placeholder="Enter mobile">
                            </div>

                            {{-- UAN --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">UAN</label>
                                <input name="uan" class="form-control"
                                    value="{{ old('uan') }}" placeholder="UAN">
                            </div>

                            {{-- PAN --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">PAN</label>
                                <input name="pan" class="form-control"
                                    value="{{ old('pan') }}" placeholder="PAN">
                            </div>

                            {{-- ESIC IP --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ESIC IP</label>
                                <input name="esic_ip" class="form-control"
                                    value="{{ old('esic_ip') }}" placeholder="ESIC IP">
                            </div>

                            {{-- LWF --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">LWF</label>
                                <input name="lwf" class="form-control"
                                    value="{{ old('lwf') }}" placeholder="LWF">
                            </div>

                            {{-- AADHAAR --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">AADHAAR</label>
                                <input name="aadhaar" class="form-control"
                                    value="{{ old('aadhaar') }}" placeholder="AADHAAR">
                            </div>

                            <hr class="my-2">

                            {{-- Bank A/C No. --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank A/C No.</label>
                                <input name="bank_account_no" class="form-control"
                                    value="{{ old('bank_account_no') }}" placeholder="Account number">
                            </div>

                            {{-- Bank --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank</label>
                                <input name="bank_name" class="form-control"
                                    value="{{ old('bank_name') }}" placeholder="Bank name">
                            </div>

                            {{-- Branch (IFSC) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Branch (IFSC)</label>
                                <input name="bank_ifsc" class="form-control"
                                    value="{{ old('bank_ifsc') }}" placeholder="IFSC code">
                            </div>

                            {{-- Specimen Signature / Thumb Impression --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Specimen Signature / Thumb Impression</label>
                                <input type="file" name="specimen_signature" class="form-control" accept="image/*">
                                <small class="text-muted">jpg / png / webp (max 2MB)</small>
                            </div>

                        </div>
                    </div>

                    {{-- STEP 4 : ADDRESS & OTHER --}}
                    <div class="tab-pane fade" id="step4" role="tabpanel">
                        <div class="row">

                            {{-- Present Address --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_address" class="form-control" rows="3"
                                    placeholder="Enter present address">{{ old('present_address') }}</textarea>
                            </div>

                            {{-- Permanent Address --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3"
                                    placeholder="Enter permanent address">{{ old('permanent_address') }}</textarea>
                            </div>

                            {{-- Date of Exit --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Exit.</label>
                                <input name="date_of_exit" class="form-control flatpickr-date"
                                    value="{{ old('date_of_exit') }}" placeholder="YYYY-MM-DD">
                            </div>

                            {{-- Reason for Exit --}}
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Reason for Exit.</label>
                                <input name="reason_for_exit" class="form-control"
                                    value="{{ old('reason_for_exit') }}" placeholder="Reason for exit">
                            </div>

                            {{-- Remarks --}}
                            <div class="col-12 mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3"
                                    placeholder="Remarks">{{ old('remarks') }}</textarea>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- FOOTER (Prev/Next + Submit only last) --}}
            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>

                <div class="d-flex gap-2">
                    <button type="button" id="prevBtn" class="btn btn-outline-primary d-none" onclick="goPrev()">
                        Previous
                    </button>

                    <button type="button" id="nextBtn" class="btn btn-primary" onclick="goNext()">
                        Next
                    </button>

                    <button type="submit" id="submitBtn" class="btn btn-success d-none">
                        <i class="fi fi-rr-check"></i> Submit
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>

@endsection