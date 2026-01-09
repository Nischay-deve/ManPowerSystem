
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

<!-- begin::GXON CSS Stylesheet -->
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
<!-- end::GXON CSS Stylesheet -->
@endpush

@push('scripts')
<!-- begin::GXON Page Scripts -->
<script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/appSettings.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<!-- end::GXON Page Scripts -->

<script>
	document.addEventListener("DOMContentLoaded", function() {
		// DataTable init
		if (window.$ && $.fn.DataTable) {
			$('#dt_Departments').DataTable({
				pageLength: 10,
				lengthChange: true,
				ordering: true,
				searching: true,
				info: true
			});
		}

		// Reset form when modal closes
		const modalEl = document.getElementById('addDepartmentModal');
		if (modalEl) {
			modalEl.addEventListener('hidden.bs.modal', function() {
				const form = modalEl.querySelector('form');
				if (form) form.reset();
			});
		}
	});
</script>
@endpush

@section('content')

<div class="app-page-head d-flex flex-wrap gap-3 align-items-center justify-content-between">
	<div class="clearfix">
		<h1 class="app-page-title">Departments</h1>
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb mb-0">
				<li class="breadcrumb-item">
					<a href="{{ route('index') }}">Dashboard</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">Departments</li>
			</ol>
		</nav>
	</div>

	<!-- Modal Button -->
	<button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
		<i class="fi fi-rr-plus me-1"></i> Add Department
	</button>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="card overflow-hidden">
			<div class="card-header d-flex gap-3 flex-wrap align-items-center justify-content-between border-0 pb-0">
				<h6 class="card-title mb-0">Department List</h6>
				<div class="d-flex gap-3 flex-wrap align-items-center">
					<div class="text-muted small">Manage all departments</div>
					<button type="button" class="btn btn-sm btn-outline-light btn-shadow waves-effect">
						<i class="fi fi-rr-download me-1"></i> Download
					</button>
				</div>
			</div>

			<div class="card-body p-2">
				<table id="dt_Departments" class="table table-sm display table-row-rounded">
					<thead class="table-light">
						<tr>
							<th class="minw-200px">Department Name</th>
							<th class="minw-120px">Code</th>
							<th class="minw-120px">Status</th>
							<th class="minw-250px">Description</th>
							<th class="minw-140px text-end">Action</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xs rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-briefcase"></i>
									</div>
									<div class="ms-2">
										<h6 class="mb-0">Human Resources</h6>
										<small class="text-muted">Handles hiring & policies</small>
									</div>
								</div>
							</td>
							<td>HR</td>
							<td><span class="badge bg-success-subtle text-success">Active</span></td>
							<td>Recruitment, employee relations, training, payroll coordination.</td>
							<td class="text-end">
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-pencil"></i>
								</button>
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-trash"></i>
								</button>
							</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xs rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-code"></i>
									</div>
									<div class="ms-2">
										<h6 class="mb-0">Development</h6>
										<small class="text-muted">Builds product features</small>
									</div>
								</div>
							</td>
							<td>DEV</td>
							<td><span class="badge bg-success-subtle text-success">Active</span></td>
							<td>Engineering, QA, deployments, improvements, maintenance.</td>
							<td class="text-end">
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-pencil"></i>
								</button>
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-trash"></i>
								</button>
							</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xs rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-chart-line"></i>
									</div>
									<div class="ms-2">
										<h6 class="mb-0">Sales</h6>
										<small class="text-muted">Leads revenue generation</small>
									</div>
								</div>
							</td>
							<td>SAL</td>
							<td><span class="badge bg-danger-subtle text-danger">Inactive</span></td>
							<td>Customer acquisition, lead follow-ups, closing deals.</td>
							<td class="text-end">
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-pencil"></i>
								</button>
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-trash"></i>
								</button>
							</td>
						</tr>

						<tr>
							<td>
								<div class="d-flex align-items-center">
									<div class="avatar avatar-xs rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center">
										<i class="fi fi-rr-megaphone"></i>
									</div>
									<div class="ms-2">
										<h6 class="mb-0">Marketing</h6>
										<small class="text-muted">Brand & campaigns</small>
									</div>
								</div>
							</td>
							<td>MKT</td>
							<td><span class="badge bg-success-subtle text-success">Active</span></td>
							<td>Advertising, social media, promotions, content strategy.</td>
							<td class="text-end">
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-pencil"></i>
								</button>
								<button class="btn btn-sm btn-outline-light btn-shadow waves-effect">
									<i class="fi fi-rr-trash"></i>
								</button>
							</td>
						</tr>

					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header py-3">
				<h5 class="modal-title">Add Department</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				{{-- Change action to your real store route --}}
				<form method="POST" action="#">
					@csrf

					<div class="row g-3">
						<div class="col-md-6">
							<label class="form-label">Department Name <span class="text-danger">*</span></label>
							<input type="text" name="name" class="form-control" placeholder="Enter department name" required>
						</div>

						<div class="col-md-6">
							<label class="form-label">Department Code <span class="text-danger">*</span></label>
							<input type="text" name="code" class="form-control" placeholder="e.g. HR, DEV, MKT" required>
						</div>

						<div class="col-md-6">
							<label class="form-label">Status</label>
							<select name="status" class="form-select">
								<option value="active" selected>Active</option>
								<option value="inactive">Inactive</option>
							</select>
						</div>

						<div class="col-md-6">
							<label class="form-label">Icon (optional)</label>
							<input type="text" name="icon" class="form-control" placeholder="e.g. fi fi-rr-briefcase">
							<small class="text-muted">Use flaticon class like: <code>fi fi-rr-briefcase</code></small>
						</div>

						<div class="col-12">
							<label class="form-label">Description</label>
							<textarea name="description" class="form-control" rows="3" placeholder="Write department description..."></textarea>
						</div>
					</div>

					<div class="d-flex justify-content-end gap-2 mt-4">
						<button type="button" class="btn btn-outline-light btn-shadow waves-effect" data-bs-dismiss="modal">
							Cancel
						</button>
						<button type="submit" class="btn btn-success waves-effect waves-light">
							<i class="fi fi-rr-check me-1"></i> Save Department
						</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

@endsection