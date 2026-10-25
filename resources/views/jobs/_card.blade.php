<div class="card h-100 shadow-sm border-0">
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h5 class="card-title mb-0">{{ $job->title }}</h5>
            <span class="badge bg-primary-subtle text-primary-emphasis text-capitalize">
                {{ $job->type }}
            </span>
        </div>

        <p class="text-muted mb-2">{{ $job->company }}</p>

        <p class="mb-1 small">📍 {{ $job->location }}</p>

        @if($job->salary_min && $job->salary_max)
            <p class="mb-2 small">
                💰 ₹{{ number_format($job->salary_min) }} - ₹{{ number_format($job->salary_max) }} / month
            </p>
        @endif

        <p class="small text-secondary">{{ \Illuminate\Support\Str::limit($job->description, 90) }}</p>

        <div class="mt-auto d-flex justify-content-between align-items-center">
            <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
            <a href="{{ route('jobs.show', $job) }}" class="btn btn-sm btn-outline-primary">View details</a>
        </div>
    </div>
</div>