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
    flatpickr(".flatpickr-date", {
        dateFormat: "Y-m-d"
    });

    document.addEventListener("DOMContentLoaded", function() {

        // bootstrap tab instances
        const tabButtons = Array.from(document.querySelectorAll('[data-bs-toggle="tab"]'));
        const tabs = tabButtons.map(btn => new bootstrap.Tab(btn));

        let currentIndex = 0;

        function showTab(index) {
            currentIndex = index;
            tabs[currentIndex].show();

            // buttons visibility
            document.getElementById("prevBtn").classList.toggle("d-none", currentIndex === 0);
            document.getElementById("nextBtn").classList.toggle("d-none", currentIndex === tabs.length - 1);
            document.getElementById("submitBtn").classList.toggle("d-none", currentIndex !== tabs.length - 1);
        }

        window.goNext = function() {
            if (currentIndex < tabs.length - 1) showTab(currentIndex + 1);
        };

        window.goPrev = function() {
            if (currentIndex > 0) showTab(currentIndex - 1);
        };

        // initial state
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
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-body">

                {{-- STEP TABS --}}
                <ul class="nav nav-tabs mb-4" role="tablist">

                    <li class="nav-item" role="presentation">
                        {{-- ✅ type="button" prevents auto submit --}}
                        <button type="button" class="nav-link active"
                            data-bs-toggle="tab" data-bs-target="#step1"
                            role="tab">
                            Personal
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#step2"
                            role="tab">
                            Job
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#step3"
                            role="tab">
                            Statutory
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link"
                            data-bs-toggle="tab" data-bs-target="#step4"
                            role="tab">
                            Address
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    {{-- STEP 1 : PERSONAL --}}
                    <div class="tab-pane fade show active" id="step1" role="tabpanel">
                        <div class="row">

                            {{-- ✅ employee_code disabled --}}
                            <div class="col-md-4 mb-3">
                                <label>Employee Code</label>
                                <input class="form-control" value="Auto Generated" disabled>
                                {{-- if you want it posted too (optional): --}}
                                {{-- <input type="hidden" name="employee_code" value=""> --}}
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>First Name *</label>
                                <input name="first_name" class="form-control" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Surname</label>
                                <input name="surname" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Father / Spouse Name</label>
                                <input name="father_or_spouse_name" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Date of Birth</label>
                                <input name="date_of_birth" class="form-control flatpickr-date">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Nationality</label>
                                <input name="nationality" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Education Level</label>
                                <input name="education_level" class="form-control">
                            </div>

                            {{-- ✅ PHOTO COLUMN ADDED --}}
                            <div class="col-md-4 mb-3">
                                <label>Photo</label>
                                <input type="file" name="photo" class="form-control">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 2 : JOB --}}
                    <div class="tab-pane fade" id="step2" role="tabpanel">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label>Date of Joining</label>
                                <input name="date_of_joining" class="form-control flatpickr-date">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Department ID</label>
                                <input name="department_id" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Designation ID</label>
                                <input name="designation_id" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Employment Type</label>
                                <select name="employment_type" class="form-select">
                                    <option>Regular</option>
                                    <option>Contract</option>
                                    <option>Apprentice</option>
                                    <option>Temporary</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Category</label>
                                <input name="category" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Salary</label>
                                <input name="salary" type="number" step="0.01" class="form-control">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 3 : STATUTORY --}}
                    <div class="tab-pane fade" id="step3" role="tabpanel">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label>Mobile</label>
                                <input name="mobile" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>UAN</label>
                                <input name="uan" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>PAN</label>
                                <input name="pan" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>ESIC IP</label>
                                <input name="esic_ip" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>LWF</label>
                                <input name="lwf" class="form-control">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label>Aadhaar</label>
                                <input name="aadhaar" class="form-control">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 4 : ADDRESS --}}
                    <div class="tab-pane fade" id="step4" role="tabpanel">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Present Address</label>
                                <textarea name="present_address" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Permanent Address</label>
                                <textarea name="permanent_address" class="form-control"></textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Mark of Identification</label>
                                <input name="mark_of_identification" class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control"></textarea>
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