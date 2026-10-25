@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<h3 class="mb-3">Admin Dashboard</h3>

@include('admin._nav')

@php
    $cards = [
        ['Total users', $stats['users'], 'primary'],
        ['Recruiters', $stats['recruiters'], 'info'],
        ['Candidates', $stats['candidates'], 'secondary'],
        ['Total jobs', $stats['jobs'], 'dark'],
        ['Active jobs', $stats['active_jobs'], 'success'],
        ['Applications', $stats['applications'], 'warning'],
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach($cards as [$label, $value, $color])
        <div class="col-6 col-lg-2">
            <div class="card shadow-sm border-0 border-start border-4 border-{{ $color }}">
                <div class="card-body py-3">
                    <div class="text-muted small">{{ $label }}</div>
                    <div class="fs-3 fw-bold">{{ $value }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="mb-3">Applications by status</h6>
                <div style="position: relative; height: 280px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
                <h6 class="mb-3">Jobs by type</h6>
                <div style="position: relative; height: 280px;">
                    <canvas id="typeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">Latest users</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <tbody>
                        @foreach($latestUsers as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong><br>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </td>
                                <td><span class="badge bg-secondary text-capitalize">{{ $user->role }}</span></td>
                                <td class="text-muted small text-end">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-semibold">Latest jobs</div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <tbody>
                        @foreach($latestJobs as $job)
                            <tr>
                                <td>
                                    <strong>{{ $job->title }}</strong><br>
                                    <small class="text-muted">{{ $job->company }} · by {{ $job->recruiter->name }}</small>
                                </td>
                                <td>
                                    @if($job->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-muted small text-end">{{ $job->created_at->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusLabels),
            datasets: [{
                data: @json($statusData),
                backgroundColor: ['#ffc107', '#0dcaf0', '#198754', '#dc3545']
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    new Chart(document.getElementById('typeChart'), {
        type: 'bar',
        data: {
            labels: @json($typeLabels),
            datasets: [{
                label: 'Jobs',
                data: @json($typeData),
                backgroundColor: '#0d6efd',
                borderRadius: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
@endpush
