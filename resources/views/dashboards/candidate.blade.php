@extends('layouts.app')

@section('title', 'My Applications')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-0">My Applications</h3>
        <small class="text-muted">Track the jobs you have applied for</small>
    </div>
    <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse Jobs</a>
</div>

@php
    $cards = [
        ['Total', $counts->sum(), 'primary'],
        ['Pending', $counts['pending'] ?? 0, 'warning'],
        ['Reviewed', $counts['reviewed'] ?? 0, 'info'],
        ['Shortlisted', $counts['shortlisted'] ?? 0, 'success'],
        ['Rejected', $counts['rejected'] ?? 0, 'danger'],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach($cards as [$label, $value, $color])
        <div class="col-6 col-lg">
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
                    <th>Applied on</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td>
                            @if($application->job->is_active)
                                <a href="{{ route('jobs.show', $application->job) }}"
                                   class="fw-semibold text-decoration-none">{{ $application->job->title }}</a>
                            @else
                                <span class="fw-semibold">{{ $application->job->title }}</span>
                                <span class="badge bg-secondary">Closed</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ $application->job->company }} · {{ $application->job->location }}</small>
                        </td>
                        <td class="text-nowrap">{{ $application->created_at->format('d M Y') }}</td>
                        <td>
                            <span class="badge {{ $application->statusBadgeClass() }} text-capitalize">
                                {{ $application->status }}
                            </span>
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('applications.resume', $application) }}"
                               class="btn btn-sm btn-outline-primary">My resume</a>

                            @if($application->status === 'pending')
                                <form action="{{ route('candidate.applications.destroy', $application) }}" method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Withdraw this application?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Withdraw</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-5">
                            You have not applied for any jobs yet.
                            <a href="{{ route('jobs.index') }}">Find your first job</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $applications->links() }}
</div>
@endsection
