@extends('layouts.app')

@section('title', 'Manage Jobs')

@section('content')
<h3 class="mb-3">Admin Dashboard</h3>

@include('admin._nav')

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Job</th>
                    <th>Recruiter</th>
                    <th>Type</th>
                    <th>Applicants</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td>
                            <strong>{{ $job->title }}</strong><br>
                            <small class="text-muted">{{ $job->company }} · {{ $job->location }}</small>
                        </td>
                        <td>{{ $job->recruiter->name }}</td>
                        <td class="text-capitalize">{{ $job->type }}</td>
                        <td>{{ $job->applications_count }}</td>
                        <td>
                            @if($job->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            @if($job->is_active)
                                <a href="{{ route('jobs.show', $job) }}" target="_blank"
                                   class="btn btn-sm btn-outline-primary">View</a>
                            @endif

                            <form action="{{ route('admin.jobs.toggle', $job) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-secondary">
                                    {{ $job->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this job and all its applications? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">No jobs have been posted yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $jobs->links() }}
</div>
@endsection
