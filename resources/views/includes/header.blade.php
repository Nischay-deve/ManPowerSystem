@auth
@php
$user = auth()->user();
@endphp

<!-- begin::GXON Page Header -->
<header class="app-header">
	<div class="app-header-inner">

		<button class="app-toggler" type="button">
			<span></span>
			<span></span>
			<span></span>
		</button>

		<div class="app-header-start">
			<form class="d-none d-md-flex align-items-center h-100 w-lg-250px w-xxl-300px position-relative">
				<button type="button" class="btn btn-sm border-0 position-absolute start-0 ms-3 p-0">
					<i class="fi fi-rr-search"></i>
				</button>
				<input type="text" class="form-control rounded-5 ps-5" placeholder="Search anything's">
			</form>
		</div>

		<div class="app-header-end">

			{{-- Theme switch --}}
			<div class="px-lg-3 px-2 ps-0 d-flex align-items-center">
				<div class="dropdown">
					<button class="btn btn-icon btn-action-gray rounded-circle" data-bs-toggle="dropdown">
						<i class="fi fi-rr-brightness"></i>
					</button>
					<ul class="dropdown-menu dropdown-menu-end">
						<li>
							<button class="dropdown-item" data-bs-theme-value="light">
								<i class="fi fi-rr-brightness"></i> Light
							</button>
						</li>
						<li>
							<button class="dropdown-item" data-bs-theme-value="dark">
								<i class="fi fi-rr-moon"></i> Dark
							</button>
						</li>
						<li>
							<button class="dropdown-item" data-bs-theme-value="auto">
								<i class="fi fi-br-circle-half-stroke"></i> Auto
							</button>
						</li>
					</ul>
				</div>
			</div>

			<div class="vr my-3"></div>

			{{-- User dropdown --}}
			<div class="dropdown text-end ms-sm-3 ms-2 ms-lg-4">
				<a href="javascript:void(0);" class="d-flex align-items-center py-2"
					data-bs-toggle="dropdown" data-bs-auto-close="outside">

					<div class="text-end me-2 d-none d-lg-inline-block">
						<div class="fw-bold text-dark">{{ $user->name }}</div>
						<small class="text-body d-block lh-sm">
							<i class="fi fi-rr-angle-down text-3xs me-1"></i>
							{{ ucfirst($user->username) }}
						</small>
					</div>

					<div class="avatar avatar-sm rounded-circle avatar-status-success">
						<img src="{{ $user->avatar
							? asset('storage/'.$user->avatar)
							: asset('assets/images/avatar/avatar1.jpg') }}"
							alt="avatar">
					</div>
				</a>

				<ul class="dropdown-menu dropdown-menu-end w-225px mt-1">

					<li class="d-flex align-items-center p-2">
						<div class="avatar avatar-sm rounded-circle">
							<img src="{{ $user->avatar
								? asset('storage/'.$user->avatar)
								: asset('assets/images/avatar/avatar1.jpg') }}"
								alt="">
						</div>
						<div class="ms-2">
							<div class="fw-bold text-dark">{{ $user->name }}</div>
							<small class="text-body d-block lh-sm">{{ $user->email }}</small>
						</div>
					</li>

					<li>
						<div class="dropdown-divider my-1"></div>
					</li>

					<li>
						<a class="dropdown-item d-flex align-items-center gap-2"
							href="{{ route('profile') }}">
							<i class="fi fi-rr-user"></i> View Profile
						</a>
					</li>

					<li>
						<a class="dropdown-item d-flex align-items-center gap-2"
							href="{{ route('profile') }}">
							<i class="fi fi-rr-settings"></i> Account Settings
						</a>
					</li>

					<li>
						<div class="dropdown-divider my-1"></div>
					</li>

					{{-- ✅ SECURE LOGOUT --}}
					<li>
						<form method="POST" action="{{ route('logout') }}">
							@csrf
							<button type="submit"
								class="dropdown-item d-flex align-items-center gap-2 text-danger">
								<i class="fi fi-sr-exit"></i> Log Out
							</button>
						</form>
					</li>

				</ul>
			</div>

		</div>
	</div>
</header>
<!-- end::GXON Page Header -->
@endauth