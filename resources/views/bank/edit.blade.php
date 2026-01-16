@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@section('content')

<div class="app-page-head mb-4">
    <h1 class="fw-bold">Edit Bank Account</h1>
</div>

@if($errors->any())
<div class="alert alert-danger">
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('bank.update', $bank->id) }}">
    @csrf
    @method('PUT')

    <div class="card">
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-select" required>
                        @foreach($employees as $e)
                        @php $label = ($e->employee_code ?? '') . ' - ' . trim(($e->first_name ?? '').' '.($e->surname ?? '')); @endphp
                        <option value="{{ $e->id }}" {{ (string)old('employee_id', $bank->employee_id)===(string)$e->id?'selected':'' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Account Number (Optional)</label>
                    <input name="account_number" class="form-control" value="{{ old('account_number') }}">
                    <small class="text-muted">Leave empty to keep saved account (****{{ $bank->account_last4 ?? '—' }})</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Account Holder Name</label>
                    <input name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $bank->account_holder_name) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Bank Name</label>
                    <input name="bank_name" class="form-control" value="{{ old('bank_name', $bank->bank_name) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Branch</label>
                    <input name="branch" class="form-control" value="{{ old('branch', $bank->branch) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">IFSC</label>
                    <input name="ifsc" class="form-control" value="{{ old('ifsc', $bank->ifsc) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Primary</label>
                    <select name="is_primary" class="form-select">
                        <option value="0" {{ (string)old('is_primary', $bank->is_primary)==='0'?'selected':'' }}>No</option>
                        <option value="1" {{ (string)old('is_primary', $bank->is_primary)==='1'?'selected':'' }}>Yes</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Verification Status</label>
                    <select name="verification_status" class="form-select">
                        <option value="unverified" {{ old('verification_status', $bank->verification_status)==='unverified'?'selected':'' }}>Unverified</option>
                        <option value="verified" {{ old('verification_status', $bank->verification_status)==='verified'?'selected':'' }}>Verified</option>
                        <option value="rejected" {{ old('verification_status', $bank->verification_status)==='rejected'?'selected':'' }}>Rejected</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">Verification Notes</label>
                    <textarea name="verification_notes" class="form-control" rows="3">{{ old('verification_notes', $bank->verification_notes) }}</textarea>
                </div>

            </div>
        </div>

        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('bank.index') }}" class="btn btn-outline-secondary">Back</a>
            <button type="submit" class="btn btn-success">
                <i class="fi fi-rr-check me-1"></i> Update
            </button>
        </div>
    </div>
</form>

@endsection