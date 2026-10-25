<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    // The candidate who applied, the recruiter who owns the job, or an admin can open the resume
    public function view(User $user, JobApplication $application): bool
    {
        return $user->isAdmin()
            || $user->id === $application->user_id
            || $user->id === $application->job->user_id;
    }

    // Only the recruiter who owns the job can change the status
    public function updateStatus(User $user, JobApplication $application): bool
    {
        return $user->id === $application->job->user_id;
    }

    // A candidate can withdraw their own application while it is still pending
    public function delete(User $user, JobApplication $application): bool
    {
        return $user->id === $application->user_id && $application->status === 'pending';
    }
}
