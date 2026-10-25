<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobListing extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'company',
        'location',
        'type',
        'salary_min',
        'salary_max',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // When a job is deleted, delete its applications one by one so that
        // every resume file is removed from storage as well.
        static::deleting(function (JobListing $job) {
            $job->applications->each->delete();
        });
    }

    // A job belongs to the recruiter (user) who posted it
    public function recruiter()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A job can receive many applications
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Only active jobs: JobListing::active()->get()
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
