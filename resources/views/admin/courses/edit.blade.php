@extends('layouts.dashboard')

@section('title', 'Edit Course')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.courses') }}">Courses</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">Edit</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Edit Course</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Update module details and academic placement for {{ $course->code }}.</p>
        </div>
        <div>
            <a href="{{ route('admin.courses') }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-edit me-2" style="color:#10b981;"></i>Edit Course Form
                    </h5>
                </div>
                
                <div class="p-4 p-md-5">
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

                    <form action="{{ route('admin.courses.update', $course->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <h6 style="font-size:0.8rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 16px; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                            <i class="fas fa-info-circle me-2 text-primary"></i>Course Details
                        </h6>

                        <div class="mb-4">
                            <label for="title" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                Course Title <span class="text-danger">*</span>
                            </label>
                            <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}" required
                                   style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="code" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Course Code <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="code" name="code" value="{{ old('code', $course->code) }}" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="credits" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Credits <span class="text-danger">*</span>
                                </label>
                                <input type="number" id="credits" name="credits" value="{{ old('credits', $course->credits) }}" min="1" max="6" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                        </div>

                        <h6 style="font-size:0.8rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin:24px 0 16px; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                            <i class="fas fa-sitemap me-2 text-primary"></i>Academic Placement
                        </h6>

                        <div class="mb-4">
                            <label for="program_id" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                Program <span class="text-danger">*</span>
                            </label>
                            <select id="program_id" name="program_id" required
                                    style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background-color:#fff;"
                                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                <option value="">Select Program...</option>
                                @foreach($faculties as $faculty)
                                    <optgroup label="{{ $faculty->name }}">
                                        @foreach($faculty->programs as $program)
                                            <option value="{{ $program->id }}" {{ (old('program_id', $course->program_id) == $program->id) ? 'selected' : '' }}>
                                                {{ $program->name }} ({{ $program->code }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="level" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Level <span class="text-danger">*</span>
                                </label>
                                <select id="level" name="level" required
                                        style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background-color:#fff;"
                                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                    <option value="">Select Level...</option>
                                    @foreach([100, 200, 300, 400] as $lvl)
                                        <option value="{{ $lvl }}" {{ (old('level', $course->level) == $lvl) ? 'selected' : '' }}>Level {{ $lvl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="semester" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Semester <span class="text-danger">*</span>
                                </label>
                                <select id="semester" name="semester" required
                                        style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background-color:#fff;"
                                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                    <option value="">Select Semester...</option>
                                    <option value="1" {{ (old('semester', $course->semester) == 1) ? 'selected' : '' }}>Semester 1</option>
                                    <option value="2" {{ (old('semester', $course->semester) == 2) ? 'selected' : '' }}>Semester 2</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top" style="border-color:#e2e8f0;">
                            <a href="{{ route('admin.courses') }}" 
                               style="display:inline-flex; align-items:center; padding:10px 24px; border-radius:999px; border:1px solid #e2e8f0; background:#f8fafc; color:#475569; font-size:0.85rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                               Cancel
                            </a>
                            <button type="submit" 
                                    style="display:inline-flex; align-items:center; gap:8px; padding:10px 28px; border-radius:999px; border:none; background:linear-gradient(135deg,#4f46e5,#2563eb); color:#fff; font-size:0.85rem; font-weight:700; cursor:pointer; box-shadow:0 6px 16px rgba(79,70,229,0.25); transition:transform 0.2s, box-shadow 0.2s;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(79,70,229,0.3)'"
                                    onmouseout="this.style.transform=''; this.style.boxShadow='0 6px 16px rgba(79,70,229,0.25)'">
                                <i class="fas fa-save"></i> Update Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection