<?php

namespace App\Http\Controllers;

use App\Models\JobListing;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // Home page
    public function home()
    {
        $latestJobs = JobListing::active()->latest()->take(6)->get();
        $totalJobs = JobListing::active()->count();
        $totalCompanies = JobListing::active()->distinct('company')->count('company');
        $cities = JobListing::active()->distinct()->orderBy('location')->pluck('location');

        return view('home', compact('latestJobs', 'totalJobs', 'totalCompanies', 'cities'));
    }

    // Jobs list + filters
    public function index(Request $request)
    {
        $jobs = JobListing::active()
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->q . '%')
                      ->orWhere('company', 'like', '%' . $request->q . '%');
                });
            })
            ->when($request->filled('city'), fn ($query) => $query->where('location', $request->city))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->type))
            ->when($request->filled('min_salary'), fn ($query) => $query->where('salary_max', '>=', $request->min_salary))
            ->when(
                $request->sort === 'salary',
                fn ($query) => $query->orderByDesc('salary_max'),
                fn ($query) => $query->latest()
            )
            ->paginate(6)
            ->withQueryString();

        $cities = JobListing::active()->distinct()->orderBy('location')->pluck('location');

        return view('jobs.index', compact('jobs', 'cities'));
    }

    // Job detail
    public function show(JobListing $jobListing)
    {
        abort_unless($jobListing->is_active, 404);

        $related = JobListing::active()
            ->where('id', '!=', $jobListing->id)
            ->where('type', $jobListing->type)
            ->take(3)
            ->get();

        // The logged-in candidate's own application for this job (if any)
        $application = auth()->check()
            ? $jobListing->applications()->where('user_id', auth()->id())->first()
            : null;

        return view('jobs.show', [
            'job' => $jobListing,
            'related' => $related,
            'application' => $application,
        ]);
    }
}
