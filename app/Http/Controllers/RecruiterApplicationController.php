<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RecruiterApplicationController extends Controller
{
    // Applicants for one of my jobs
    public function index(JobListing $jobListing)
    {
        Gate::authorize('update', $jobListing);

        $applications = $jobListing->applications()
            ->with('candidate')
            ->latest()
            ->paginate(10);

        return view('recruiter.applications.index', [
            'job' => $jobListing,
            'applications' => $applications,
        ]);
    }

    // Change an application's status
    public function updateStatus(Request $request, JobApplication $jobApplication)
    {
        Gate::authorize('updateStatus', $jobApplication);

        $data = $request->validate([
            'status' => ['required', Rule::in(JobApplication::STATUSES)],
        ]);

        $jobApplication->update($data);

        return back()->with('success', 'Application status updated.');
    }
}
