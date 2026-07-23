@extends('layouts.dashboard')

@section('title', $program->name . ' - Level ' . $level)

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.faculties') }}">Academic</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.faculty', $program->faculty_id) }}">{{ $program->faculty->code }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.program.levels', $program->id) }}">{{ $program->code }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">Level {{ $level }} - Sem {{ $semester }}</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">{{ $program->name }}</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Manage courses for Level {{ $level }}, Semester {{ $semester }}.</p>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn dropdown-toggle" data-bs-toggle="dropdown"
                        style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; outline:none; box-shadow:none;">
                    <i class="fas fa-layer-group text-primary"></i> Level {{ $level }}
                </button>
                <ul class="dropdown-menu border-0 shadow-sm" style="border-radius:12px; overflow:hidden; padding:4px;">
                    @foreach([100, 200, 300, 400] as $lvl)
                        <li><a class="dropdown-item py-2 {{ $level == $lvl ? 'active' : '' }}" href="{{ route('admin.academic.program', [$program->id, 'level' => $lvl, 'semester' => $semester]) }}" style="border-radius:8px; font-size:0.85rem; font-weight:500;">Level {{ $lvl }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn dropdown-toggle" data-bs-toggle="dropdown"
                        style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; outline:none; box-shadow:none;">
                    <i class="fas fa-calendar-alt text-primary"></i> Sem {{ $semester }}
                </button>
                <ul class="dropdown-menu border-0 shadow-sm" style="border-radius:12px; overflow:hidden; padding:4px;">
                    <li><a class="dropdown-item py-2 {{ $semester == 1 ? 'active' : '' }}" href="{{ route('admin.academic.program', [$program->id, 'level' => $level, 'semester' => 1]) }}" style="border-radius:8px; font-size:0.85rem; font-weight:500;">First Semester</a></li>
                    <li><a class="dropdown-item py-2 {{ $semester == 2 ? 'active' : '' }}" href="{{ route('admin.academic.program', [$program->id, 'level' => $level, 'semester' => 2]) }}" style="border-radius:8px; font-size:0.85rem; font-weight:500;">Second Semester</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse($courses as $course)
        <div class="col-md-6 col-lg-4">
            <div class="dash-panel h-100 d-flex flex-column" style="transition:transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 24px rgba(15,23,42,0.08)'" onmouseout="this.style.transform=''; this.style.boxShadow=''">
                <div class="p-4 d-flex flex-column h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span style="display:inline-block; padding:4px 10px; background:#eff6ff; color:#2563eb; border-radius:6px; font-size:0.75rem; font-weight:800; letter-spacing:0.05em;">
                            {{ $course->code }}
                        </span>
                        <span style="font-size:0.75rem; font-weight:700; color:#64748b;">
                            <i class="fas fa-certificate text-primary me-1"></i> {{ $course->credits }} Credits
                        </span>
                    </div>

                    <h6 style="font-size:1.05rem; font-weight:800; color:#0f172a; margin:0 0 8px; line-height:1.4;">{{ $course->title }}</h6>
                    <p style="font-size:0.8rem; color:#64748b; margin:0 0 20px; flex:1;">Manage enrollment and course roster.</p>

                    <div class="d-flex align-items-center justify-content-between pt-3 mt-auto border-top" style="border-color:#e2e8f0 !important;">
                        <span style="font-size:0.75rem; font-weight:700; color:#64748b; display:flex; align-items:center; gap:6px;">
                            <div style="width:28px; height:28px; border-radius:50%; background:#f1f5f9; display:flex; justify-content:center; align-items:center;">
                                <i class="fas fa-users" style="color:#94a3b8; font-size:0.7rem;"></i>
                            </div>
                            @php $lecturerCount = $course->lecturers()->count(); @endphp
                            {{ $lecturerCount > 0 ? $lecturerCount . ' Lecturer(s)' : 'No Lecturers' }}
                        </span>
                        <a href="{{ route('admin.academic.course', $course->id) }}" 
                           style="display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border-radius:10px; background:#f8fafc; border:1px solid #e2e8f0; color:#0f172a; transition:all 0.2s;"
                           onmouseover="this.style.background='#0f172a'; this.style.color='#fff'; this.style.borderColor='#0f172a'" onmouseout="this.style.background='#f8fafc'; this.style.color='#0f172a'; this.style.borderColor='#e2e8f0'">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="dash-panel p-5 text-center">
                <div style="max-width:400px; margin:0 auto;">
                    <i class="fas fa-book-open fa-3x mb-3" style="color:#cbd5e1;"></i>
                    <h5 style="font-size:1.1rem; font-weight:800; color:#0f172a; margin:0 0 8px;">No courses found</h5>
                    <p style="font-size:0.85rem; color:#64748b; margin:0 0 24px;">No pre-loaded courses for <strong>Level {{ $level }} - Semester {{ $semester }}</strong> in this program.</p>
                    <a href="{{ route('admin.academic.program.levels', $program->id) }}" 
                       style="display:inline-flex; align-items:center; gap:8px; padding:10px 24px; border-radius:999px; border:none; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:0.85rem; font-weight:700; text-decoration:none;">
                        <i class="fas fa-arrow-left"></i> Back to Level Selection
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

</div>

@endsection
