# Laravel Job Portal

A full-stack job portal built with **Laravel 12** and **Bootstrap 5**. It has three user roles (Admin, Recruiter, Candidate), each with its own dashboard and permissions.

**Live demo:** http://rehanjobs.lovestoblog.com

---



## Features

### Public
- Home page and job listing with pagination
- Search and filter jobs by keyword, location and job type
- Job detail page

### Candidate
- Register and log in
- Apply to a job with a cover letter and resume upload (PDF, DOC, DOCX, max 2 MB)
- Duplicate applications are blocked
- Personal dashboard with status counts (pending, reviewed, shortlisted, rejected)
- Withdraw an application while it is still pending

### Recruiter
- Post, edit and delete own jobs
- Activate or deactivate a job
- Dashboard with job and applicant statistics
- View applicants per job, download resumes and update application status

### Admin
- Dashboard with statistics and charts (Chart.js)
- Manage users and delete accounts (admin accounts are protected)
- Manage all jobs: activate, deactivate or delete

---

## Tech stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2+, Laravel 12 |
| Database | MySQL |
| Frontend | Blade, Bootstrap 5, Chart.js |
| Auth and access | Laravel authentication, custom `role` middleware, Policies |

---

## What this project demonstrates

- **Role-based access control** with a custom middleware (`role`) and route groups per role
- **Authorization with Policies** (`JobListingPolicy`, `JobApplicationPolicy`) so a recruiter can only manage their own jobs
- **Eloquent relationships**: `User hasMany JobListing`, `JobListing hasMany JobApplication`, `JobApplication belongsTo User and JobListing`
- **Query scopes and dynamic filters** using `when()` for the search form
- **Form Request validation** with custom error messages
- **File uploads** stored on the private disk and served only through authorised download routes
- **Model events** that delete resume files when an application or job is removed
- **Route Model Binding** with resource routes and custom parameter names
- **Mass-assignment protection** with `$fillable` and hashed password casting
- **Database migrations and seeders** with unique constraints (one application per candidate per job)
- **Flash messages, the PRG pattern** and custom error pages (403, 404, 419, 500)
- **Deployment** on shared hosting without SSH access

---

## Database overview

| Table | Purpose |
|---|---|
| `users` | All users, with a `role` column (admin, recruiter, candidate) |
| `job_listings` | Jobs posted by recruiters (title, company, location, type, salary range, description, active flag) |
| `job_applications` | Applications with cover letter, resume path and status. Unique on (`job_listing_id`, `user_id`) |

---

## Project structure (main parts)

```
app/
 ├─ Http/
 │   ├─ Controllers/
 │   │   ├─ JobController.php                    # public job list and detail
 │   │   ├─ ApplicationController.php            # candidate: apply, withdraw, resume download
 │   │   ├─ RecruiterJobController.php           # recruiter: job CRUD
 │   │   ├─ RecruiterApplicationController.php   # recruiter: applicants and status
 │   │   └─ AdminController.php                  # admin dashboard, users, jobs
 │   ├─ Middleware/                              # role middleware
 │   └─ Requests/JobListingRequest.php
 ├─ Models/                                      # User, JobListing, JobApplication
 └─ Policies/                                    # JobListingPolicy, JobApplicationPolicy
database/
 ├─ migrations/
 └─ seeders/DatabaseSeeder.php
resources/views/                                 # Blade templates per role
routes/web.php
```

---

## Installation (local)

Requirements: PHP 8.2+, Composer, MySQL (XAMPP works fine).

```bash
# 1. Clone the repository
git clone https://github.com/YOUR_USERNAME/laravel-job-portal.git
cd laravel-job-portal

# 2. Install dependencies
composer install

# 3. Environment file
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=job_portal
DB_USERNAME=root
DB_PASSWORD=
```

Create an empty database named `job_portal`, then:

```bash
# 4. Create tables and demo data
php artisan migrate:fresh --seed

# 5. Start the server
php artisan serve
```

Visit http://127.0.0.1:8000

---

## Demo accounts

The seeder creates demo recruiters, candidates, jobs and applications.

| Role | Email | Password |
|---|---|---|
| Recruiter | recruiter@demo.com | password |
| Recruiter | hr@demo.com | password |
| Candidate | candidate@demo.com | password |
| Candidate | aman@demo.com | password |

> The admin account is created by the seeder as well. Change its password before deploying anywhere public.

---

## Security notes

- Passwords are hashed
- CSRF protection on all forms
- Resumes are not publicly accessible, they are downloaded through policy-checked routes
- Role checks happen in middleware and again in policies
- `.env` and `vendor/` are excluded from the repository

---

## Author

**Mohammad Rehan**
Web developer (HTML, CSS, Bootstrap, JavaScript, PHP, Laravel, React)

GitHub: https://github.com/mdrehan8084

---

## License

This project is open source and available under the [MIT license](https://opensource.org/licenses/MIT).
