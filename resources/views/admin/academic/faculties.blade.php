@extends('layouts.dashboard')

@section('title', 'Faculties')

@section('content')
@php
    $facultyCollection = collect($faculties);
    $totalFaculties = $facultyCollection->count();
    $totalPrograms = $facultyCollection->sum('programs_count');
    $largestFaculty = $facultyCollection->sortByDesc('programs_count')->first();
@endphp

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    <div class="admin-hero">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <div>
                <div class="hero-kicker">Academic structure</div>
                <h1 class="hero-title">Faculties & Programs</h1>
                <p class="hero-subtitle">Select a faculty to view programs, levels, courses, lecturers, and enrolled students.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="hero-cta">
                <i class="fas fa-arrow-left"></i>
                Dashboard
            </a>
        </div>
    </div>

    <div class="stat-grid stat-grid-3">
        <div class="stat-card-pro">
            <div class="stat-icon-pro gold"><i class="fas fa-university"></i></div>
            <div class="stat-value-pro">{{ $totalFaculties }}</div>
            <div class="stat-label-pro">Faculties</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro teal"><i class="fas fa-layer-group"></i></div>
            <div class="stat-value-pro">{{ $totalPrograms }}</div>
            <div class="stat-label-pro">Programs</div>
        </div>

        <div class="stat-card-pro">
            <div class="stat-icon-pro indigo"><i class="fas fa-chart-simple"></i></div>
            <div class="stat-value-pro">{{ $largestFaculty?->programs_count ?? 0 }}</div>
            <div class="stat-label-pro">Largest Faculty Programs</div>
        </div>
    </div>

    <div class="dash-panel">
        <div class="panel-header-pro flex-column flex-md-row align-items-md-center gap-3">
            <div>
                <h5><i class="fas fa-sitemap me-2" style="color:#154734;"></i>Faculty Directory</h5>
                <p class="mb-0 mt-1" style="font-size:0.78rem; color:#64748b;">
                    Browse the academic hierarchy by faculty.
                </p>
            </div>

            <div class="table-search" style="width:min(360px,100%);">
                <i class="fas fa-search"></i>
                <input type="text" id="faculty-search" placeholder="Search faculties">
            </div>
        </div>

        <div class="p-4">
            <div class="row g-4" id="faculty-grid">
                @forelse($facultyCollection as $faculty)
                    <div class="col-md-6 col-xl-4 faculty-card-wrap"
                         data-search="{{ strtolower($faculty->name . ' ' . $faculty->code) }}">
                        <a href="{{ route('admin.academic.faculty', $faculty->id) }}" class="text-decoration-none">
                            <div class="explorer-card h-100">
                                <div class="ec-top-bar amber"></div>
                                <div class="ec-body">
                                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                        <div class="ec-icon amber">
                                            <i class="fas fa-university"></i>
                                        </div>

                                        <span class="ec-code-badge">{{ $faculty->code }}</span>
                                    </div>

                                    <h6 class="ec-title mb-2">{{ $faculty->name }}</h6>
                                    <p class="mb-0" style="font-size:0.78rem; color:#64748b; line-height:1.5;">
                                        Open this faculty to manage its programs and course hierarchy.
                                    </p>

                                    <div class="ec-footer">
                                        <span>
                                            <i class="fas fa-layer-group me-1"></i>
                                            {{ $faculty->programs_count }} {{ $faculty->programs_count == 1 ? 'program' : 'programs' }}
                                        </span>
                                        <span class="arrow">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="fas fa-university"></i>
                            <p style="font-weight:800; font-size:0.95rem; color:#0f172a; margin-bottom:4px;">No Faculties Found</p>
                            <p>Faculties need to be seeded into the database.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('faculty-search')?.addEventListener('input', event => {
        const query = event.target.value.toLowerCase();

        document.querySelectorAll('.faculty-card-wrap').forEach(card => {
            card.style.display = card.dataset.search.includes(query) ? 'block' : 'none';
        });
    });
</script>
@endpush

@endsection
