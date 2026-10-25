@extends('layouts.app')

@section('title', 'All Jobs')

@section('content')
<h3 class="mb-4">All Jobs</h3>

<div class="row">
    {{-- Filters --}}
    <div class="col-lg-3 mb-4">
        <form method="GET" action="{{ route('jobs.index') }}" class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Filters</h5>

                <div class="mb-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="form-control" placeholder="Title or company">
                </div>

                <div class="mb-3">
                    <label class="form-label">City</label>
                    <select name="city" class="form-select">
                        <option value="">All</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" @selected(request('city') == $city)>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Job type</label>
                    <select name="type" class="form-select">
                        <option value="">All</option>
                        @foreach(['full-time', 'part-time', 'internship', 'remote'] as $type)
                            <option value="{{ $type }}" @selected(request('type') == $type)>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Minimum salary (₹)</label>
                    <input type="number" name="min_salary" value="{{ request('min_salary') }}"
                           class="form-control" placeholder="e.g. 25000">
                </div>

                <div class="mb-3">
                    <label class="form-label">Sort</label>
                    <select name="sort" class="form-select">
                        <option value="">Newest first</option>
                        <option value="salary" @selected(request('sort') == 'salary')>Highest salary first</option>
                    </select>
                </div>

                <button class="btn btn-primary w-100 mb-2">Apply filters</button>
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>

    {{-- Results --}}
    <div class="col-lg-9">
        <p class="text-muted">{{ $jobs->total() }} jobs found</p>

        <div class="row g-4">
            @forelse($jobs as $job)
                <div class="col-md-6">
                    @include('jobs._card')
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No jobs match your filters. Try changing them.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $jobs->links() }}
        </div>
    </div>
</div>
@endsection