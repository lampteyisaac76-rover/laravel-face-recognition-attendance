@extends('layouts.dashboard')

@section('title', 'Lecturer Dashboard')

@section('content')
@php
    $assigned = $assignedCourses ?? collect([]);
    $sessions = $recentSessions ?? collect([]);

    $totalStudents = $assigned->sum('students_count');
    $todaySessions = $sessions->filter(fn ($session) => $session->session_date && $session->session_date->isToday());
    $todaySessionsCount = $todaySessions->count();
    $activeSessions = $sessions->where('status', 'active')->count();
    $completedSessions = $sessions->where('status', 'ended')->count();

    $latestSession = $sessions->first();
@endphp

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    <section style="background:linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #b45309 100%); border-radius:16px; padding:2rem; color:#fff; box-shadow:0 12px 24px rgba(15,23,42,0.15); margin-bottom:1.5rem; position:relative; overflow:hidden;">
        <div style="position:absolute; right:-100px; bottom:-100px; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>
        <div style="position:relative; z-index:2;">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <div style="color:rgba(255,255,255,0.7); font-size:0.75rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase;">Lecturer workspace</div>
                    <h1 style="margin:0.25rem 0 0.5rem; font-size:2rem; font-weight:900;">Welcome back, {{ Auth::user()->name }}</h1>
                    <p style="margin:0; color:rgba(255,255,255,0.85); max-width:600px; font-size:0.95rem;">
                        Start attendance, review class activity, and manage your assigned courses.
                    </p>
                </div>
                <div class="d-flex align-items-start gap-2">
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); font-size:0.8rem; font-weight:800;">
                        <i class="fas fa-user-tie"></i> {{ ucfirst(Auth::user()->role) }}
                    </span>
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); font-size:0.8rem; font-weight:800;">
                        <i class="fas fa-circle-check" style="color:#4ade80;"></i> System Online
                    </span>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="dash-panel p-4 h-100" style="display:flex; flex-direction:column; justify-content:center;">
                <div style="width:48px; height:48px; border-radius:12px; background:#eff6ff; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                    <i class="fas fa-book-open" style="color:#2563eb; font-size:1.25rem;"></i>
                </div>
                <div style="color:#64748b; font-size:0.75rem; font-weight:800; text-transform:uppercase; margin-bottom:4px;">Assigned Courses</div>
                <div style="color:#0f172a; font-size:1.75rem; font-weight:900; line-height:1;">{{ $assigned->count() }}</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="dash-panel p-4 h-100" style="display:flex; flex-direction:column; justify-content:center;">
                <div style="width:48px; height:48px; border-radius:12px; background:#f0fdf4; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                    <i class="fas fa-user-graduate" style="color:#16a34a; font-size:1.25rem;"></i>
                </div>
                <div style="color:#64748b; font-size:0.75rem; font-weight:800; text-transform:uppercase; margin-bottom:4px;">Students Covered</div>
                <div style="color:#0f172a; font-size:1.75rem; font-weight:900; line-height:1;">{{ $totalStudents }}</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="dash-panel p-4 h-100" style="display:flex; flex-direction:column; justify-content:center;">
                <div style="width:48px; height:48px; border-radius:12px; background:#f5f3ff; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                    <i class="fas fa-clipboard-check" style="color:#8b5cf6; font-size:1.25rem;"></i>
                </div>
                <div style="color:#64748b; font-size:0.75rem; font-weight:800; text-transform:uppercase; margin-bottom:4px;">Today’s Sessions</div>
                <div style="color:#0f172a; font-size:1.75rem; font-weight:900; line-height:1;">{{ $todaySessionsCount }}</div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="dash-panel p-4 h-100" style="display:flex; flex-direction:column; justify-content:center;">
                <div style="width:48px; height:48px; border-radius:12px; background:#fffbeb; display:flex; align-items:center; justify-content:center; margin-bottom:16px;">
                    <i class="fas fa-signal" style="color:#d97706; font-size:1.25rem;"></i>
                </div>
                <div style="color:#64748b; font-size:0.75rem; font-weight:800; text-transform:uppercase; margin-bottom:4px;">Active Sessions</div>
                <div style="color:#0f172a; font-size:1.75rem; font-weight:900; line-height:1;">{{ $activeSessions }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="dash-panel h-100 d-flex flex-column">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Assigned Courses</h5>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#64748b;">Take attendance or review rosters.</p>
                    </div>
                    <div style="position:relative; width:min(280px, 100%);">
                        <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8;"></i>
                        <input type="text" id="course-search" placeholder="Search courses..." 
                               style="width:100%; height:40px; border:1px solid #cbd5e1; border-radius:10px; padding:0 12px 0 36px; font-size:0.85rem; color:#0f172a; outline:none;"
                               onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>
                </div>

                <div class="p-4 flex-grow-1">
                    @forelse($assigned as $course)
                        <div class="course-card-pro" data-search="{{ strtolower($course->code . ' ' . $course->title) }}"
                             style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; padding:16px; margin-bottom:16px; transition:border-color 0.2s, box-shadow 0.2s;"
                             onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 4px 12px rgba(15,23,42,0.05)'"
                             onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                <div>
                                    <span style="display:inline-block; padding:4px 10px; background:#f1f5f9; color:#0f172a; border-radius:6px; font-size:0.75rem; font-weight:800; letter-spacing:0.05em; margin-bottom:8px;">
                                        {{ $course->code }}
                                    </span>
                                    <h3 style="margin:0 0 12px; font-size:1.1rem; font-weight:800; color:#0f172a;">{{ $course->title }}</h3>
                                    
                                    <div class="d-flex flex-wrap gap-2">
                                        <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:6px; background:#f8fafc; border:1px solid #e2e8f0; color:#64748b; font-size:0.75rem; font-weight:700;">
                                            <i class="fas fa-layer-group"></i> Level {{ $course->level }}
                                        </span>
                                        <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:6px; background:#f8fafc; border:1px solid #e2e8f0; color:#64748b; font-size:0.75rem; font-weight:700;">
                                            <i class="fas fa-calendar-alt"></i> Sem {{ $course->semester }}
                                        </span>
                                        <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:6px; background:#f8fafc; border:1px solid #e2e8f0; color:#64748b; font-size:0.75rem; font-weight:700;">
                                            <i class="fas fa-users"></i> {{ $course->students_count }} students
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <a href="{{ route('lecturer.attendance', $course->id) }}" 
                                       style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:none; background:linear-gradient(135deg,#047857,#059669); color:#fff; font-size:0.8rem; font-weight:700; text-decoration:none; transition:transform 0.2s;"
                                       onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                                        <i class="fas fa-camera"></i> Take Attendance
                                    </a>
                                    <a href="{{ route('lecturer.course', $course->id) }}" 
                                       style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:1px solid #cbd5e1; background:#fff; color:#0f172a; font-size:0.8rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                                       onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                                        <i class="fas fa-list-ul"></i> Roster
                                    </a>
                                    <a href="{{ route('lecturer.attendance.history', $course->id) }}" 
                                       style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; border:1px solid #cbd5e1; background:#fff; color:#0f172a; font-size:0.8rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                                       onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                                        <i class="fas fa-history"></i> History
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; padding:64px 20px;">
                            <i class="fas fa-layer-group fa-3x mb-3" style="color:#cbd5e1; display:block;"></i>
                            <p style="font-weight:800; color:#0f172a; font-size:1.1rem; margin:0 0 4px;">No assigned courses</p>
                            <p style="font-size:0.85rem; color:#64748b; margin:0;">Ask an administrator to assign courses to your profile.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-4 d-flex flex-column gap-4">
            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Attendance Summary</h5>
                </div>
                <div class="p-4">
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-radius:10px; background:#f8fafc; border:1px solid #e2e8f0; margin-bottom:12px;">
                        <div>
                            <p style="margin:0; font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase;">Sessions Today</p>
                            <p style="margin:0; font-size:1.2rem; font-weight:900; color:#0f172a;">{{ $todaySessionsCount }}</p>
                        </div>
                        <i class="fas fa-clipboard-check fa-lg text-primary"></i>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-radius:10px; background:#f0fdf4; border:1px solid #bbf7d0; margin-bottom:12px;">
                        <div>
                            <p style="margin:0; font-size:0.75rem; font-weight:800; color:#166534; text-transform:uppercase;">Completed Recently</p>
                            <p style="margin:0; font-size:1.2rem; font-weight:900; color:#14532d;">{{ $completedSessions }}</p>
                        </div>
                        <i class="fas fa-check-circle fa-lg text-success"></i>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 16px; border-radius:10px; background:#fffbeb; border:1px solid #fde68a;">
                        <div>
                            <p style="margin:0; font-size:0.75rem; font-weight:800; color:#92400e; text-transform:uppercase;">Currently Active</p>
                            <p style="margin:0; font-size:1.2rem; font-weight:900; color:#78350f;">{{ $activeSessions }}</p>
                        </div>
                        <i class="fas fa-signal fa-lg text-warning"></i>
                    </div>
                </div>
            </div>

            <div class="dash-panel flex-grow-1">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Recent Activity</h5>
                </div>
                <div class="p-4">
                    <div class="d-flex flex-column gap-3">
                        @forelse($sessions as $session)
                            @php
                                $presentCount = $session->attendances()->where('status', 'present')->count();
                                $totalCount = $session->attendances()->count();
                                $percent = $totalCount > 0 ? round(($presentCount / $totalCount) * 100) : 0;
                            @endphp
                            <div style="padding:16px; border-radius:12px; border:1px solid #e2e8f0; background:#fff;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <p style="margin:0; font-size:0.85rem; font-weight:800; color:#0f172a;">
                                            {{ optional($session->course)->code ?? 'Course' }}
                                        </p>
                                        <p style="margin:0; font-size:0.75rem; color:#64748b;">
                                            {{ $session->session_date ? $session->session_date->format('M d, Y') : 'No date' }} &bull; {{ ucfirst($session->period) }}
                                        </p>
                                    </div>
                                    <span style="display:inline-block; padding:2px 8px; border-radius:6px; font-size:0.7rem; font-weight:800; text-transform:uppercase; 
                                        @if($session->status == 'ended') background:#dcfce7; color:#16a34a; 
                                        @elseif($session->status == 'active') background:#fef3c7; color:#d97706; 
                                        @else background:#eff6ff; color:#2563eb; @endif">
                                        {{ ucfirst($session->status) }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div style="flex-grow:1; height:6px; background:#f1f5f9; border-radius:999px; overflow:hidden;">
                                        <div style="height:100%; width:{{ $percent }}%; background:linear-gradient(90deg,#047857,#10b981);"></div>
                                    </div>
                                    <span style="font-size:0.75rem; font-weight:800; color:#0f172a;">{{ $presentCount }}/{{ $totalCount }}</span>
                                </div>
                                @if($session->course)
                                    <a href="{{ route('lecturer.attendance.history', $session->course->id) }}" 
                                       style="font-size:0.75rem; font-weight:700; color:#2563eb; text-decoration:none; display:inline-flex; align-items:center; gap:4px;"
                                       onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                        View report <i class="fas fa-arrow-right" style="font-size:0.6rem;"></i>
                                    </a>
                                @endif
                            </div>
                        @empty
                            <div style="text-align:center; padding:32px 0;">
                                <i class="fas fa-clock-rotate-left fa-2x mb-3" style="color:#cbd5e1; display:block;"></i>
                                <p style="font-size:0.85rem; font-weight:800; color:#0f172a; margin:0 0 4px;">No activity yet</p>
                                <p style="font-size:0.75rem; color:#64748b; margin:0;">Sessions will appear here after attendance.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('course-search')?.addEventListener('input', event => {
        const query = event.target.value.toLowerCase();
        document.querySelectorAll('.course-card-pro').forEach(card => {
            if (card.dataset.search.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection