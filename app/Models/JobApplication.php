<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    public const STATUSES = ['pending', 'reviewed', 'shortlisted', 'rejected'];

    protected $fillable = [
        'job_listing_id',
        'user_id',
        'cover_letter',
        'resume_path',
        'status',
    ];

    protected static function booted(): void
    {
        // Remove the uploaded resume from storage when the application is deleted
        static::deleting(function (JobApplication $application) {
            Storage::delete($application->resume_path);
        });
    }

    // The job this application is for
    public function job()
    {
        return $this->belongsTo(JobListing::class, 'job_listing_id');
    }

    // The candidate who applied
    public function candidate()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Bootstrap colour classes for the status badge
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'reviewed' => 'bg-info text-dark',
            'shortlisted' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-warning text-dark',
        };
    }
}
