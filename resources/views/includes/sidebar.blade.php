{{-- ✅ Fully Updated Sidebar Code (with Blue Background on Active Module) --}}
{{-- Put the CSS below in your main styles.css OR keep it here in layout/sidebar blade --}}
<style>
	/* Blue background for active sidebar item */
	.app-menubar .menubar .menu-item.active>.menu-link,
	.app-menubar .menubar .menu-inner .menu-item.active>.menu-link {
		background: rgba(13, 110, 253, 0.14);
		/* primary blue tint */
		color: #0d6efd;
		border-radius: 10px;
	}

	/* Left blue indicator line (optional) */
	.app-menubar .menubar .menu-item.active>.menu-link::before,
	.app-menubar .menubar .menu-inner .menu-item.active>.menu-link::before {
		content: "";
		position: absolute;
		left: 0;
		top: 8px;
		bottom: 8px;
		width: 4px;
		background: #0d6efd;
		border-radius: 0 6px 6px 0;
	}

	/* Ensure ::before works properly */
	.app-menubar .menubar .menu-link {
		position: relative;
	}
</style>

<!-- begin::GXON Sidebar Menu -->
<aside class="app-menubar" id="menubar">
	<div class="app-navbar-brand">
		<a class="navbar-brand-logo" href="{{ route('index') }}">
			<img src="{{ asset("assets/images/logo.svg") }}" alt="logo">
		</a>
		<a class="navbar-brand-mini visible-light" href="{{ route('index') }}">
			<img src="{{ asset("assets/images/logo-text.svg") }}" alt="logo">
		</a>
		<a class="navbar-brand-mini visible-dark" href="{{ route('index') }}">
			<img src="{{ asset("assets/images/logo-text-white.svg") }}" alt="logo">
		</a>
	</div>

	<nav class="app-navbar" data-simplebar>
		<ul class="menubar">
			<li class="menu-item menu-arrow">
				<ul class="menu-inner">

					<li class="menu-item {{ request()->routeIs('index') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('index') }}">
							<span class="menu-label">Dashboard</span>
						</a>
					</li>

					<li class="menu-item {{ request()->routeIs('employee') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('employee') }}">
							<span class="menu-label">Employee</span>
						</a>
					</li>

					<li class="menu-item {{ request()->routeIs('attendance') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('attendance') }}">
							<span class="menu-label">Departments</span>
						</a>
					</li>

					<li class="menu-item {{ request()->routeIs('leave') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('leave') }}">
							<span class="menu-label">Designations</span>
						</a>
					</li>

					<!-- <li class="menu-item {{ request()->routeIs('payroll') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('payroll') }}">
							<span class="menu-label">Users</span>
						</a>
					</li>

					<li class="menu-item {{ request()->routeIs('recruitment') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('recruitment') }}">
							<span class="menu-label">Setting</span>
						</a>
					</li> -->

					<!-- <li class="menu-item {{ request()->routeIs('task-management') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('task-management') }}">
							<span class="menu-label">Logout</span>
						</a>
					</li> -->

					<li class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
						<a class="menu-link" href="{{ route('analytics') }}">
							<span class="menu-label">Analytics</span>
						</a>
					</li>

				</ul>
			</li>
		</ul>
	</nav>

	<div class="app-footer">
		<a href="{{ route('login-basic') }}" class="btn btn-outline-light waves-effect btn-shadow btn-app-nav w-100">
			<i class="fi fi-rr-door-open text-primary"></i>
			<span class="nav-text">Logut</span>
		</a>
	</div>
</aside>
<!-- end::GXON Sidebar Menu -->