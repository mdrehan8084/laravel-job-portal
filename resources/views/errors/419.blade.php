@extends('layouts.app')

@section('title', 'Page expired')

@section('content')
<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-primary">419</h1>
    <h4 class="mb-3">Page expired</h4>
    <p class="text-muted">Your session has expired. Please go back, refresh the page and try again.</p>
    <a href="{{ route('home') }}" class="btn btn-primary">Go to home page</a>
</div>
@endsection
