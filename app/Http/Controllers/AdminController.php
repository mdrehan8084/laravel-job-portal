<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;

class AdminController extends Controller
{
    private const JOB_TYPES = ['full-time', 'part-time', 'internship', 'remote'];

    // Overview: stat cards, charts and latest records
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'recruiters' => User::where('role', 'recruiter')->count(),
            'candidates' => User::where('role', 'candidate')->count(),
            'jobs' => JobListing::count(),
            'active_jobs' => JobListing::where('is_active', true)->count(),
            'applications' => JobApplication::count(),
        ];

        // Applications grouped by status (doughnut chart)
        $byStatus = JobApplication::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusLabels = array_map('ucfirst', JobApplication::STATUSES);
        $statusData = array_map(fn ($status) => (int) ($byStatus[$status] ?? 0), JobApplication::STATUSES);

        // Jobs grouped by type (bar chart)
        $byType = JobListing::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        $typeLabels = array_map('ucfirst', self::JOB_TYPES);
        $typeData = array_map(fn ($type) => (int) ($byType[$type] ?? 0), self::JOB_TYPES);

        $latestUsers = User::latest()->take(5)->get();
        $latestJobs = JobListing::with('recruiter')->latest()->take(5)->get();

        return view('dashboards.admin', compact(
            'stats',
            'statusLabels',
            'statusData',
            'typeLabels',
            'typeData',
            'latestUsers',
            'latestJobs'
        ));
    }

    // All users
    public function users()
    {
        $users = User::withCount(['jobListings', 'applications'])
            ->latest()
            ->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function destroyUser(User $user)
    {
        abort_if($user->isAdmin(), 403, 'Admin accounts cannot be deleted.');

        // Delete through the models so that resume files are removed from storage too
        $user->applications->each->delete();
        $user->jobListings->each->delete();
        $user->delete();

        return back()->with('success', 'User deleted.');
    }

    // All jobs
    public function jobs()
    {
        $jobs = JobListing::with('recruiter')
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('admin.jobs', compact('jobs'));
    }

    public function toggleJob(JobListing $jobListing)
    {
        $jobListing->update(['is_active' => ! $jobListing->is_active]);

        return back()->with('success', 'Job status updated.');
    }

    public function destroyJob(JobListing $jobListing)
    {
        $jobListing->delete();

        return back()->with('success', 'Job deleted.');
    }
}
