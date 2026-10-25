@extends('layouts.app')

@section('title', $job->title)

@section('content')
<a href="{{ route('jobs.index') }}" class="text-decoration-none">← All jobs</a>

<div class="row mt-3">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h2 class="mb-1">{{ $job->title }}</h2>
                        <h5 class="text-muted">{{ $job->company }}</h5>
                    </div>
                    <span class="badge bg-primary text-capitalize fs-6">{{ $job->type }}</span>
                </div>

                <hr>

                <div class="row mb-3">
                    <div class="col-sm-4"><strong>📍 Location</strong><br>{{ $job->location }}</div>
                    <div class="col-sm-4">
                        <strong>💰 Salary</strong><br>
                        @if($job->salary_min && $job->salary_max)
                            ₹{{ number_format($job->salary_min) }} - ₹{{ number_format($job->salary_max) }} / month
                        @else
                            Not specified
                        @endif
                    </div>
                    <div class="col-sm-4"><strong>🕒 Posted</strong><br>{{ $job->created_at->diffForHumans() }}</div>
                </div>

                <h5>Job description</h5>
                <p style="white-space: pre-line;">{{ $job->description }}</p>

                <p class="text-muted small mb-0">
                    Posted by: {{ $job->recruiter->name }}
                </p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                @guest
                    <div class="text-center">
                        <p class="text-muted">Please log in to apply for this job.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary w-100">Log in to Apply</a>
                    </div>
                @endguest

                @auth
                    @if(auth()->user()->isCandidate())
                        @if($application)
                            <div class="text-center">
                                <p class="mb-2">You applied {{ $application->created_at->diffForHumans() }}.</p>
                                <span class="badge {{ $application->statusBadgeClass() }} fs-6 text-capitalize">
                                    {{ $application->status }}
                                </span>
                                <div class="mt-3">
                                    <a href="{{ route('candidate.dashboard') }}">View my applications</a>
                                </div>
                            </div>
                        @else
                            <h6 class="mb-3">Apply for this job</h6>

                            <form method="POST" action="{{ route('candidate.applications.store', $job) }}"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Cover letter <span class="text-muted">(optional)</span></label>
                                    <textarea name="cover_letter" rows="4"
                                              class="form-control @error('cover_letter') is-invalid @enderror"
                                              placeholder="Tell the recruiter why you are a good fit">{{ old('cover_letter') }}</textarea>
                                    @error('cover_letter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Resume</label>
                                    <input type="file" name="resume" accept=".pdf,.doc,.docx"
                                           class="form-control @error('resume') is-invalid @enderror">
                                    <div class="form-text">PDF, DOC or DOCX. Maximum 2 MB.</div>
                                    @error('resume')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button class="btn btn-success w-100">Submit Application</button>
                            </form>
                        @endif
                    @else
                        <p class="text-muted text-center mb-0">Only candidates can apply for jobs.</p>
                    @endif
                @endauth
            </div>
        </div>

        @if($related->count())
            <h6 class="mb-3">Similar jobs</h6>
            @foreach($related as $item)
                <a href="{{ route('jobs.show', $item) }}" class="card shadow-sm border-0 mb-2 text-decoration-none">
                    <div class="card-body py-2">
                        <strong>{{ $item->title }}</strong><br>
                        <small class="text-muted">{{ $item->company }} · {{ $item->location }}</small>
                    </div>
                </a>
            @endforeach
        @endif
    </div>
</div>
@endsection
