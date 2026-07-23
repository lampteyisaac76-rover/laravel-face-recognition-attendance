@extends('layouts.dashboard')

@section('title', 'Edit Lecturer')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.lecturers') }}">Lecturers</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">Edit</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Edit Lecturer</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Update profile and academic assignments for {{ $lecturer->name }}.</p>
        </div>
        <div>
            <a href="{{ route('admin.lecturers') }}"
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
                        <i class="fas fa-user-edit me-2" style="color:#4f46e5;"></i>Lecturer Profile Update
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

                    <form action="{{ route('admin.lecturer.update', $lecturer->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <h6 style="font-size:0.8rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 16px; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                            <i class="fas fa-user-tie me-2 text-primary"></i>Personal Information
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $lecturer->name) }}" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; transition:border-color 0.2s;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="email" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Institutional Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $lecturer->email) }}" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="staff_id" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Staff ID <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="staff_id" name="staff_id" value="{{ old('staff_id', $lecturer->staff_id) }}" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="phone_number" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Phone Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $lecturer->phone_number) }}" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                        </div>

                        <h6 style="font-size:0.8rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin:24px 0 16px; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                            <i class="fas fa-university me-2 text-primary"></i>Academic Assignment
                        </h6>

                        <div class="mb-4">
                            <label for="faculty_id" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                Faculty <span class="text-danger">*</span>
                            </label>
                            <select id="faculty_id" name="faculty_id" required
                                    style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background-color:#fff;"
                                    onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                <option value="">Select Faculty...</option>
                                @foreach($faculties as $faculty)
                                    <option value="{{ $faculty->id }}" {{ (old('faculty_id', $lecturer->faculty_id) == $faculty->id) ? 'selected' : '' }}>
                                        {{ $faculty->name }} ({{ $faculty->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                Assign Courses
                            </label>
                            <div style="border:1px solid #e2e8f0; border-radius:12px; background:#f8fafc; padding:16px;">
                                <div class="row g-2" style="max-height:250px; overflow-y:auto; overflow-x:hidden;">
                                    @php
                                        $assignedCourses = $lecturer->courses->pluck('id')->toArray();
                                    @endphp
                                    @foreach($courses as $course)
                                        <div class="col-md-6">
                                            <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px; display:flex; align-items:flex-start; gap:10px; transition:border-color 0.2s, box-shadow 0.2s;"
                                                 onmouseover="this.style.borderColor='#cbd5e1'; this.style.boxShadow='0 2px 8px rgba(15,23,42,0.05)'"
                                                 onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                                                <input type="checkbox" name="courses[]" value="{{ $course->id }}" id="course_{{ $course->id }}" 
                                                       {{ (is_array(old('courses')) && in_array($course->id, old('courses'))) || in_array($course->id, $assignedCourses) ? 'checked' : '' }}
                                                       style="margin-top:4px; width:16px; height:16px;">
                                                <label for="course_{{ $course->id }}" style="cursor:pointer; width:100%; margin:0;">
                                                    <span style="display:block; font-weight:700; font-size:0.8rem; color:#0f172a; line-height:1.2;">{{ $course->code }}</span>
                                                    <span style="display:block; font-size:0.72rem; color:#64748b; margin-top:2px; line-height:1.4;">{{ Str::limit($course->title, 40) }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top" style="border-color:#e2e8f0;">
                            <a href="{{ route('admin.lecturers') }}" 
                               style="display:inline-flex; align-items:center; padding:10px 24px; border-radius:999px; border:1px solid #e2e8f0; background:#f8fafc; color:#475569; font-size:0.85rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                               Cancel
                            </a>
                            <button type="submit" 
                                    style="display:inline-flex; align-items:center; gap:8px; padding:10px 28px; border-radius:999px; border:none; background:linear-gradient(135deg,#4f46e5,#2563eb); color:#fff; font-size:0.85rem; font-weight:700; cursor:pointer; box-shadow:0 6px 16px rgba(79,70,229,0.25); transition:transform 0.2s, box-shadow 0.2s;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(79,70,229,0.3)'"
                                    onmouseout="this.style.transform=''; this.style.boxShadow='0 6px 16px rgba(79,70,229,0.25)'">
                                <i class="fas fa-save"></i> Update Lecturer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection