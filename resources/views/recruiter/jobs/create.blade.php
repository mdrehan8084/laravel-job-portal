@extends('layouts.app')

@section('title', 'Post a Job')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-4">Post a New Job</h4>

                @include('recruiter.jobs._form', [
                    'action' => route('recruiter.jobs.store'),
                    'method' => 'POST',
                    'submitLabel' => 'Post Job',
                ])
            </div>
        </div>
    </div>
</div>
@endsection
