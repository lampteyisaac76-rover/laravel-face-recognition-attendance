@extends('layouts.dashboard')

@section('title', 'Manage Lecturers')

@section('content')

@php
    $total    = $lecturers->count();
    $verified = $lecturers->where('is_verified', true)->count();
    $pending  = $lecturers->where('is_verified', false)->count();
    $facCount = $lecturers->pluck('faculty_id')->unique()->filter()->count();
@endphp

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- HERO --}}
    <section class="admin-hero">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="hero-kicker">Staff management</div>
                <h1 class="hero-title" style="font-size:1.8rem;">Lecturer Management</h1>
                <p class="hero-subtitle">View, verify, and manage all teaching staff.</p>
            </div>
            <a href="{{ route('admin.lecturers.create') }}" class="hero-cta">
                <i class="fas fa-user-plus"></i> Register Lecturer
            </a>
        </div>
    </section>

    {{-- STATS --}}
    <div class="stat-grid stat-grid-4" style="margin-bottom:1.5rem;">
        <div class="stat-card-pro">
            <div class="stat-icon-pro indigo"><i class="fas fa-users"></i></div>
            <div class="stat-value-pro">{{ $total }}</div>
            <div class="stat-label-pro">Total</div>
        </div>
        <div class="stat-card-pro">
            <div class="stat-icon-pro green"><i class="fas fa-check-circle"></i></div>
            <div class="stat-value-pro">{{ $verified }}</div>
            <div class="stat-label-pro">Verified</div>
        </div>
        <div class="stat-card-pro">
            <div class="stat-icon-pro gold"><i class="fas fa-clock"></i></div>
            <div class="stat-value-pro">{{ $pending }}</div>
            <div class="stat-label-pro">Pending</div>
        </div>
        <div class="stat-card-pro">
            <div class="stat-icon-pro rose"><i class="fas fa-university"></i></div>
            <div class="stat-value-pro">{{ $facCount }}</div>
            <div class="stat-label-pro">Faculties</div>
        </div>
    </div>

    {{-- TABLE PANEL --}}
    <div class="dash-panel">
        <div class="panel-header-pro">
            <h5><i class="fas fa-list me-2" style="color:#4f46e5;"></i>All Lecturers</h5>
            <div class="table-search" style="width:260px;">
                <i class="fas fa-search"></i>
                <form method="GET" style="margin:0;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search lecturers...">
                </form>
            </div>
        </div>

        {{-- Column headers --}}
        <div class="pro-table-row" style="grid-template-columns:2.5fr 1fr 1fr 1fr 60px; background:#f8fafc; font-size:0.68rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#94a3b8; border-bottom:1px solid #e2e8f0;">
            <span>Lecturer</span>
            <span>Staff ID</span>
            <span>Faculty</span>
            <span>Status</span>
            <span>Action</span>
        </div>

        @forelse($lecturers as $lecturer)
            <div class="pro-table-row" style="grid-template-columns:2.5fr 1fr 1fr 1fr 60px;">

                <div class="d-flex align-items-center gap-2">
                    <div class="pro-avatar">{{ strtoupper(substr($lecturer->name, 0, 2)) }}</div>
                    <div>
                        <div style="font-weight:600; font-size:0.85rem;">{{ $lecturer->name }}</div>
                        <small>{{ $lecturer->email }}</small>
                    </div>
                </div>

                <div style="font-size:0.83rem;">{{ $lecturer->staff_id }}</div>
                <div style="font-size:0.83rem;">{{ $lecturer->faculty?->code ?? '—' }}</div>

                <div>
                    <span class="pro-badge {{ $lecturer->is_verified ? 'success' : 'warning' }}">
                        {{ $lecturer->is_verified ? 'Verified' : 'Pending' }}
                    </span>
                </div>

                <div>
                    <a href="{{ route('admin.lecturer.edit', $lecturer->id) }}" class="row-action" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                </div>

            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <p style="font-weight:700; font-size:0.95rem; color:#0f172a; margin-bottom:4px;">No Lecturers Found</p>
                <p>Try a different search term or register a new lecturer.</p>
            </div>
        @endforelse
    </div>

</div>

@endsection