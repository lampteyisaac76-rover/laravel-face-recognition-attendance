@extends('layouts.dashboard')

@section('title', 'Course Roster - ' . $course->code)

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('lecturer.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">Roster</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Course Roster</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">
                <span style="font-weight:700; color:#0f172a;">{{ $course->code }}</span> &bull; {{ $course->title }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('lecturer.dashboard') }}" 
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
            <a href="{{ route('lecturer.attendance', $course->id) }}" 
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:none; background:linear-gradient(135deg,#047857,#059669); color:#fff; font-size:0.82rem; font-weight:700; text-decoration:none; transition:transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                <i class="fas fa-camera"></i> Start Session
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Main Column (Students Table) --}}
        <div class="col-12">
            <div class="dash-panel h-100 d-flex flex-column">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-users me-2" style="color:#10b981;"></i> Enrolled Students
                    </h5>
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:999px; background:#e2e8f0; color:#475569; font-size:0.75rem; font-weight:800;">
                        <i class="fas fa-user-graduate"></i>{{ $students->count() }} Total
                    </span>
                </div>

                <div class="p-4 flex-grow-1" style="background:#fff;">
                    {{-- Search Bar --}}
                    <div style="position:relative; width:min(400px, 100%); margin-bottom:24px;">
                        <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8;"></i>
                        <input type="text" id="roster-search" placeholder="Search by name or ID..." 
                               style="width:100%; height:44px; border:1px solid #cbd5e1; border-radius:999px; padding:0 16px 0 36px; font-size:0.85rem; color:#0f172a; outline:none; transition:border-color 0.2s;"
                               onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    {{-- Table --}}
                    <div style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                        <div style="padding:12px 24px; border-bottom:1px solid #e2e8f0; background:#f8fafc;">
                            <div class="row align-items-center">
                                <div class="col-5"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Student Name</span></div>
                                <div class="col-4"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">ID Number</span></div>
                                <div class="col-3 text-end"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Face Data Status</span></div>
                            </div>
                        </div>

                        <div>
                            @forelse($students as $student)
                                <div class="student-item" data-search="{{ strtolower($student->name . ' ' . $student->index_number) }}"
                                     style="padding:16px 24px; border-bottom:1px solid #e2e8f0; transition:background 0.2s;"
                                     onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                    <div class="row align-items-center">
                                        <div class="col-5">
                                            <div class="d-flex align-items-center gap-3">
                                                <div style="width:36px; height:36px; border-radius:10px; background:#fff; border:1px solid #e2e8f0; display:flex; justify-content:center; align-items:center; font-weight:800; color:#475569; font-size:0.85rem; flex-shrink:0; box-shadow:0 2px 4px rgba(15,23,42,0.05);">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>
                                                <div style="overflow:hidden;">
                                                    <p style="font-weight:700; color:#0f172a; font-size:0.9rem; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->name }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <span style="font-size:0.85rem; font-weight:600; color:#64748b;">{{ $student->index_number }}</span>
                                        </div>
                                        <div class="col-3 text-end">
                                            @if($student->face_descriptor)
                                                <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:999px; background:#dcfce7; color:#16a34a; font-size:0.75rem; font-weight:700;">
                                                    <i class="fas fa-check-circle"></i> Registered
                                                </span>
                                            @else
                                                <span style="display:inline-flex; align-items:center; gap:4px; padding:4px 10px; border-radius:999px; background:#fee2e2; color:#dc2626; font-size:0.75rem; font-weight:700;">
                                                    <i class="fas fa-exclamation-circle"></i> Missing
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center; padding:64px 20px;">
                                    <i class="fas fa-users-slash fa-3x mb-3" style="color:#cbd5e1; display:block;"></i>
                                    <p style="font-weight:800; color:#0f172a; font-size:1.1rem; margin:0 0 4px;">No Students Enrolled</p>
                                    <p style="font-size:0.85rem; color:#64748b; margin:0;">No students have been assigned to this course by the administrator.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    document.getElementById('roster-search')?.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.student-item').forEach(item => {
            const text = item.dataset.search;
            item.style.display = text.includes(q) ? 'block' : 'none';
        });
    });
</script>
@endpush

@endsection
