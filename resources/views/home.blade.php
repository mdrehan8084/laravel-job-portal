@extends('layouts.app')

@section('title', 'JobPortal - Find your next job')

@section('content')

{{-- Hero --}}
<div class="p-5 mb-5 text-white rounded-4 shadow"
     style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
    <h1 class="display-5 fw-bold">Find your dream job</h1>
    <p class="lead mb-4">
        Browse {{ $totalJobs }} jobs from {{ $totalCompanies }} companies and apply in minutes.
    </p>

    <form action="{{ route('jobs.index') }}" method="GET" class="row g-2">
        <div class="col-md-5">
            <input type="text" name="q" class="form-control form-control-lg"
                   placeholder="Job title or company">
        </div>
        <div class="col-md-4">
            <select name="city" class="form-select form-select-lg">
                <option value="">All cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-grid">
            <button class="btn btn-warning btn-lg fw-semibold">Search</button>
        </div>
    </form>
</div>

{{-- Latest jobs --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Latest Jobs</h3>
    <a href="{{ route('jobs.index') }}">View all jobs →</a>
</div>

<div class="row g-4">
    @forelse($latestJobs as $job)
        <div class="col-md-6 col-lg-4">
            @include('jobs._card')
        </div>
    @empty
        <p class="text-muted">No jobs available right now.</p>
    @endforelse
</div>

@endsection