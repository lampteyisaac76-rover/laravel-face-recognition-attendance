@extends('layouts.dashboard')

@section('title', 'Attendance History - ' . $course->code)

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('lecturer.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">History</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Attendance History</h1>
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
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:none; background:linear-gradient(135deg,#2563eb,#1d4ed8); color:#fff; font-size:0.82rem; font-weight:700; text-decoration:none; transition:transform 0.2s;"
               onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                <i class="fas fa-camera"></i> Live Session
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column: Sessions List --}}
        <div class="col-lg-4">
            <div class="dash-panel h-100 d-flex flex-column">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-calendar-alt me-2" style="color:#8b5cf6;"></i> Past Sessions
                    </h5>
                </div>

                <div class="flex-grow-1 p-3" style="max-height:600px; overflow-y:auto; background:#fff;">
                    <div class="d-flex flex-column gap-2">
                        @forelse($sessions as $session)
                            @php
                                $isActive = request('session_id') == $session->id;
                            @endphp
                            <a href="{{ route('lecturer.attendance.history', ['course' => $course->id, 'session_id' => $session->id]) }}" 
                               style="text-decoration:none; display:flex; align-items:center; gap:12px; padding:12px; border-radius:12px; transition:all 0.2s;
                                      background:{{ $isActive ? '#f5f3ff' : '#f8fafc' }}; 
                                      border:1px solid {{ $isActive ? '#c4b5fd' : '#e2e8f0' }};"
                               onmouseover="this.style.background='{{ $isActive ? '#f5f3ff' : '#f1f5f9' }}'">
                                
                                <div style="width:40px; height:40px; border-radius:10px; background:{{ $isActive ? '#8b5cf6' : '#fff' }}; color:{{ $isActive ? '#fff' : '#64748b' }}; border:1px solid {{ $isActive ? '#8b5cf6' : '#e2e8f0' }}; display:flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0;">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div style="flex-grow:1; overflow:hidden;">
                                    <p style="margin:0; font-weight:800; color:{{ $isActive ? '#4c1d95' : '#0f172a' }}; font-size:0.9rem;">{{ $session->session_date->format('M d, Y') }}</p>
                                    <p style="margin:0; font-size:0.75rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        <i class="fas fa-clock" style="margin-right:4px;"></i>{{ $session->created_at->format('h:i A') }}
                                    </p>
                                </div>
                                <div style="text-align:right;">
                                    <span style="display:inline-block; padding:4px 8px; border-radius:6px; background:#dcfce7; color:#16a34a; font-size:0.75rem; font-weight:800;">
                                        {{ $session->attendances()->where('status', 'present')->count() }} P
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div style="text-align:center; padding:48px 20px;">
                                <i class="fas fa-history fa-2x mb-3" style="color:#cbd5e1; display:block;"></i>
                                <p style="font-size:0.85rem; color:#64748b; margin:0;">No past sessions found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Session Details --}}
        <div class="col-lg-8">
            <div class="dash-panel h-100 d-flex flex-column">
                @if($selectedSession)
                    <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                            <div>
                                <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                                    <i class="fas fa-clipboard-list me-2" style="color:#2563eb;"></i> Report for {{ $selectedSession->session_date->format('l, F j, Y') }}
                                </h5>
                                <p style="margin:4px 0 0; font-size:0.8rem; color:#64748b;">Started at {{ $selectedSession->created_at->format('h:i A') }}</p>
                            </div>
                            <div class="d-flex gap-2">
                                <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:8px; background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; font-size:0.85rem; font-weight:700;">
                                    <i class="fas fa-user-check"></i> {{ $attendances->where('status', 'present')->count() }} Present
                                </span>
                                <span style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:8px; background:#fef2f2; border:1px solid #fecaca; color:#991b1b; font-size:0.85rem; font-weight:700;">
                                    <i class="fas fa-user-times"></i> {{ $attendances->where('status', 'absent')->count() }} Absent
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 flex-grow-1" style="background:#fff; display:flex; flex-direction:column;">
                        {{-- Search --}}
                        <div style="position:relative; margin-bottom:20px;">
                            <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8;"></i>
                            <input type="text" id="record-search" placeholder="Search student name or ID..." 
                                   style="width:100%; height:44px; border:1px solid #cbd5e1; border-radius:999px; padding:0 16px 0 36px; font-size:0.85rem; color:#0f172a; outline:none; transition:border-color 0.2s;"
                                   onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                        </div>

                        {{-- Table --}}
                        <div style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; flex:1;">
                            <div style="padding:12px 24px; border-bottom:1px solid #e2e8f0; background:#f8fafc;">
                                <div class="row align-items-center">
                                    <div class="col-6"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Student Name</span></div>
                                    <div class="col-4"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">ID Number</span></div>
                                    <div class="col-2 text-end"><span style="font-size:0.75rem; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:0.05em;">Status</span></div>
                                </div>
                            </div>

                            <div style="max-height:450px; overflow-y:auto; flex:1;">
                                @foreach($attendances as $record)
                                    <div class="record-item" data-search="{{ strtolower($record->student->name . ' ' . $record->student->index_number) }}"
                                         style="padding:16px 24px; border-bottom:1px solid #e2e8f0; transition:background 0.2s;"
                                         onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div style="width:32px; height:32px; border-radius:8px; background:#fff; border:1px solid #e2e8f0; display:flex; justify-content:center; align-items:center; font-weight:800; color:#475569; font-size:0.75rem; flex-shrink:0; box-shadow:0 2px 4px rgba(15,23,42,0.05);">
                                                        {{ strtoupper(substr($record->student->name, 0, 1)) }}
                                                    </div>
                                                    <p style="margin:0; font-weight:700; color:#0f172a; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $record->student->name }}</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span style="font-size:0.82rem; font-weight:600; color:#64748b;">{{ $record->student->index_number }}</span>
                                            </div>
                                            <div class="col-2 text-end">
                                                @if($record->status === 'present')
                                                    <span style="display:inline-block; padding:4px 10px; border-radius:6px; background:#dcfce7; color:#16a34a; font-size:0.7rem; font-weight:800;">Present</span>
                                                @else
                                                    <span style="display:inline-block; padding:4px 10px; border-radius:6px; background:#fee2e2; color:#dc2626; font-size:0.7rem; font-weight:800;">Absent</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div style="flex-grow:1; display:flex; flex-direction:column; justify-content:center; align-items:center; padding:64px 20px; text-align:center;">
                        <i class="fas fa-hand-pointer fa-3x mb-3" style="color:#cbd5e1; display:block;"></i>
                        <p style="font-weight:800; color:#0f172a; font-size:1.1rem; margin:0 0 8px;">Select a Session</p>
                        <p style="font-size:0.85rem; color:#64748b; margin:0; max-width:300px;">Choose a past session from the list on the left to view its attendance report.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('record-search')?.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.record-item').forEach(item => {
            const text = item.dataset.search;
            item.style.display = text.includes(q) ? 'block' : 'none';
        });
    });
</script>
@endpush

@endsection