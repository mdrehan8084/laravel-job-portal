<?php

namespace Database\Seeders;

use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    // A tiny valid PDF, used as the demo resume for the seeded applications
    private const DEMO_PDF = 'JVBERi0xLjQKMSAwIG9iago8PCAvVHlwZSAvQ2F0YWxvZyAvUGFnZXMgMiAwIFIgPj4KZW5kb2JqCjIgMCBvYmoKPDwgL1R5cGUgL1BhZ2VzIC9LaWRzIFszIDAgUl0gL0NvdW50IDEgPj4KZW5kb2JqCjMgMCBvYmoKPDwgL1R5cGUgL1BhZ2UgL1BhcmVudCAyIDAgUiAvTWVkaWFCb3ggWzAgMCAzNjAgMTUwXSAvQ29udGVudHMgNCAwIFIgL1Jlc291cmNlcyA8PCAvRm9udCA8PCAvRjEgNSAwIFIgPj4gPj4gPj4KZW5kb2JqCjQgMCBvYmoKPDwgL0xlbmd0aCAxMTAgPj4Kc3RyZWFtCkJUIC9GMSAyMCBUZiAyNCA5MCBUZCAoRGVtbyBSZXN1bWUpIFRqIDAgLTI4IFRkIC9GMSAxMSBUZiAoU2FtcGxlIGZpbGUgY3JlYXRlZCBieSB0aGUgSm9iUG9ydGFsIHNlZWRlci4pIFRqIEVUCmVuZHN0cmVhbQplbmRvYmoKNSAwIG9iago8PCAvVHlwZSAvRm9udCAvU3VidHlwZSAvVHlwZTEgL0Jhc2VGb250IC9IZWx2ZXRpY2EgPj4KZW5kb2JqCnhyZWYKMCA2CjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAwMDAwOSAwMDAwMCBuIAowMDAwMDAwMDU4IDAwMDAwIG4gCjAwMDAwMDAxMTUgMDAwMDAgbiAKMDAwMDAwMDI0MSAwMDAwMCBuIAowMDAwMDAwNDAyIDAwMDAwIG4gCnRyYWlsZXIKPDwgL1NpemUgNiAvUm9vdCAxIDAgUiA+PgpzdGFydHhyZWYKNDcyCiUlRU9GCg==';

    public function run(): void
    {
        // Remove resume files left over from a previous seed
        Storage::deleteDirectory('resumes');

        // ---------- Users ----------
        User::create([
            'name' => 'Admin',
            'email' => 'admin@demo.com',
            'password' => 'password',
            'role' => 'admin',
        ]);

        $recruiter = User::create([
            'name' => 'Tech Recruiter',
            'email' => 'recruiter@demo.com',
            'password' => 'password',
            'role' => 'recruiter',
        ]);

        $hr = User::create([
            'name' => 'Priya HR',
            'email' => 'hr@demo.com',
            'password' => 'password',
            'role' => 'recruiter',
        ]);

        $candidates = [];
        foreach ([
            ['Demo Candidate', 'candidate@demo.com'],
            ['Aman Verma', 'aman@demo.com'],
            ['Sneha Kapoor', 'sneha@demo.com'],
            ['Rohit Sharma', 'rohit@demo.com'],
        ] as [$name, $email]) {
            $candidates[] = User::create([
                'name' => $name,
                'email' => $email,
                'password' => 'password',
                'role' => 'candidate',
            ]);
        }

        // ---------- Jobs ----------
        $jobs = [
            [$recruiter, 'Laravel Developer', 'TechNova Solutions', 'Delhi', 'full-time', 25000, 40000,
                'Work on Laravel and MySQL applications. You should understand REST APIs, authentication and Eloquent well.'],
            [$recruiter, 'React Frontend Developer', 'PixelCraft Studio', 'Noida', 'full-time', 30000, 50000,
                'Build responsive user interfaces with React, hooks and Bootstrap. Experience with API integration is a plus.'],
            [$recruiter, 'WordPress Developer', 'WebWorks Agency', 'Gurugram', 'full-time', 20000, 32000,
                'Create custom themes and plugins. Good knowledge of HTML, CSS, PHP and WordPress is required.'],
            [$recruiter, 'PHP Developer Intern', 'CodeBridge Labs', 'Delhi', 'internship', 8000, 12000,
                'For freshers. Learn core PHP and MySQL while working alongside senior developers.'],
            [$recruiter, 'Full Stack Developer', 'RemoteFirst Tech', 'Remote', 'remote', 35000, 60000,
                'Work on both frontend (React) and backend (Laravel). Fully remote with flexible working hours.'],
            [$recruiter, 'Bootstrap UI Developer', 'Designify', 'Mumbai', 'part-time', 15000, 22000,
                'Build landing pages and dashboards using Bootstrap 5, jQuery and JavaScript.'],
            [$hr, 'Backend Developer (PHP)', 'CloudNest Technologies', 'Gurugram', 'full-time', 35000, 55000,
                'Design and build REST APIs with PHP and Laravel. Experience with MySQL and Git is required.'],
            [$hr, 'UI/UX Designer', 'BrightLabs Design', 'Mumbai', 'full-time', 28000, 45000,
                'Create wireframes and polished interfaces in Figma, and work closely with frontend developers.'],
        ];

        $listings = [];
        foreach ($jobs as [$owner, $title, $company, $location, $type, $min, $max, $description]) {
            $listings[] = $owner->jobListings()->create([
                'title' => $title,
                'company' => $company,
                'location' => $location,
                'type' => $type,
                'salary_min' => $min,
                'salary_max' => $max,
                'description' => $description,
            ]);
        }

        // ---------- Applications: [candidate index, job index, status] ----------
        $applications = [
            [0, 0, 'pending'],
            [0, 1, 'reviewed'],
            [1, 0, 'shortlisted'],
            [1, 4, 'pending'],
            [1, 6, 'pending'],
            [2, 2, 'rejected'],
            [2, 0, 'reviewed'],
            [2, 7, 'reviewed'],
            [3, 3, 'pending'],
            [3, 5, 'shortlisted'],
        ];

        foreach ($applications as $i => [$candidate, $job, $status]) {
            $path = "resumes/demo-resume-{$i}.pdf";
            Storage::put($path, base64_decode(self::DEMO_PDF));

            JobApplication::create([
                'job_listing_id' => $listings[$job]->id,
                'user_id' => $candidates[$candidate]->id,
                'cover_letter' => 'Hello, I am excited to apply for this position. I have hands-on project experience and would love to discuss how I can contribute to your team.',
                'resume_path' => $path,
                'status' => $status,
            ]);
        }
    }
}
