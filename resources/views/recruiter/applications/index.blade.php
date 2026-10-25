@extends('layouts.app')

@section('title', 'Applicants - ' . $job->title)

@section('content')
<a href="{{ route('recruiter.jobs.index') }}" class="text-decoration-none">← My jobs</a>

<div class="d-flex justify-content-between align-items-center mt-3 mb-4">
    <div>
        <h3 class="mb-0">Applicants</h3>
        <span class="text-muted">{{ $job->title }} · {{ $job->company }}</span>
    </div>
    <span class="badge bg-dark fs-6">{{ $applications->total() }} total</span>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Candidate</th>
                    <th>Applied</th>
                    <th>Cover letter</th>
                    <th>Resume</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $application)
                    <tr>
                        <td>
                            <strong>{{ $application->candidate->name }}</strong><br>
                            <small class="text-muted">{{ $application->candidate->email }}</small>
                        </td>
                        <td class="text-nowrap">{{ $application->created_at->format('d M Y') }}</td>
                        <td style="min-width: 200px;">
                            @if($application->cover_letter)
                                <details>
                                    <summary class="text-primary" style="cursor: pointer;">Read</summary>
                                    <p class="small mt-2 mb-0" style="white-space: pre-line;">{{ $application->cover_letter }}</p>
                                </details>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('applications.resume', $application) }}"
                               class="btn btn-sm btn-outline-primary">Download</a>
                        </td>
                        <td style="min-width: 230px;">
                            <form action="{{ route('recruiter.applications.status', $application) }}" method="POST"
                                  class="d-flex gap-2 align-items-center">
                                @csrf
                                @method('PATCH')
                                <span class="badge {{ $application->statusBadgeClass() }} text-capitalize">
                                    {{ $application->status }}
                                </span>
                                <select name="status" class="form-select form-select-sm">
                                    @foreach(\App\Models\JobApplication::STATUSES as $status)
                                        <option value="{{ $status }}" @selected($application->status === $status)>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            No one has applied for this job yet.
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
