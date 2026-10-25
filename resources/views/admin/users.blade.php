@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<h3 class="mb-3">Admin Dashboard</h3>

@include('admin._nav')

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Jobs posted</th>
                    <th>Applications</th>
                    <th>Joined</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <strong>{{ $user->name }}</strong><br>
                            <small class="text-muted">{{ $user->email }}</small>
                        </td>
                        <td>
                            @php
                                $roleColor = match ($user->role) {
                                    'admin' => 'danger',
                                    'recruiter' => 'primary',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $roleColor }} text-capitalize">{{ $user->role }}</span>
                        </td>
                        <td>{{ $user->job_listings_count }}</td>
                        <td>{{ $user->applications_count }}</td>
                        <td class="text-nowrap">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="text-end">
                            @if($user->isAdmin())
                                <span class="text-muted small">Protected</span>
                            @else
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this user and everything they posted or applied for? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
