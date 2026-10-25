<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobListing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    // Candidate dashboard: my applications
    public function index()
    {
        $user = auth()->user();

        $applications = $user->applications()->with('job')->latest()->paginate(8);

        $counts = $user->applications()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboards.candidate', compact('applications', 'counts'));
    }

    // Apply for a job (with resume upload)
    public function store(Request $request, JobListing $jobListing)
    {
        abort_unless($jobListing->is_active, 404);

        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ], [
            'resume.required' => 'Please upload your resume.',
            'resume.uploaded' => 'The resume could not be uploaded. Please choose a file under 2 MB.',
            'resume.mimes' => 'The resume must be a PDF, DOC or DOCX file.',
            'resume.max' => 'The resume must not be larger than 2 MB.',
        ]);

        $alreadyApplied = $jobListing->applications()
            ->where('user_id', $request->user()->id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'You have already applied for this job.');
        }

        // Stored on the private disk (storage/app/private/resumes), not publicly accessible
        $path = $request->file('resume')->store('resumes');

        $jobListing->applications()->create([
            'user_id' => $request->user()->id,
            'cover_letter' => $request->input('cover_letter'),
            'resume_path' => $path,
        ]);

        return redirect()
            ->route('candidate.dashboard')
            ->with('success', 'Application submitted successfully.');
    }

    // Withdraw a pending application
    public function destroy(JobApplication $jobApplication)
    {
        Gate::authorize('delete', $jobApplication);

        $jobApplication->delete(); // the model event also deletes the resume file

        return back()->with('success', 'Application withdrawn.');
    }

    // Download a resume (candidate who applied, job owner, or admin)
    public function resume(JobApplication $jobApplication)
    {
        Gate::authorize('view', $jobApplication);

        abort_unless(Storage::exists($jobApplication->resume_path), 404, 'Resume file not found.');

        $extension = pathinfo($jobApplication->resume_path, PATHINFO_EXTENSION);
        $filename = Str::slug($jobApplication->candidate->name) . '-resume.' . $extension;

        return Storage::download($jobApplication->resume_path, $filename);
    }
}
