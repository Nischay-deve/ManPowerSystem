<aside class="app-menubar" id="menubar">

	<div class="app-navbar-brand">
		<a class="navbar-brand-logo" href="{{ route('index') }}">
			<img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
		</a>
	</div>

	<nav class="app-navbar" data-simplebar>
		<ul class="menubar">

			<li class="menu-item">
				<a class="menu-link {{ request()->routeIs('index') ? 'active' : '' }}"
					href="{{ route('index') }}">
					<i class="fi fi-rr-apps"></i>
					<span class="menu-label">Dashboard</span>
				</a>
			</li>

			<li class="menu-item">
				<a class="menu-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
					href="{{ route('employees.index') }}">
					<i class="fi fi-rr-users"></i>
					<span class="menu-label">Workforce</span>
				</a>
			</li>

			<li class="menu-item">
				<a class="menu-link {{ request()->routeIs('designations.*') ? 'active' : '' }}"
					href="{{ route('designations.index') }}">
					<i class="fi fi-rr-id-badge"></i>
					<span class="menu-label">Designations</span>
				</a>
			</li>

			<li class="menu-item">
				<a class="menu-link {{ request()->routeIs('departments.*') ? 'active' : '' }}"
					href="{{ route('departments.index') }}">
					<i class="fi fi-rr-building"></i>
					<span class="menu-label">Departments</span>
				</a>
			</li>

		</ul>
	</nav>


	<div class="app-footer">
		<form method="POST" action="{{ route('logout') }}">
			@csrf
			<button class="btn btn-outline-light w-100">
				<i class="fi fi-rr-sign-out-alt me-1"></i>
				Logout
			</button>
		</form>
	</div>

</aside>