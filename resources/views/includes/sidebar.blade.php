<aside class="app-menubar" id="menubar">

	<div class="app-navbar-brand">
		<a href="{{ route('index') }}"
			class="d-flex align-items-center justify-content-center w-100"
			style="
            height:80px;
            border-bottom:1px solid #eef1f6;
            background:#ffffff;
       ">
			<img src="{{ asset('assets/images/auth/Workx_logo.png') }}"
				alt="Xways Logo"
				style="
                max-height:150px;
				margin-bottom:10px;
                width:auto;
                object-fit:contain;
             ">
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
			<button class="btn btn-danger w-100">
				<i class="fi fi-rr-sign-out-alt me-1"></i>
				Logout
			</button>
		</form>
	</div>

</aside>


{{-- RIGHT MINI SIDEBAR --}}
<div class="app-sidebar-end" style="width: 100px;">
	<ul class="sidebar-list">

		<li>
			<a href="{{ route('index') }}" class="{{ request()->routeIs('index') ? 'active' : '' }}">
				<div class="avatar avatar-sm bg-warning shadow-sharp-warning rounded-circle text-white mx-auto mb-2">
					<i class="fi fi-rr-to-do"></i>
				</div>
				<span class="text-dark">Dashboard</span>
			</a>
		</li>

		<li>
			<a href="{{ route('employees.index') }}" class="{{ request()->routeIs('employees.*') ? 'active' : '' }}">
				<div class="avatar avatar-sm bg-secondary shadow-sharp-secondary rounded-circle text-white mx-auto mb-2">
					<i class="fi fi-rr-interrogation"></i>
				</div>
				<span class="text-dark">Workforce</span>
			</a>
		</li>

		<li>
			<a href="{{ route('designations.index') }}" class="{{ request()->routeIs('designations.*') ? 'active' : '' }}">
				<div class="avatar avatar-sm bg-info shadow-sharp-info rounded-circle text-white mx-auto mb-2">
					<i class="fi fi-rr-calendar"></i>
				</div>
				<span class="text-dark">Designations</span>
			</a>
		</li>

		<li>
			<a href="{{ route('departments.index') }}" class="{{ request()->routeIs('departments.*') ? 'active' : '' }}">
				<div class="avatar avatar-sm bg-gray shadow-sharp-gray rounded-circle text-white mx-auto mb-2">
					<i class="fi fi-rr-settings"></i>
				</div>
				<span class="text-dark">Departments</span>
			</a>
		</li>

	</ul>
</div>