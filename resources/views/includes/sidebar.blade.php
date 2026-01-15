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
					Dashboard
				</a>
			</li>

			<li class="menu-item">
				<a class="menu-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
					href="{{ route('employees.index') }}">
					Employee
				</a>
			</li>



			<li class="menu-item">
				<a class="menu-link {{ request()->routeIs('designations.*') ? 'active' : '' }}"
					href="{{ route('designations.index') }}">
					Designations
				</a>
			</li>

			<li class="menu-item {{ request()->routeIs('analytics') ? 'active' : '' }}"> <a class="menu-link" href="{{ route('analytics') }}"> <span class="menu-label">Analytics</span> </a> </li>

		</ul>
	</nav>

	<div class="app-footer">
		<form method="POST" action="{{ route('logout') }}">
			@csrf
			<button class="btn btn-outline-light w-100">
				Logout
			</button>
		</form>
	</div>

</aside>