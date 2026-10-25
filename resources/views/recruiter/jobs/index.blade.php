@extends('layouts.app')

@section('title', 'My Jobs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0">Recruiter Dashboard</h3>
        <small class="text-muted">Manage your jobs and review applicants</small>
    </div>
    <a href="{{ route('recruiter.jobs.create') }}" class="btn btn-primary">+ Post a Job</a>
</div>

@php
    $cards = [
        ['Jobs posted', $stats['jobs'], 'primary'],
        ['Active jobs', $stats['active'], 'success'],
        ['Total applicants', $stats['applicants'], 'info'],
        ['Pending review', $stats['pending'], 'warning'],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach($cards as [$label, $value, $color])
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm border-0 border-start border-4 border-{{ $color }}">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="fs-3 fw-bold">{{ $value }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Job</th>
                    <th>Location</th>
                    <th>Type</th>
                    <th>Salary (₹/month)</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                    <tr>
                        <td>
                            <strong>{{ $job->title }}</strong><br>
                            <small class="text-muted">{{ $job->company }}</small>
                        </td>
                        <td>{{ $job->location }}</td>
                        <td class="text-capitalize">{{ $job->type }}</td>
                        <td>{{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}</td>
                        <td>
                            @if($job->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('recruiter.jobs.applications', $job) }}"
                               class="btn btn-sm btn-dark">
                                Applicants <span class="badge bg-light text-dark">{{ $job->applications_count }}</span>
                            </a>

                            <a href="{{ route('recruiter.jobs.edit', $job) }}"
                               class="btn btn-sm btn-outline-primary">Edit</a>

                            <form action="{{ route('recruiter.jobs.toggle', $job) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-outline-secondary">
                                    {{ $job->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <form action="{{ route('recruiter.jobs.destroy', $job) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this job and all its applications? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            You have not posted any jobs yet.
                            <a href="{{ route('recruiter.jobs.create') }}">Post your first job</a>
                        </td>
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
