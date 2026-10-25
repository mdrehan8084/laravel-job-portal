<ul class="nav nav-pills mb-4">
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           href="{{ route('admin.dashboard') }}">Overview</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
           href="{{ route('admin.users.index') }}">Users</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}"
           href="{{ route('admin.jobs.index') }}">Jobs</a>
    </li>
</ul>
