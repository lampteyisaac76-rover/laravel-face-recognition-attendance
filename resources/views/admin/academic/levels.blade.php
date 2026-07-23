@extends('layouts.dashboard')

@section('title', $program->name . ' - Levels')

@section('content')

@php
    $levels = [100, 200, 300, 400];
    $levelMeta = [
        100 => ['color' => 'indigo', 'year' => 'First Year',  'icon' => 'fa-door-open'],
        200 => ['color' => 'violet', 'year' => 'Second Year', 'icon' => 'fa-book-reader'],
        300 => ['color' => 'teal',   'year' => 'Third Year',  'icon' => 'fa-graduation-cap'],
        400 => ['color' => 'amber',  'year' => 'Final Year',  'icon' => 'fa-award'],
    ];
    $selectedSem = request('semester', 1);

    /* Map color names to inline gradients for level icon */
    $gradients = [
        'indigo' => 'linear-gradient(135deg,#6366f1,#4f46e5)',
        'violet' => 'linear-gradient(135deg,#7c3aed,#6d28d9)',
        'teal'   => 'linear-gradient(135deg,#0d9488,#0f766e)',
        'amber'  => 'linear-gradient(135deg,#f59e0b,#d97706)',
    ];
    $topBarColors = [
        'indigo' => 'indigo',
        'violet' => 'indigo',
        'teal'   => 'teal',
        'amber'  => 'amber',
    ];
@endphp

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.faculties') }}">{{ $program->faculty->code }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">{{ $program->code }}</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">{{ $program->name }}</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Select a level to view related courses by semester.</p>
        </div>
        <div>
            <a href="{{ route('admin.academic.faculty', $program->faculty_id) }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> Programs
            </a>
        </div>
    </div>

    {{-- SEMESTER SWITCHER --}}
    <div class="dash-panel mb-4">
        <div class="p-3 d-flex justify-content-center">
            <div class="semester-tabs">
                <a href="{{ route('admin.academic.program.levels', ['program' => $program->id, 'semester' => 1]) }}"
                   class="semester-tab text-decoration-none {{ $selectedSem == 1 ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-1"></i> Semester 1
                </a>
                <a href="{{ route('admin.academic.program.levels', ['program' => $program->id, 'semester' => 2]) }}"
                   class="semester-tab text-decoration-none {{ $selectedSem == 2 ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-1"></i> Semester 2
                </a>
            </div>
        </div>
    </div>

    {{-- LEVEL CARDS --}}
    <div class="row g-4 mb-5">
        @foreach($levels as $lvl)
            @php
                $meta = $levelMeta[$lvl];
                $coursesInLevel = $program->courses->where('level', $lvl)->where('semester', $selectedSem);
                $grad = $gradients[$meta['color']];
                $tbColor = $topBarColors[$meta['color']];
            @endphp
            <div class="col-md-6 col-lg-3">
                <div class="explorer-card h-100">
                    <div class="ec-top-bar {{ $tbColor }}"></div>
                    <div class="ec-body text-center" style="padding:1.5rem 1.25rem;">
                        <div style="width:52px; height:52px; border-radius:14px; background:{{ $grad }}; display:grid; place-items:center; color:#fff; font-size:1.2rem; margin:0 auto 0.85rem;">
                            <i class="fas {{ $meta['icon'] }}"></i>
                        </div>
                        <h2 style="font-size:1.3rem; font-weight:900; color:#0f172a; margin:0 0 2px;">Level {{ $lvl }}</h2>
                        <p style="font-size:0.78rem; color:#64748b; margin:0 0 0.85rem;">{{ $meta['year'] }}</p>
                        <span class="pro-badge neutral" style="margin-bottom:1rem; display:inline-flex;">
                            <i class="fas fa-book me-1"></i> {{ $coursesInLevel->count() }} {{ Str::plural('Course', $coursesInLevel->count()) }}
                        </span>

                        @if($coursesInLevel->count() > 0)
                            <button class="btn btn-primary btn-sm rounded-pill w-100 fw-bold"
                                    style="font-size:0.78rem;"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#coursesLevel{{ $lvl }}">
                                View Courses <i class="fas fa-chevron-down ms-1"></i>
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Collapsible course list --}}
                <div class="collapse mt-3" id="coursesLevel{{ $lvl }}">
                    <div class="dash-panel">
                        <div class="panel-header-pro" style="padding:0.75rem 1rem;">
                            <span style="font-size:0.78rem; font-weight:700; color:#0f172a;">
                                Semester {{ $selectedSem }} — Level {{ $lvl }}
                            </span>
                        </div>
                        <div class="p-2">
                            @forelse($coursesInLevel as $course)
                                <a href="{{ route('admin.academic.course', $course->id) }}" class="text-decoration-none">
                                    <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 10px; border-radius:10px; border:1px solid #f1f5f9; margin-bottom:6px; transition:background 0.15s;"
                                         onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                        <div>
                                            <div style="font-size:0.78rem; font-weight:700; color:#0f172a;">{{ $course->code }}</div>
                                            <div style="font-size:0.7rem; color:#64748b; max-width:150px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;">{{ $course->title }}</div>
                                        </div>
                                        <i class="fas fa-arrow-right" style="font-size:0.7rem; color:#94a3b8;"></i>
                                    </div>
                                </a>
                            @empty
                                <p style="font-size:0.8rem; color:#94a3b8; text-align:center; padding:12px 0; margin:0;">No courses found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>

@endsection
