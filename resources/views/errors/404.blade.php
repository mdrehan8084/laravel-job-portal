@extends('layouts.app')

@section('title', 'Page not found')

@section('content')
<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-primary">404</h1>
    <h4 class="mb-3">Page not found</h4>
    <p class="text-muted">The page you are looking for does not exist, or this job is no longer available.</p>
    <a href="{{ route('jobs.index') }}" class="btn btn-primary">Browse jobs</a>
</div>
@endsection
