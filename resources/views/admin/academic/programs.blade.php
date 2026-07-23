@extends('layouts.dashboard')

@section('title', $faculty->name . ' - Programs')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.faculties') }}">Faculties</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">{{ $faculty->code }}</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Programs in {{ $faculty->name }}</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Select an academic program to view its levels and courses.</p>
        </div>
        <div>
            <a href="{{ route('admin.academic.faculties') }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> All Faculties
            </a>
        </div>
    </div>

    {{-- PROGRAM CARDS --}}
    <div class="row g-4">
        @forelse($faculty->programs as $program)
            <div class="col-md-6 col-lg-4">
                <a href="{{ route('admin.academic.program.levels', $program->id) }}" class="text-decoration-none">
                    <div class="explorer-card h-100">
                        <div class="ec-top-bar teal"></div>
                        <div class="ec-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="ec-icon teal">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <span class="ec-code-badge">{{ $program->code }}</span>
                            </div>
                            <p class="ec-title mb-2">{{ $program->name }}</p>
                            <div class="ec-footer">
                                <span>
                                    <i class="fas fa-book me-1" style="color:#0d9488;"></i>
                                    {{ $program->courses_count }} {{ Str::plural('Course', $program->courses_count) }}
                                </span>
                                <i class="fas fa-arrow-right arrow"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12">
                <div class="dash-panel">
                    <div class="empty-state">
                        <i class="fas fa-layer-group"></i>
                        <p style="font-weight:700; font-size:1rem; color:#0f172a; margin-bottom:4px;">No Programs Found</p>
                        <p>This faculty currently has no programs assigned.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

</div>

@endsection
