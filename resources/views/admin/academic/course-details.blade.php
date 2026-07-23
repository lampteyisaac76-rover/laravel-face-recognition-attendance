@extends('layouts.dashboard')

@section('title', $course->code . ' Details')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.faculties') }}">{{ $course->program->faculty->code }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.program.levels', ['program' => $course->program_id, 'semester' => $course->semester]) }}">{{ $course->program->code }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">{{ $course->code }}</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">{{ $course->title }}</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">
                <span style="font-weight:700; color:#0f172a;">{{ $course->code }}</span> &bull; 
                Level {{ $course->level }} &bull; Semester {{ $course->semester }} &bull; {{ $course->credits }} Credits
            </p>
        </div>
        <div>
            <a href="{{ route('admin.academic.program.levels', ['program' => $course->program_id, 'semester' => $course->semester]) }}" 
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> Levels
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Main Column (Students) --}}
        <div class="col-lg-8">
            <div class="dash-panel h-100">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-user-graduate me-2" style="color:#2563eb;"></i> Enrolled Students ({{ $students->count() }})
                    </h5>
                    <a href="{{ route('admin.academic.course.students', $course->id) }}" 
                       style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:8px; border:none; background:#eff6ff; color:#2563eb; font-size:0.75rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                       onmouseover="this.style.background='#dbeafe'" onmouseout="this.style.background='#eff6ff'">
                        Manage Roster <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="p-4 d-flex flex-column" style="flex:1;">
                    @if($students->isEmpty())
                        <div style="text-align:center; padding:64px 20px; margin:auto;">
                            <i class="fas fa-users-slash fa-3x mb-3" style="color:#cbd5e1; display:block;"></i>
                            <p style="font-weight:800; color:#0f172a; font-size:1.1rem; margin:0 0 4px;">No Students Enrolled</p>
                            <p style="font-size:0.85rem; color:#64748b; margin:0 0 24px;">You need to import or add students to this course.</p>
                            <a href="{{ route('admin.academic.course.students', $course->id) }}" 
                               style="display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border-radius:999px; border:1px solid #e2e8f0; background:#f8fafc; color:#0f172a; font-size:0.85rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                <i class="fas fa-file-import text-primary"></i> Import Excel List
                            </a>
                        </div>
                    @else
                        {{-- Quick Grid Preview --}}
                        <div class="row g-3 mb-4">
                            @foreach($students->take(6) as $student)
                                <div class="col-md-6">
                                    <div style="display:flex; align-items:center; gap:12px; padding:12px; border-radius:12px; border:1px solid #e2e8f0; background:#f8fafc;">
                                        <div style="width:36px; height:36px; border-radius:8px; background:#fff; border:1px solid #e2e8f0; display:flex; justify-content:center; align-items:center; font-weight:800; color:#475569; font-size:0.85rem; flex-shrink:0;">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div style="overflow:hidden;">
                                            <p style="font-weight:700; color:#0f172a; font-size:0.85rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->name }}</p>
                                            <p style="color:#64748b; font-size:0.75rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->index_number }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($students->count() > 6)
                            <div style="text-align:center; margin-top:auto; padding-top:16px;">
                                <a href="{{ route('admin.academic.course.students', $course->id) }}" 
                                   style="font-size:0.8rem; font-weight:700; color:#2563eb; text-decoration:none; display:inline-flex; align-items:center; gap:4px;"
                                   onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                    View all {{ $students->count() }} students <i class="fas fa-chevron-right" style="font-size:0.6rem;"></i>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Column (Stats & Lecturers) --}}
        <div class="col-lg-4 d-flex flex-column gap-4">
            
            {{-- Stats --}}
            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1rem 1.25rem;">
                    <h6 style="margin:0; font-size:0.95rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-chart-pie me-2" style="color:#10b981;"></i> Readiness
                    </h6>
                </div>
                <div class="p-4">
                    @php
                        $total = $students->count();
                        $withFace = $students->whereNotNull('face_descriptor')->count();
                        $percentage = $total > 0 ? round(($withFace / $total) * 100) : 0;
                        
                        $color = '#ef4444'; // red
                        if($percentage >= 75) $color = '#10b981'; // green
                        elseif($percentage >= 40) $color = '#f59e0b'; // yellow
                    @endphp

                    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:12px;">
                        <span style="font-size:0.8rem; font-weight:700; color:#64748b;">Face Data Coverage</span>
                        <span style="font-size:1.4rem; font-weight:800; color:#0f172a; line-height:1;">{{ $percentage }}%</span>
                    </div>
                    <div style="height:10px; background:#f1f5f9; border-radius:999px; overflow:hidden; margin-bottom:16px;">
                        <div style="height:100%; width:{{ $percentage }}%; background:{{ $color }}; border-radius:999px; transition:width 1s ease-in-out;"></div>
                    </div>
                    <p style="font-size:0.75rem; color:#64748b; margin:0; line-height:1.5;">
                        <strong>{{ $withFace }}</strong> out of <strong>{{ $total }}</strong> students have their facial data registered for attendance.
                    </p>
                </div>
            </div>

            {{-- Assigned Lecturers --}}
            <div class="dash-panel flex-grow-1">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1rem 1.25rem;">
                    <h6 style="margin:0; font-size:0.95rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-chalkboard-teacher me-2" style="color:#8b5cf6;"></i> Assigned Lecturers
                    </h6>
                </div>
                <div class="p-4 d-flex flex-column h-100">
                    @if($course->lecturers->isEmpty())
                        <div style="text-align:center; padding:32px 0; margin:auto;">
                            <i class="fas fa-user-slash fa-2x mb-2" style="color:#cbd5e1; display:block;"></i>
                            <p style="font-size:0.8rem; color:#64748b; margin:0;">No lecturers assigned.</p>
                        </div>
                    @else
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            @foreach($course->lecturers as $lecturer)
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div style="width:40px; height:40px; border-radius:12px; background:#f5f3ff; border:1px solid #ede9fe; display:flex; justify-content:center; align-items:center; font-weight:800; color:#7c3aed; font-size:0.9rem; flex-shrink:0;">
                                        {{ strtoupper(substr($lecturer->name, 0, 1)) }}
                                    </div>
                                    <div style="overflow:hidden;">
                                        <p style="font-weight:700; color:#0f172a; font-size:0.85rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $lecturer->name }}</p>
                                        <p style="color:#64748b; font-size:0.75rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $lecturer->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div style="margin-top:auto; padding-top:24px; text-align:center;">
                        <a href="{{ route('admin.courses.edit', $course->id) }}" 
                           style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; border:1px solid #e2e8f0; background:#f8fafc; color:#475569; font-size:0.8rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                           onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <i class="fas fa-cog"></i> Manage Course
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection