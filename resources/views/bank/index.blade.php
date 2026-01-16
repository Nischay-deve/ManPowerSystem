@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
    <div class="clearfix">
        <h1 class="app-page-title">
            Bank Accounts
        </h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bank Accounts</li>
            </ol>
        </nav>
    </div>

    <a href="{{ route('bank.create') }}" class="btn btn-primary waves-effect waves-light">
        <i class="fi fi-rr-plus me-1"></i> Add Bank Account
    </a>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form class="row g-2 align-items-center" method="GET" action="{{ route('bank.index') }}">
            <div class="col-lg-5">
                <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                    placeholder="Search employee / bank / IFSC / last4">
            </div>

            <div class="col-lg-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="unverified" {{ ($status ?? '')==='unverified'?'selected':'' }}>Unverified</option>
                    <option value="verified" {{ ($status ?? '')==='verified'?'selected':'' }}>Verified</option>
                    <option value="rejected" {{ ($status ?? '')==='rejected'?'selected':'' }}>Rejected</option>
                </select>
            </div>

            <div class="col-lg-2">
                <select name="primary" class="form-select">
                    <option value="">All</option>
                    <option value="1" {{ (string)($primary ?? '')==='1'?'selected':'' }}>Primary</option>
                    <option value="0" {{ (string)($primary ?? '')==='0'?'selected':'' }}>Not Primary</option>
                </select>
            </div>

            <div class="col-lg-2 d-grid">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fi fi-rr-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Bank</th>
                    <th>IFSC</th>
                    <th>Account</th>
                    <th>Primary</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($accounts as $acc)
                @php
                $emp = $acc->employee;
                $empName = $emp ? trim(($emp->first_name ?? '').' '.($emp->surname ?? '')) : '—';
                $code = $emp->employee_code ?? '—';
                $statusBadge = match($acc->verification_status) {
                'verified' => 'bg-success-subtle text-success',
                'rejected' => 'bg-danger-subtle text-danger',
                default => 'bg-warning-subtle text-warning',
                };
                @endphp
                <tr>
                    <td>
                        <div class="fw-bold">{{ $empName }}</div>
                        <div class="text-muted text-sm">{{ $code }}</div>
                    </td>
                    <td>{{ $acc->bank_name ?: '—' }}</td>
                    <td>{{ $acc->ifsc ?: '—' }}</td>
                    <td>{{ $acc->account_last4 ? ('****'.$acc->account_last4) : '—' }}</td>
                    <td>
                        @if($acc->is_primary)
                        <span class="badge bg-primary-subtle text-primary">Yes</span>
                        @else
                        <span class="badge bg-light text-body">No</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $statusBadge }}">{{ $acc->verification_status ?: 'unverified' }}</span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('bank.edit', $acc->id) }}" class="btn btn-sm btn-outline-primary">
                            Edit
                        </a>

                        <form class="d-inline" method="POST" action="{{ route('bank.destroy', $acc->id) }}"
                            onsubmit="return confirm('Delete this bank account?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="alert alert-warning mb-0">No bank accounts found.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $accounts->links() }}
        </div>
    </div>
</div>

@endsection