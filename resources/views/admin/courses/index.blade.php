@extends('layouts.dashboard')

@section('title', 'Manage Courses')

@section('content')
@php
    $courseCollection = collect($courses);
    $search = strtolower(trim(request('search', '')));

    $filteredCourses = $search
        ? $courseCollection->filter(function ($course) use ($search) {
            return str_contains(strtolower($course->title), $search)
                || str_contains(strtolower($course->code), $search)
                || str_contains(strtolower(optional($course->program)->code ?? ''), $search)
                || str_contains(strtolower(optional($course->program)->name ?? ''), $search);
        })
        : $courseCollection;

    $totalCourses = $courseCollection->count();
    $totalPrograms = $courseCollection->pluck('program_id')->filter()->unique()->count();
    $totalLecturerAssignments = $courseCollection->sum(fn ($course) => $course->lecturers->count());
    $totalCredits = $courseCollection->sum('credits');
@endphp

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    <div class="admin-hero">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="hero-kicker">Academic setup</div>
                <h1 class="hero-title">Course Management</h1>
                <p class="hero-subtitle">View, search, create, and maintain all courses used for attendance.</p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="hero-cta">
                <i class="fas fa-plus-circle"></i>
                Add Course
            </a>
        </div>
    </div>

    <div class="stat-grid stat-grid-4">
        <div class="stat-card-pro">
            <div class="stat-icon-pro indigo"><i class="fas fa-book-open"></i></div>
            <div class="stat-value-pro">{{ $totalCourses }}</div>
            <div class="stat-label-pro">Total Courses</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro teal"><i class="fas fa-layer-group"></i></div>
            <div class="stat-value-pro">{{ $totalPrograms }}</div>
            <div class="stat-label-pro">Linked Programs</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro green"><i class="fas fa-user-tie"></i></div>
            <div class="stat-value-pro">{{ $totalLecturerAssignments }}</div>
            <div class="stat-label-pro">Lecturer Assignments</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro gold"><i class="fas fa-star"></i></div>
            <div class="stat-value-pro">{{ $totalCredits }}</div>
            <div class="stat-label-pro">Total Credits</div>
        </div>
    </div>

    @if(session('success'))
        <div class="gctu-alert gctu-alert-success shadow-sm">
            <i class="fas fa-check-circle fa-lg"></i>
            <div class="fw-semibold">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div class="gctu-alert gctu-alert-danger shadow-sm">
            <i class="fas fa-exclamation-triangle fa-lg"></i>
            <div class="fw-semibold">{{ session('error') }}</div>
        </div>
    @endif

    <div class="dash-panel">
        <div class="panel-header-pro flex-column flex-lg-row align-items-lg-center gap-3">
            <div>
                <h5><i class="fas fa-book-open me-2" style="color:#154734;"></i>All Courses</h5>
                <p class="mb-0 mt-1" style="font-size:0.78rem; color:#64748b;">
                    {{ $filteredCourses->count() }} of {{ $totalCourses }} courses shown
                </p>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="width:min(520px,100%);">
                <form action="{{ route('admin.courses') }}" method="GET" class="table-search flex-grow-1">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title, code, or program">
                </form>

                @if(request('search'))
                    <a href="{{ route('admin.courses') }}" class="hero-cta" style="background:#fef2f2;color:#b91c1c;border-color:#fecaca;">
                        <i class="fas fa-times"></i>
                        Clear
                    </a>
                @endif
            </div>
        </div>

        <div class="d-none d-lg-grid"
             style="grid-template-columns: 3fr 1.2fr 1.2fr 1fr 100px; gap:0.75rem; padding:0.75rem 1.25rem; background:#f8fafc; border-bottom:1px solid #e2e8f0;">
            <span class="gctu-col-header">Course Details</span>
            <span class="gctu-col-header">Program</span>
            <span class="gctu-col-header">Level / Sem</span>
            <span class="gctu-col-header">Lecturers</span>
            <span class="gctu-col-header text-end">Actions</span>
        </div>

        @forelse($filteredCourses as $course)
            <div class="pro-table-row"
                 style="display:grid; grid-template-columns: 3fr 1.2fr 1.2fr 1fr 100px; gap:0.75rem;">

                <div class="d-flex align-items-center gap-3 min-w-0">
                    <div class="pro-avatar" style="background:linear-gradient(135deg,#154734,#0f3327);">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="mb-1 text-truncate" style="font-size:0.9rem; font-weight:800; color:#0f172a;">
                            {{ $course->title }}
                        </p>
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="pro-badge neutral">{{ $course->code }}</span>
                            <small><i class="fas fa-star me-1"></i>{{ $course->credits }} Credits</small>
                        </div>
                    </div>
                </div>

                <div>
                    <span class="d-lg-none gctu-col-header mb-1">Program</span>
                    <p class="mb-0" style="font-size:0.83rem; font-weight:700; color:#0f172a;">
                        {{ $course->program?->code ?? '—' }}
                    </p>
                    <small>{{ $course->program?->faculty?->code ?? 'No faculty' }}</small>
                </div>

                <div>
                    <span class="d-lg-none gctu-col-header mb-1">Level / Sem</span>
                    <div class="d-flex flex-wrap gap-1">
                        <span class="pro-badge" style="background:#eff6ff; color:#2563eb;">Level {{ $course->level }}</span>
                        <span class="pro-badge warning">Sem {{ $course->semester }}</span>
                    </div>
                </div>

                <div>
                    <span class="d-lg-none gctu-col-header mb-1">Lecturers</span>
                    <span class="pro-badge neutral">
                        <i class="fas fa-user-tie me-1"></i>{{ $course->lecturers->count() }}
                    </span>
                </div>

                <div class="d-flex justify-content-lg-end gap-1">
                    <a href="{{ route('admin.courses.edit', $course->id) }}" class="row-action" title="Edit course">
                        <i class="fas fa-edit"></i>
                    </a>

                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="row-action danger"
                                style="cursor:pointer;"
                                onclick="return confirm('Delete this course?')"
                                title="Delete course">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-book-open"></i>
                <p style="font-weight:800; font-size:0.95rem; color:#0f172a; margin-bottom:4px;">No Courses Found</p>
                <p>Try a different search term or add a new course.</p>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    @media (max-width: 991px) {
        .pro-table-row {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@endsection
