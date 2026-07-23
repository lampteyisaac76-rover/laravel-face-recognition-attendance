@extends('layouts.dashboard')

@section('title', 'Course Roster - ' . $course->code)

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.program.levels', ['program' => $course->program_id, 'semester' => $course->semester]) }}">{{ $course->program->code }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.course', $course->id) }}">{{ $course->code }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">Roster</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Course Roster</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Manage enrolled students for {{ $course->title }}</p>
        </div>
        <div>
            <a href="{{ route('admin.academic.course', $course->id) }}" 
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> Course Details
            </a>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-left:4px solid #16a34a; border-radius:12px; padding:16px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
            <i class="fas fa-check-circle fa-lg text-success"></i>
            <div style="font-weight:700; color:#166534; font-size:0.85rem;">{{ session('success') }}</div>
        </div>
    @endif

    @if(session('error'))
        <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:12px; padding:16px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
            <i class="fas fa-exclamation-triangle fa-lg text-danger"></i>
            <div style="font-weight:700; color:#991b1b; font-size:0.85rem;">{{ session('error') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; border-left:4px solid #dc2626; border-radius:12px; padding:16px; margin-bottom:24px;">
            <div style="font-weight:700; color:#991b1b; font-size:0.85rem; margin-bottom:6px;">
                <i class="fas fa-exclamation-triangle me-1"></i> Please fix the following errors:
            </div>
            <ul style="margin:0; padding-left:24px; color:#b91c1c; font-size:0.8rem; font-weight:500;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        {{-- Main Column (Students Table) --}}
        <div class="col-lg-8">
            <div class="dash-panel h-100 d-flex flex-column">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-users me-2" style="color:#2563eb;"></i>Enrolled Students
                    </h5>
                    <span style="display:inline-block; padding:4px 12px; border-radius:999px; background:#e2e8f0; color:#475569; font-size:0.75rem; font-weight:800;">
                        {{ $students->count() }} Total
                    </span>
                </div>

                {{-- Table --}}
                <div class="flex-grow-1" style="background:#fff;">
                    <div style="padding:12px 24px; border-bottom:1px solid #e2e8f0; background:#f8fafc;">
                        <div class="row align-items-center">
                            <div class="col-5"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Student</span></div>
                            <div class="col-3"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">ID Number</span></div>
                            <div class="col-2 text-center"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Face Data</span></div>
                            <div class="col-2 text-end"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Actions</span></div>
                        </div>
                    </div>

                    @forelse($students as $student)
                        <div class="pro-table-row" style="padding:16px 24px; border-bottom:1px solid #e2e8f0; transition:background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                            <div class="row align-items-center">
                                <div class="col-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:36px; height:36px; border-radius:10px; background:#fff; border:1px solid #e2e8f0; display:flex; justify-content:center; align-items:center; font-weight:800; color:#475569; font-size:0.85rem; flex-shrink:0; box-shadow:0 2px 4px rgba(15,23,42,0.05);">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div style="overflow:hidden;">
                                            <p style="font-weight:700; color:#0f172a; font-size:0.85rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <span style="font-size:0.8rem; font-weight:600; color:#64748b;">{{ $student->index_number }}</span>
                                </div>
                                <div class="col-2 text-center">
                                    @if($student->face_descriptor)
                                        <span style="display:inline-flex; justify-content:center; align-items:center; width:24px; height:24px; border-radius:50%; background:#dcfce7; color:#16a34a; font-size:0.7rem;" title="Face Data Registered">
                                            <i class="fas fa-check"></i>
                                        </span>
                                    @else
                                        <span style="display:inline-flex; justify-content:center; align-items:center; width:24px; height:24px; border-radius:50%; background:#fee2e2; color:#dc2626; font-size:0.7rem;" title="No Face Data">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-2 text-end">
                                    <a href="{{ route('admin.academic.students.edit', $student->id) }}" 
                                       style="display:inline-flex; justify-content:center; align-items:center; width:32px; height:32px; border-radius:8px; background:#eff6ff; color:#2563eb; transition:all 0.2s; text-decoration:none;"
                                       onmouseover="this.style.background='#dbeafe'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#eff6ff'; this.style.transform=''">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; padding:64px 20px;">
                            <i class="fas fa-users-slash fa-3x mb-3" style="color:#cbd5e1; display:block;"></i>
                            <p style="font-weight:800; color:#0f172a; font-size:1.1rem; margin:0 0 4px;">No Students Enrolled</p>
                            <p style="font-size:0.85rem; color:#64748b; margin:0;">Use the tools on the right to add students.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar Column (Actions) --}}
        <div class="col-lg-4 d-flex flex-column gap-4">
            
            {{-- Import Card --}}
            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <h6 style="margin:0; font-size:0.95rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-file-excel me-2" style="color:#10b981;"></i> Import Excel List
                    </h6>
                </div>
                <div class="p-4">
                    <p style="font-size:0.8rem; color:#64748b; margin-bottom:16px;">Upload a CSV or Excel file containing student details.</p>
                    <form action="{{ route('admin.academic.course.students.import', $course->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                                   style="width:100%; padding:8px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                   onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>
                        <button type="submit" 
                                style="width:100%; padding:10px; border-radius:10px; border:none; background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-size:0.85rem; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(16,185,129,0.2); transition:transform 0.2s;"
                                onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                            <i class="fas fa-upload me-2"></i> Upload & Import
                        </button>
                    </form>
                    <div style="text-align:center; margin-top:16px;">
                        <a href="{{ route('admin.academic.course.students.download-template') }}" 
                           style="font-size:0.75rem; font-weight:700; color:#475569; text-decoration:none; display:inline-flex; align-items:center; gap:4px;"
                           onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                            <i class="fas fa-download"></i> Download Template File
                        </a>
                    </div>
                </div>
            </div>

            {{-- Add Single Student Card --}}
            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <h6 style="margin:0; font-size:0.95rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-user-plus me-2" style="color:#8b5cf6;"></i> Add Single Student
                    </h6>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.academic.course.students.store', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">Full Name</label>
                            <input type="text" name="name" placeholder="e.g. John Doe" required
                                   style="width:100%; height:40px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                   onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>
                        <div class="mb-4">
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">Index / Student Number</label>
                            <input type="text" name="index_number" placeholder="e.g. 040900000" required
                                   style="width:100%; height:40px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                   onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>
                        <button type="submit" 
                                style="width:100%; padding:10px; border-radius:10px; border:1px solid #e2e8f0; background:#f8fafc; color:#0f172a; font-size:0.85rem; font-weight:700; cursor:pointer; transition:all 0.2s;"
                                onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <i class="fas fa-plus me-2 text-primary"></i> Add Student
                        </button>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

</div>

@endsection