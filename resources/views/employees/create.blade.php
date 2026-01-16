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

        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d"
        });

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

        window.goNext = function() {
            if (currentIndex < tabs.length - 1) showTab(currentIndex + 1);
        };

        window.goPrev = function() {
            if (currentIndex > 0) showTab(currentIndex - 1);
        };

        const form = document.getElementById('employeeForm');
        form.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && currentIndex !== tabs.length - 1) {
                e.preventDefault();
                goNext();
            }
        });

        showTab(0);
    });
</script>
@endpush

@section('content')

<div class="container-fluid">

    <div class="app-page-head mb-4">
        <h1 class="fw-bold">Add Employee</h1>
        <p class="text-muted">Fill employee details step by step</p>
    </div>

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

                <ul class="nav nav-tabs mb-4" role="tablist">

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#step1" role="tab">
                            Personal
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#step2" role="tab">
                            Job
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#step3" role="tab">
                            Statutory & Bank
                        </button>
                    </li>

                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#step4" role="tab">
                            Address & Other
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    {{-- STEP 1 : PERSONAL --}}
                    <div class="tab-pane fade show active" id="step1" role="tabpanel">
                        <div class="row">

                            {{-- Employee Code (Auto) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Employee Code</label>
                                <input class="form-control" value="Auto Generated (EMP-0001...)" disabled>
                                <small class="text-muted">System will create employee code automatically.</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Surname</label>
                                <input name="surname" class="form-control" value="{{ old('surname') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('gender')=='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ old('gender')=='Female'?'selected':'' }}>Female</option>
                                    <option value="Other" {{ old('gender')=='Other'?'selected':'' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Father's / Spouse Name</label>
                                <input name="father_or_spouse_name" class="form-control" value="{{ old('father_or_spouse_name') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input name="date_of_birth" class="form-control flatpickr-date" value="{{ old('date_of_birth') }}" placeholder="YYYY-MM-DD">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nationality</label>
                                <input name="nationality" class="form-control" value="{{ old('nationality') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Education Level</label>
                                <input name="education_level" class="form-control" value="{{ old('education_level') }}">
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

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Joining</label>
                                <input name="date_of_joining" class="form-control flatpickr-date" value="{{ old('date_of_joining') }}" placeholder="YYYY-MM-DD">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Department</label>
                                <select name="department_id" class="form-select">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department_id')==$dept->id?'selected':'' }}>
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
                                    <option value="{{ $d->id }}" {{ old('designation_id')==$d->id?'selected':'' }}>
                                        {{ $d->title }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category</label>
                                <input name="category" class="form-control" value="{{ old('category') }}" placeholder="Skilled / Unskilled / Semi-skilled">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Address Type (HS / S / SS / US)</label>
                                <select name="address_type" class="form-select">
                                    <option value="">Select</option>
                                    <option value="HS" {{ old('address_type')=='HS'?'selected':'' }}>HS</option>
                                    <option value="S" {{ old('address_type')=='S'?'selected':'' }}>S</option>
                                    <option value="SS" {{ old('address_type')=='SS'?'selected':'' }}>SS</option>
                                    <option value="US" {{ old('address_type')=='US'?'selected':'' }}>US</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Type of Employment <span class="text-danger">*</span></label>
                                <select name="employment_type" class="form-select" required>
                                    <option value="Regular" {{ old('employment_type','Regular')=='Regular'?'selected':'' }}>Regular</option>
                                    <option value="Contract" {{ old('employment_type')=='Contract'?'selected':'' }}>Contract</option>
                                    <option value="Apprentice" {{ old('employment_type')=='Apprentice'?'selected':'' }}>Apprentice</option>
                                    <option value="Temporary" {{ old('employment_type')=='Temporary'?'selected':'' }}>Temporary</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Salary <span class="text-danger">*</span></label>
                                <input name="salary" type="number" step="0.01" class="form-control" value="{{ old('salary') }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Service Book No.</label>
                                <input name="service_book_no" class="form-control" value="{{ old('service_book_no') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mark of Identification</label>
                                <input name="mark_of_identification" class="form-control" value="{{ old('mark_of_identification') }}">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 3 : STATUTORY & BANK --}}
                    <div class="tab-pane fade" id="step3" role="tabpanel">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Mobile</label>
                                <input name="mobile" class="form-control" value="{{ old('mobile') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">UAN</label>
                                <input name="uan" class="form-control" value="{{ old('uan') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">PAN</label>
                                <input name="pan" class="form-control" value="{{ old('pan') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">ESIC IP</label>
                                <input name="esic_ip" class="form-control" value="{{ old('esic_ip') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">LWF</label>
                                <input name="lwf" class="form-control" value="{{ old('lwf') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Aadhaar</label>
                                <input name="aadhaar" class="form-control" value="{{ old('aadhaar') }}">
                            </div>

                            <hr class="my-2">

                            {{-- Bank fields (NESTED) --}}
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank A/C No.</label>
                                <input name="bank[account_number]" class="form-control" value="{{ old('bank.account_number') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Account Holder Name</label>
                                <input name="bank[account_holder_name]" class="form-control" value="{{ old('bank.account_holder_name') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank Name</label>
                                <input name="bank[bank_name]" class="form-control" value="{{ old('bank.bank_name') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Branch</label>
                                <input name="bank[branch]" class="form-control" value="{{ old('bank.branch') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">IFSC</label>
                                <input name="bank[ifsc]" class="form-control" value="{{ old('bank.ifsc') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Bank Proof (Passbook / Cheque)</label>
                                <input type="file" name="bank_proof" class="form-control" accept="image/*,.pdf">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Specimen Signature / Thumb</label>
                                <input type="file" name="signature" class="form-control" accept="image/*,.pdf">
                            </div>

                        </div>
                    </div>

                    {{-- STEP 4 : ADDRESS & OTHER --}}
                    <div class="tab-pane fade" id="step4" role="tabpanel">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Present Address</label>
                                <textarea name="present_address" class="form-control" rows="3">{{ old('present_address') }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address') }}</textarea>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Date of Exit</label>
                                <input name="date_of_exit" class="form-control flatpickr-date" value="{{ old('date_of_exit') }}" placeholder="YYYY-MM-DD">
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="form-label">Reason for Exit</label>
                                <input name="reason_for_exit" class="form-control" value="{{ old('reason_for_exit') }}">
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3">{{ old('remarks') }}</textarea>
                            </div>

                            <hr class="my-2">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Aadhaar Front</label>
                                <input type="file" name="aadhaar_front" class="form-control" accept="image/*,.pdf">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Aadhaar Back</label>
                                <input type="file" name="aadhaar_back" class="form-control" accept="image/*,.pdf">
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary">Cancel</a>

                <div class="d-flex gap-2">
                    <button type="button" id="prevBtn" class="btn btn-outline-primary d-none" onclick="goPrev()">Previous</button>
                    <button type="button" id="nextBtn" class="btn btn-primary" onclick="goNext()">Next</button>
                    <button type="submit" id="submitBtn" class="btn btn-success d-none">
                        <i class="fi fi-rr-check"></i> Submit
                    </button>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection