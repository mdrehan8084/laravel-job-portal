@extends('layouts.app')

@section('title', 'Edit Job')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-4">Edit Job</h4>

                @include('recruiter.jobs._form', [
                    'action' => route('recruiter.jobs.update', $job),
                    'method' => 'PUT',
                    'submitLabel' => 'Save Changes',
                ])
            </div>
        </div>
    </div>
</div>
@endsection
