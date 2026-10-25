<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobListingRequest;
use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Support\Facades\Gate;

class RecruiterJobController extends Controller
{
    // List only the logged-in recruiter's jobs, with a few summary numbers
    public function index()
    {
        $user = auth()->user();

        $jobs = $user->jobListings()->withCount('applications')->latest()->paginate(8);

        $myApplications = fn () => JobApplication::whereHas(
            'job',
            fn ($query) => $query->where('user_id', $user->id)
        );

        $stats = [
            'jobs' => $user->jobListings()->count(),
            'active' => $user->jobListings()->where('is_active', true)->count(),
            'applicants' => $myApplications()->count(),
            'pending' => $myApplications()->where('status', 'pending')->count(),
        ];

        return view('recruiter.jobs.index', compact('jobs', 'stats'));
    }

    public function create()
    {
        return view('recruiter.jobs.create');
    }

    public function store(JobListingRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $request->user()->jobListings()->create($data);

        return redirect()
            ->route('recruiter.jobs.index')
            ->with('success', 'Job posted successfully.');
    }

    public function edit(JobListing $jobListing)
    {
        Gate::authorize('update', $jobListing);

        return view('recruiter.jobs.edit', ['job' => $jobListing]);
    }

    public function update(JobListingRequest $request, JobListing $jobListing)
    {
        Gate::authorize('update', $jobListing);

        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $jobListing->update($data);

        return redirect()
            ->route('recruiter.jobs.index')
            ->with('success', 'Job updated successfully.');
    }

    public function destroy(JobListing $jobListing)
    {
        Gate::authorize('delete', $jobListing);

        $jobListing->delete();

        return redirect()
            ->route('recruiter.jobs.index')
            ->with('success', 'Job deleted.');
    }

    // Switch a job between active and inactive
    public function toggle(JobListing $jobListing)
    {
        Gate::authorize('update', $jobListing);

        $jobListing->update(['is_active' => ! $jobListing->is_active]);

        return back()->with('success', 'Job status updated.');
    }
}
