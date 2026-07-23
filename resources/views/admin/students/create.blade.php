@extends('layouts.dashboard')

@section('title', 'Register Student')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.students') }}">Students</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">Register</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Student Registration</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Add new students manually, or import them via Excel/ZIP.</p>
        </div>
        <div>
            <a href="{{ route('admin.students') }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

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

    {{-- Method Tabs --}}
    <div class="d-flex gap-2 mb-4 flex-wrap" style="background:#fff; padding:8px; border-radius:14px; border:1px solid #e2e8f0; display:inline-flex;">
        <button type="button" id="tab-manual" onclick="switchTab('manual')"
                style="border:none; border-radius:10px; padding:8px 20px; font-size:0.85rem; font-weight:700; background:#eff6ff; color:#2563eb; transition:all 0.2s; display:inline-flex; align-items:center; gap:8px;">
            <i class="fas fa-user-plus"></i> Manual
        </button>
        <button type="button" id="tab-excel" onclick="switchTab('excel')"
                style="border:none; border-radius:10px; padding:8px 20px; font-size:0.85rem; font-weight:700; background:transparent; color:#64748b; transition:all 0.2s; display:inline-flex; align-items:center; gap:8px;">
            <i class="fas fa-file-excel"></i> Excel / CSV
        </button>
        <button type="button" id="tab-zip" onclick="switchTab('zip')"
                style="border:none; border-radius:10px; padding:8px 20px; font-size:0.85rem; font-weight:700; background:transparent; color:#64748b; transition:all 0.2s; display:inline-flex; align-items:center; gap:8px;">
            <i class="fas fa-file-archive"></i> ZIP (Students + Photos)
        </button>
    </div>

    {{-- ── MANUAL TAB ─────────────────────────────────────────── --}}
    <div id="panel-manual">
        <div class="row g-4">
            {{-- Left: Form --}}
            <div class="col-lg-7">
                <div class="dash-panel h-100">
                    <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                        <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                            <i class="fas fa-user-edit me-2" style="color:#2563eb;"></i>Student Details
                        </h5>
                    </div>
                    <div class="p-4 p-md-5">
                        <form id="manual-form" action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="webcam_image" name="webcam_image" value="">

                            <div class="row g-3">
                                {{-- Full Name --}}
                                <div class="col-12">
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. John Mensah" required
                                           style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                </div>

                                {{-- Index Number --}}
                                <div class="col-md-6">
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                        Index Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="index_number" value="{{ old('index_number') }}" placeholder="4235230001" required
                                           style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                        Institutional Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" value="{{ old('email') }}" placeholder="student@gctu.edu.gh" required
                                           style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                </div>

                                {{-- Level --}}
                                <div class="col-md-6">
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                        Academic Level <span class="text-danger">*</span>
                                    </label>
                                    <select name="level" required
                                            style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                        <option value="">Select...</option>
                                        @foreach([100,200,300,400] as $l)
                                            <option value="{{ $l }}" {{ old('level') == $l ? 'selected' : '' }}>Level {{ $l }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Program --}}
                                <div class="col-md-6">
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                        Program <span class="text-danger">*</span>
                                    </label>
                                    <select name="program_id" required
                                            style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                            onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                        <option value="">Select...</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                                {{ $program->name }} ({{ $program->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Courses --}}
                                <div class="col-12 mt-4">
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:10px;">
                                        Enroll in Course(s)
                                    </label>
                                    <div style="border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; padding:16px;">
                                        <div class="row g-2" style="max-height:200px; overflow-y:auto; overflow-x:hidden;">
                                            @foreach($courses as $course)
                                            <div class="col-md-6">
                                                <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px; display:flex; align-items:flex-start; gap:10px; transition:border-color 0.2s, box-shadow 0.2s;"
                                                     onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(15,23,42,0.05)'"
                                                     onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                                    <input type="checkbox" name="course_ids[]" value="{{ $course->id }}" id="mcourse_{{ $course->id }}" 
                                                           {{ in_array($course->id, old('course_ids', [])) ? 'checked' : '' }}
                                                           style="margin-top:4px; width:16px; height:16px;">
                                                    <label for="mcourse_{{ $course->id }}" style="cursor:pointer; width:100%; margin:0;">
                                                        <span style="display:block; font-weight:700; font-size:0.8rem; color:#0f172a; line-height:1.2;">{{ $course->code }}</span>
                                                        <span style="display:block; font-size:0.72rem; color:#64748b; margin-top:2px; line-height:1.4;">{{ Str::limit($course->title, 40) }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Face image upload --}}
                                <div class="col-12 mt-4">
                                    <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                        Face Image (optional)
                                    </label>
                                    <p style="font-size:0.75rem; color:#64748b; margin-bottom:8px;">Upload a photo OR use the webcam panel on the right.</p>
                                    <input type="file" name="face_image" id="face_file_input" accept="image/*"
                                           style="width:100%; padding:8px 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                           onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                </div>

                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" 
                                        style="display:flex; align-items:center; justify-content:center; gap:8px; padding:12px; border-radius:12px; border:none; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:0.95rem; font-weight:700; cursor:pointer; box-shadow:0 8px 20px rgba(37,99,235,0.25); transition:transform 0.2s;"
                                        onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                                    <i class="fas fa-user-plus"></i> Register Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right: Webcam --}}
            <div class="col-lg-5">
                <div class="dash-panel h-100">
                    <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                        <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                            <i class="fas fa-camera me-2" style="color:#10b981;"></i>Webcam Capture
                        </h5>
                    </div>
                    <div class="p-4 p-md-5 d-flex flex-column h-100">
                        <p style="font-size:0.82rem; color:#64748b; margin-bottom:20px;">
                            Take a live photo for the student's biometric attendance profile.
                        </p>

                        {{-- Video preview --}}
                        <div class="position-relative mb-4" style="background:#0f172a; border-radius:16px; overflow:hidden; aspect-ratio:4/3; box-shadow:inset 0 4px 20px rgba(0,0,0,0.5);">
                            <video id="webcam-preview" autoplay muted playsinline class="w-100 h-100" style="object-fit:cover; display:none; transform:scaleX(-1);"></video>
                            <canvas id="webcam-canvas" class="w-100 h-100" style="display:none; transform:scaleX(-1);"></canvas>
                            <div id="webcam-placeholder" class="w-100 h-100 d-flex flex-column align-items-center justify-content-center" style="position:absolute; top:0; left:0;">
                                <i class="fas fa-camera fa-3x mb-3" style="color:rgba(255,255,255,0.15);"></i>
                                <p style="color:rgba(255,255,255,0.4); font-size:0.85rem; margin:0; font-weight:600;">Camera is off</p>
                            </div>
                        </div>

                        {{-- Controls --}}
                        <div class="d-flex gap-2 mb-4">
                            <button type="button" id="btn-start-webcam" class="flex-grow-1"
                                    style="padding:10px; border-radius:10px; border:1px solid #bfdbfe; background:#eff6ff; color:#2563eb; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all 0.2s;">
                                <i class="fas fa-video me-1"></i> Start
                            </button>
                            <button type="button" id="btn-capture" class="flex-grow-1" disabled
                                    style="padding:10px; border-radius:10px; border:1px solid #bbf7d0; background:#f0fdf4; color:#16a34a; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all 0.2s; opacity:0.5;">
                                <i class="fas fa-camera me-1"></i> Capture
                            </button>
                            <button type="button" id="btn-retake" class="flex-grow-1" style="display:none; padding:10px; border-radius:10px; border:1px solid #fef08a; background:#fefce8; color:#ca8a04; font-weight:700; font-size:0.85rem; cursor:pointer; transition:all 0.2s;">
                                <i class="fas fa-redo me-1"></i> Retake
                            </button>
                        </div>

                        {{-- Capture status --}}
                        <div id="capture-status" style="display:none; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px; text-align:center;">
                            <i class="fas fa-check-circle me-1" style="color:#16a34a;"></i>
                            <span style="color:#16a34a; font-size:0.85rem; font-weight:700;">Photo captured successfully</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── EXCEL TAB ──────────────────────────────────────────── --}}
    <div id="panel-excel" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="dash-panel">
                    <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                        <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                            <i class="fas fa-file-excel me-2" style="color:#10b981;"></i>Excel / CSV Bulk Import
                        </h5>
                    </div>
                    <div class="p-4 p-md-5">
                        <form action="{{ route('admin.students.import-excel') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Template --}}
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px; margin-bottom:24px; display:flex; align-items:center; gap:16px;">
                                <div style="width:48px; height:48px; border-radius:12px; background:#dcfce7; border:1px solid #bbf7d0; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="fas fa-file-csv fa-lg" style="color:#16a34a;"></i>
                                </div>
                                <div style="flex-grow:1;">
                                    <p style="margin:0 0 4px; font-weight:700; color:#0f172a; font-size:0.9rem;">Download Template First</p>
                                    <p style="margin:0; font-size:0.75rem; color:#64748b;">Columns: index_number, name, email, level</p>
                                </div>
                                <a href="{{ route('admin.students.template') }}" 
                                   style="padding:8px 16px; border-radius:8px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.8rem; font-weight:700; text-decoration:none;">
                                    <i class="fas fa-download me-1"></i> Template
                                </a>
                            </div>

                            {{-- Course selector --}}
                            <div class="mb-4">
                                <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Enroll Into Course <span class="text-danger">*</span>
                                </label>
                                <select name="course_id" required
                                        style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                    <option value="">Select course...</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">
                                            {{ $course->code }} — {{ $course->title }} (L{{ $course->level }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- File upload --}}
                            <div style="background:#f8fafc; border:2px dashed #cbd5e1; border-radius:16px; padding:32px 16px; text-align:center; margin-bottom:24px; transition:border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#94a3b8'" onmouseout="this.style.borderColor='#cbd5e1'">
                                <input type="file" name="excel_file" id="excel_input" class="d-none" accept=".xlsx,.xls,.csv">
                                <label for="excel_input" style="cursor:pointer; width:100%; margin:0;">
                                    <i class="fas fa-cloud-upload-alt fa-2x mb-3" style="color:#94a3b8;"></i>
                                    <p style="margin:0 0 4px; font-weight:700; color:#0f172a; font-size:0.95rem;">Click to choose file</p>
                                    <p style="margin:0; font-size:0.75rem; color:#64748b;">.xlsx, .xls or .csv — max 10MB</p>
                                </label>
                                <p id="excel-file-name" style="margin:12px 0 0; font-weight:700; color:#2563eb; font-size:0.8rem; display:none;"></p>
                            </div>

                            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px; margin-bottom:24px;">
                                <p style="margin:0; font-size:0.8rem; color:#1e40af; line-height:1.5;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Duplicates are skipped automatically. Upload face images individually from the student roster, or use the ZIP import to upload photos in bulk.
                                </p>
                            </div>

                            <button type="submit" 
                                    style="width:100%; padding:14px; border-radius:12px; border:none; background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-size:0.95rem; font-weight:700; cursor:pointer; box-shadow:0 8px 20px rgba(16,185,129,0.25); transition:transform 0.2s;"
                                    onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                                <i class="fas fa-file-import me-2"></i> Import Students
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── ZIP TAB ────────────────────────────────────────────── --}}
    <div id="panel-zip" style="display:none;">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="dash-panel">
                    <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                        <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                            <i class="fas fa-file-archive me-2" style="color:#8b5cf6;"></i>ZIP Import — Students + Face Images
                        </h5>
                    </div>
                    <div class="p-4 p-md-5">
                        <form action="{{ route('admin.students.import-zip') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- How it works --}}
                            <div style="background:#faf5ff; border:1px solid #e9d5ff; border-radius:12px; padding:16px; margin-bottom:24px;">
                                <p style="margin:0 0 12px; font-weight:700; color:#6b21a8; font-size:0.9rem;">
                                    <i class="fas fa-info-circle me-2"></i>How to prepare your ZIP:
                                </p>
                                <ol style="margin:0; padding-left:20px; color:#7e22ce; font-size:0.8rem; line-height:1.8;">
                                    <li>Put your <strong>students.xlsx</strong> (or .csv) inside a folder</li>
                                    <li>Name each face image after the student's index number — <strong>4235230017.jpg</strong></li>
                                    <li>ZIP the folder and upload below</li>
                                    <li>System matches images to students automatically</li>
                                </ol>
                            </div>

                            {{-- ZIP structure --}}
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px; margin-bottom:24px; font-family:monospace;">
                                <p style="margin:0 0 8px; font-weight:700; color:#64748b; font-size:0.7rem; text-transform:uppercase; letter-spacing:1px;">
                                    Example ZIP Structure
                                </p>
                                <p style="margin:0; color:#0f172a; font-size:0.8rem; line-height:1.8;">
                                    📦 batch_import.zip<br>
                                    &nbsp;&nbsp;📄 students.xlsx<br>
                                    &nbsp;&nbsp;🖼 4235230001.jpg<br>
                                    &nbsp;&nbsp;🖼 4235230002.jpg<br>
                                    &nbsp;&nbsp;🖼 4235230003.png<br>
                                </p>
                            </div>

                            {{-- Course selector --}}
                            <div class="mb-4">
                                <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Enroll Into Course <span class="text-danger">*</span>
                                </label>
                                <select name="course_id" required
                                        style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                    <option value="">Select course...</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">
                                            {{ $course->code }} — {{ $course->title }} (L{{ $course->level }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ZIP upload area --}}
                            <div style="background:#f8fafc; border:2px dashed #cbd5e1; border-radius:16px; padding:32px 16px; text-align:center; margin-bottom:24px; transition:border-color 0.2s;"
                                 onmouseover="this.style.borderColor='#94a3b8'" onmouseout="this.style.borderColor='#cbd5e1'">
                                <input type="file" name="zip_file" id="zip_input" class="d-none" accept=".zip">
                                <label for="zip_input" style="cursor:pointer; width:100%; margin:0;">
                                    <i class="fas fa-file-archive fa-2x mb-3" style="color:#a78bfa;"></i>
                                    <p style="margin:0 0 4px; font-weight:700; color:#0f172a; font-size:0.95rem;">Click to choose ZIP file</p>
                                    <p style="margin:0; font-size:0.75rem; color:#64748b;">.zip only — max 100MB</p>
                                </label>
                                <p id="zip-file-name" style="margin:12px 0 0; font-weight:700; color:#8b5cf6; font-size:0.8rem; display:none;"></p>
                            </div>

                            <button type="submit" id="btn-zip-submit"
                                    style="width:100%; padding:14px; border-radius:12px; border:none; background:linear-gradient(135deg,#8b5cf6,#6d28d9); color:#fff; font-size:0.95rem; font-weight:700; cursor:pointer; box-shadow:0 8px 20px rgba(139,92,246,0.25); transition:transform 0.2s;"
                                    onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                                <i class="fas fa-file-archive me-2"></i> Import ZIP Package
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Tab switching ─────────────────────────────────────────
    window.switchTab = function (tab) {
        const tabs   = ['manual', 'excel', 'zip'];
        const panels = {
            manual: document.getElementById('panel-manual'),
            excel:  document.getElementById('panel-excel'),
            zip:    document.getElementById('panel-zip'),
        };
        const buttons = {
            manual: document.getElementById('tab-manual'),
            excel:  document.getElementById('tab-excel'),
            zip:    document.getElementById('tab-zip'),
        };

        tabs.forEach(function (t) {
            const isActive = t === tab;
            if (panels[t])  panels[t].style.display = isActive ? 'block' : 'none';
            
            if (buttons[t]) {
                if (isActive) {
                    buttons[t].style.background = '#eff6ff';
                    buttons[t].style.color = '#2563eb';
                } else {
                    buttons[t].style.background = 'transparent';
                    buttons[t].style.color = '#64748b';
                }
            }
        });
    };

    // ── Show selected filenames ───────────────────────────────
    const excelInput = document.getElementById('excel_input');
    const excelName  = document.getElementById('excel-file-name');
    if (excelInput) {
        excelInput.addEventListener('change', function () {
            if (this.files.length && excelName) {
                excelName.textContent = '✓ ' + this.files[0].name;
                excelName.style.display = 'block';
            }
        });
    }

    const zipInput = document.getElementById('zip_input');
    const zipName  = document.getElementById('zip-file-name');
    const zipBtn   = document.getElementById('btn-zip-submit');
    if (zipInput) {
        zipInput.addEventListener('change', function () {
            if (this.files.length) {
                if (zipName) {
                    zipName.textContent = '✓ ' + this.files[0].name;
                    zipName.style.display = 'block';
                }
                const sizeMB = (this.files[0].size / 1024 / 1024).toFixed(1);
                if (parseFloat(sizeMB) > 10 && zipBtn) {
                    zipBtn.innerHTML = '<i class="fas fa-file-archive me-2"></i>Import ZIP (' + sizeMB + 'MB)';
                }
            }
        });
    }

    // Show spinner on ZIP submit
    if (zipBtn) {
        zipBtn.closest('form').addEventListener('submit', function () {
            zipBtn.disabled = true;
            zipBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing... please wait';
        });
    }

    // ── Webcam ────────────────────────────────────────────────
    let stream     = null;
    let captured   = false;

    const video       = document.getElementById('webcam-preview');
    const canvas      = document.getElementById('webcam-canvas');
    const placeholder = document.getElementById('webcam-placeholder');
    
    const btnStart   = document.getElementById('btn-start-webcam');
    const btnCapture = document.getElementById('btn-capture');
    const btnRetake  = document.getElementById('btn-retake');
    const statusMsg  = document.getElementById('capture-status');
    const inputHidden= document.getElementById('webcam_image');
    const fileInput  = document.getElementById('face_file_input');

    if (btnStart) {
        btnStart.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'user', width: 640, height: 480 } 
                });
                video.srcObject = stream;
                
                placeholder.style.display = 'none';
                canvas.style.display      = 'none';
                video.style.display       = 'block';
                
                btnStart.style.display = 'none';
                btnCapture.disabled    = false;
                btnCapture.style.opacity = '1';
                btnRetake.style.display  = 'none';
                
                captured = false;
                statusMsg.style.display = 'none';
                inputHidden.value = '';
                
            } catch (err) {
                alert("Camera access denied or unavailable.");
                console.error(err);
            }
        });
    }

    if (btnCapture) {
        btnCapture.addEventListener('click', () => {
            if (!stream) return;
            
            canvas.width  = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            video.style.display  = 'none';
            canvas.style.display = 'block';
            
            btnCapture.style.display = 'none';
            btnRetake.style.display  = 'block';
            
            inputHidden.value = canvas.toDataURL('image/jpeg', 0.9);
            statusMsg.style.display = 'block';
            
            if (fileInput) fileInput.value = "";
        });
    }

    if (btnRetake) {
        btnRetake.addEventListener('click', () => {
            canvas.style.display = 'none';
            video.style.display  = 'block';
            
            btnRetake.style.display  = 'none';
            btnCapture.style.display = 'block';
            btnCapture.disabled      = false;
            btnCapture.style.opacity = '1';
            
            inputHidden.value = '';
            statusMsg.style.display = 'none';
        });
    }

    // Stop camera when leaving tab
    window.addEventListener('beforeunload', () => {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    });

});
</script>
@endpush
@endsection