@extends('layouts.app')

@section('title', 'Access denied')

@section('content')
<div class="text-center py-5">
    <h1 class="display-1 fw-bold text-primary">403</h1>
    <h4 class="mb-3">Access denied</h4>
    <div class="alert alert-warning d-inline-block">
        {{ $exception->getMessage() ?: 'You do not have permission to access this page.' }}
    </div>
    <div>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Go to home page</a>
    </div>
</div>
@endsection
