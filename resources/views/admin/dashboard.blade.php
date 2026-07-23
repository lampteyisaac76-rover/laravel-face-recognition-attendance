@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- HERO --}}
    <section class="admin-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="hero-kicker">System administration</div>
                <h1 class="hero-title" style="font-size:1.8rem;">Admin Dashboard</h1>
                <p class="hero-subtitle">Face Recognition Attendance Portal</p>
            </div>
            <a href="{{ route('admin.lecturers.create') }}" class="hero-cta">
                <i class="fas fa-plus-circle"></i> Register Lecturer
            </a>
        </div>
    </section>

    {{-- STATS --}}
    <div class="stat-grid stat-grid-6" style="margin-bottom:1.5rem;">

        <div class="stat-card-pro">
            <div class="stat-icon-pro"><i class="fas fa-university"></i></div>
            <div class="stat-value-pro">{{ $totalFaculties }}</div>
            <div class="stat-label-pro">Faculties</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro violet"><i class="fas fa-layer-group"></i></div>
            <div class="stat-value-pro">{{ $totalPrograms }}</div>
            <div class="stat-label-pro">Programs</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro green"><i class="fas fa-book"></i></div>
            <div class="stat-value-pro">{{ $totalCourses }}</div>
            <div class="stat-label-pro">Courses</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro teal"><i class="fas fa-user-graduate"></i></div>
            <div class="stat-value-pro">{{ $totalStudents }}</div>
            <div class="stat-label-pro">Students</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro gold"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-value-pro">{{ $totalLecturers }}</div>
            <div class="stat-label-pro">Lecturers</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro {{ $pendingLecturers ? 'rose' : 'green' }}">
                <i class="fas {{ $pendingLecturers ? 'fa-clock' : 'fa-check' }}"></i>
            </div>
            <div class="stat-value-pro">{{ $pendingLecturers ?: '✓' }}</div>
            <div class="stat-label-pro">Pending</div>
        </div>

    </div>

    {{-- ACTIVITY + LECTURERS --}}
    <div class="row g-3">

        {{-- RECENT ACTIVITY --}}
        <div class="col-lg-5">
            <div class="dash-panel h-100">
                <div class="panel-header-pro">
                    <h5><i class="fas fa-bolt me-2" style="color:#f59e0b;"></i>Recent Activity</h5>
                </div>
                <div class="p-3">
                    @forelse($recentActivity as $activity)
                        <div class="d-flex gap-3 mb-3 pb-3" style="border-bottom:1px solid #f1f5f9;">
                            <div style="width:8px; height:8px; border-radius:50%; background:#4f46e5; margin-top:6px; flex-shrink:0;"></div>
                            <div>
                                <div class="fw-bold" style="font-size:0.85rem; color:#0f172a;">{{ $activity['title'] }}</div>
                                <small style="color:#64748b;">{{ $activity['detail'] }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-inbox"></i>
                            <p>No recent activity</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RECENT LECTURERS --}}
        <div class="col-lg-7">
            <div class="dash-panel h-100">
                <div class="panel-header-pro">
                    <h5><i class="fas fa-chalkboard-teacher me-2" style="color:#0d9488;"></i>Recent Lecturers</h5>
                    <a href="{{ route('admin.lecturers') }}" class="row-action" title="View all">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                {{-- Table header --}}
                <div class="pro-table-row" style="grid-template-columns:2fr 1fr 1fr 1fr; background:#f8fafc; font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; border-bottom:1px solid #e2e8f0;">
                    <span>Lecturer</span>
                    <span>Staff ID</span>
                    <span>Faculty</span>
                    <span>Status</span>
                </div>

                @forelse($recentLecturers as $lecturer)
                    <div class="pro-table-row" style="grid-template-columns:2fr 1fr 1fr 1fr;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="pro-avatar">{{ strtoupper(substr($lecturer->name, 0, 2)) }}</div>
                            <div>
                                <div style="font-weight:600; font-size:0.84rem;">{{ $lecturer->name }}</div>
                                <small>{{ $lecturer->email }}</small>
                            </div>
                        </div>
                        <div style="font-size:0.83rem;">{{ $lecturer->staff_id }}</div>
                        <div style="font-size:0.83rem;">{{ $lecturer->faculty?->code ?? '—' }}</div>
                        <div>
                            <span class="pro-badge {{ $lecturer->is_verified ? 'success' : 'warning' }}">
                                {{ $lecturer->is_verified ? 'Verified' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-user-slash"></i>
                        <p>No lecturers registered yet</p>
                    </div>
                @endforelse

            </div>
        </div>

    </div>

</div>

@endsection