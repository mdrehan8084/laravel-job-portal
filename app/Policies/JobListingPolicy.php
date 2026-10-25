<?php

namespace App\Policies;

use App\Models\JobListing;
use App\Models\User;

class JobListingPolicy
{
    // Only the recruiter who posted the job can edit it
    public function update(User $user, JobListing $jobListing): bool
    {
        return $user->id === $jobListing->user_id;
    }

    // Only the recruiter who posted the job can delete it
    public function delete(User $user, JobListing $jobListing): bool
    {
        return $user->id === $jobListing->user_id;
    }
}