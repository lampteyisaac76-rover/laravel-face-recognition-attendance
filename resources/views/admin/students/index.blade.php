@extends('layouts.dashboard')

@section('title', 'Students')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- HERO --}}
    <section class="admin-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="hero-kicker">Student management</div>
                <h1 class="hero-title" style="font-size:1.8rem;">Students</h1>
                <p class="hero-subtitle">Manage all registered students in the system.</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="hero-cta">
                <i class="fas fa-user-plus"></i> Register Student
            </a>
        </div>
    </section>

    {{-- STATS --}}
    <div class="stat-grid stat-grid-3" style="margin-bottom:1.5rem;">
        <div class="stat-card-pro">
            <div class="stat-icon-pro indigo"><i class="fas fa-users"></i></div>
            <div class="stat-value-pro">{{ $totalStudents }}</div>
            <div class="stat-label-pro">Total Students</div>
        </div>
        <div class="stat-card-pro">
            <div class="stat-icon-pro green"><i class="fas fa-camera"></i></div>
            <div class="stat-value-pro">{{ $withFace }}</div>
            <div class="stat-label-pro">Face Registered</div>
        </div>
        <div class="stat-card-pro">
            <div class="stat-icon-pro gold"><i class="fas fa-exclamation-triangle"></i></div>
            <div class="stat-value-pro">{{ $withoutFace }}</div>
            <div class="stat-label-pro">Missing Face</div>
        </div>
    </div>

    {{-- TABLE PANEL --}}
    <div class="dash-panel">
        <div class="panel-header-pro" style="flex-wrap:wrap; gap:12px;">
            <h5><i class="fas fa-user-graduate me-2" style="color:#0d9488;"></i>All Students</h5>

            <form method="GET" style="display:flex; flex-wrap:wrap; align-items:center; gap:8px; margin:0;">

                <div class="table-search" style="width:220px;">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search students...">
                </div>

                <select name="level" onchange="this.form.submit()"
                        style="height:40px; padding:0 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:0.8rem; font-weight:600; color:#0f172a; background:#fff;">
                    <option value="">All Levels</option>
                    @foreach([100,200,300,400] as $l)
                        <option value="{{ $l }}" {{ (string) $level === (string) $l ? 'selected' : '' }}>Level {{ $l }}</option>
                    @endforeach
                </select>

                <select name="program_id" onchange="this.form.submit()"
                        style="height:40px; padding:0 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:0.8rem; font-weight:600; color:#0f172a; background:#fff; max-width:200px;">
                    <option value="">All Programs</option>
                    @foreach($programs as $program)
                        <option value="{{ $program->id }}" {{ (string) $programId === (string) $program->id ? 'selected' : '' }}>
                            {{ $program->code }}
                        </option>
                    @endforeach
                </select>

                <select name="face_status" onchange="this.form.submit()"
                        style="height:40px; padding:0 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:0.8rem; font-weight:600; color:#0f172a; background:#fff;">
                    <option value="">All Face Status</option>
                    <option value="registered" {{ $faceStatus === 'registered' ? 'selected' : '' }}>Face Registered</option>
                    <option value="missing" {{ $faceStatus === 'missing' ? 'selected' : '' }}>Missing Face</option>
                </select>

                @if($search || $level || $programId || $faceStatus)
                    <a href="{{ route('admin.students') }}"
                       style="display:inline-flex; align-items:center; gap:5px; height:40px; padding:0 12px; border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc; color:#64748b; font-size:0.78rem; font-weight:700; text-decoration:none;">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif

            </form>
        </div>

        {{-- Column headers --}}
        <div class="pro-table-row" style="grid-template-columns:2.3fr 1fr 0.8fr 1.1fr 1fr 0.9fr 0.9fr; background:#f8fafc; font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; border-bottom:1px solid #e2e8f0;">
            <span>Student</span>
            <span>Index No.</span>
            <span>Level</span>
            <span>Program</span>
            <span>Face</span>
            <span>Courses</span>
            <span style="text-align:right;">Actions</span>
        </div>

        @php
            $levelColors = [
                100 => ['bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#c7d2fe'],
                200 => ['bg' => '#ecfdf5', 'text' => '#047857', 'border' => '#a7f3d0'],
                300 => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a'],
                400 => ['bg' => '#f5f3ff', 'text' => '#6d28d9', 'border' => '#ddd6fe'],
            ];
            $avatarGradients = [
                'linear-gradient(135deg,#4f46e5,#3730a3)',
                'linear-gradient(135deg,#0d9488,#0f766e)',
                'linear-gradient(135deg,#c7941d,#a16207)',
                'linear-gradient(135deg,#e11d48,#be123c)',
                'linear-gradient(135deg,#7c3aed,#6d28d9)',
                'linear-gradient(135deg,#0ea5e9,#0369a1)',
            ];
        @endphp

        @forelse($students as $student)
            @php
                $lc = $levelColors[$student->level] ?? ['bg' => '#f1f5f9', 'text' => '#475569', 'border' => '#e2e8f0'];
                $gradient = $avatarGradients[$student->id % count($avatarGradients)];
            @endphp
            <div class="pro-table-row" style="grid-template-columns:2.3fr 1fr 0.8fr 1.1fr 1fr 0.9fr 0.9fr; padding:0.95rem 1.25rem;">

                <div class="d-flex align-items-center gap-3">
                    @if($student->face_image_path)
                        <img src="{{ asset('storage/' . $student->face_image_path) }}"
                             alt="{{ $student->name }}"
                             style="width:42px; height:42px; border-radius:11px; object-fit:cover; flex-shrink:0; border:1px solid #e2e8f0;">
                    @else
                        <div class="pro-avatar" style="width:42px; height:42px; border-radius:11px; background:{{ $gradient }};">
                            {{ strtoupper(substr($student->name, 0, 2)) }}
                        </div>
                    @endif
                    <div style="min-width:0;">
                        <div style="font-weight:700; font-size:0.85rem; color:#0f172a; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->name }}</div>
                        <small style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis; display:block;">{{ $student->email ?? '—' }}</small>
                    </div>
                </div>

                <div>
                    <span style="font-family:'JetBrains Mono', monospace; font-size:0.76rem; font-weight:600; color:#475569; background:#f8fafc; border:1px solid #f1f5f9; padding:3px 8px; border-radius:6px; display:inline-block;">
                        {{ $student->index_number }}
                    </span>
                </div>

                <div>
                    <span style="display:inline-flex; align-items:center; padding:4px 11px; border-radius:999px; font-size:0.72rem; font-weight:800; background:{{ $lc['bg'] }}; color:{{ $lc['text'] }}; border:1px solid {{ $lc['border'] }};">
                        {{ $student->level }}
                    </span>
                </div>

                <div style="font-size:0.8rem; color:#475569; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $student->program->name ?? '' }}">
                    {{ $student->program->code ?? '—' }}
                </div>

                <div>
                    <span class="pro-badge {{ $student->face_image_path ? 'success' : 'warning' }}">
                        <i class="fas {{ $student->face_image_path ? 'fa-check' : 'fa-times' }} me-1"></i>
                        {{ $student->face_image_path ? 'Registered' : 'Missing' }}
                    </span>
                </div>

                <div>
                    <span class="pro-badge neutral">
                        <i class="fas fa-book me-1"></i>{{ $student->courses->count() }}
                    </span>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:6px;">
                    <a href="{{ route('admin.student.edit', $student->id) }}" class="row-action" title="Edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.student.delete', $student->id) }}" method="POST"
                          onsubmit="return confirm('Remove {{ addslashes($student->name) }}? This cannot be undone.');" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="row-action danger" title="Delete" style="cursor:pointer;">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <p style="font-weight:700; font-size:0.95rem; color:#0f172a; margin-bottom:4px;">No Students Found</p>
                <p>Try a different search term or filter, or register a new student.</p>
            </div>
        @endforelse

        @if($students->hasPages())
           <div class="student-pagination">
    {{ $students->onEachSide(1)->links() }}
</div>
        @endif
    </div>

</div>

@endsection