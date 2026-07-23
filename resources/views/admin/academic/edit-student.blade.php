@extends('layouts.dashboard')

@section('title', 'Edit Student')

@section('content')

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <ul class="gctu-breadcrumbs">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.program', $student->program_id) }}">{{ $student->program->name }}</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li><a href="{{ route('admin.academic.program.levels', $student->program_id) }}">Roster</a></li>
                <li class="bc-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="bc-active">Edit Student</li>
            </ul>
            <h1 style="font-size:1.55rem; font-weight:800; color:#0f172a; margin:0 0 4px;">Edit Student</h1>
            <p style="font-size:0.82rem; color:#64748b; margin:0;">Update profile details for {{ $student->name }}.</p>
        </div>
        <div>
            <a href="{{ route('admin.academic.program.levels', $student->program_id) }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:10px; border:1px solid #e2e8f0; background:#fff; color:#0f172a; font-size:0.82rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#fff'">
                <i class="fas fa-arrow-left"></i> Back to Roster
            </a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <h5 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">
                        <i class="fas fa-user-edit me-2" style="color:#f59e0b;"></i>Student Information Update
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

                    <form action="{{ route('admin.student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <h6 style="font-size:0.8rem; font-weight:800; color:#475569; text-transform:uppercase; letter-spacing:0.06em; margin:0 0 16px; padding-bottom:8px; border-bottom:1px solid #e2e8f0;">
                            <i class="fas fa-user me-2 text-primary"></i>Basic Info
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $student->name) }}" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; transition:border-color 0.2s;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="index_number" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Index / Student ID <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="index_number" name="index_number" value="{{ old('index_number', $student->index_number) }}" required
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="email" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Email
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}"
                                       style="width:100%; height:44px; padding:0 14px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none;"
                                       onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                            </div>
                            <div class="col-md-3 mb-4">
                                <label for="program_id" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Program <span class="text-danger">*</span>
                                </label>
                                <select id="program_id" name="program_id" required
                                        style="width:100%; height:44px; padding:0 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                    @php $groupedPrograms = $programs->groupBy(fn($p) => $p->faculty->name ?? 'Other'); @endphp
                                    @foreach($groupedPrograms as $facultyName => $facultyPrograms)
                                        <optgroup label="{{ $facultyName }}">
                                            @foreach($facultyPrograms as $program)
                                                <option value="{{ $program->id }}"
                                                    {{ (int) old('program_id', $student->program_id) === $program->id ? 'selected' : '' }}>
                                                    {{ $program->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label for="level" style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                    Level <span class="text-danger">*</span>
                                </label>
                                <select id="level" name="level" required
                                        style="width:100%; height:44px; padding:0 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:0.85rem; color:#0f172a; outline:none; background:#fff;"
                                        onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                    @foreach([100, 200, 300, 400] as $lvl)
                                        <option value="{{ $lvl }}" {{ (int) old('level', $student->level) === $lvl ? 'selected' : '' }}>
                                            Level {{ $lvl }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                Student Photo
                            </label>
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px;">
                                <div class="d-flex align-items-center gap-4 flex-wrap">
                                    <div style="width:96px; height:96px; border-radius:50%; overflow:hidden; background:#e2e8f0; border:2px solid #cbd5e1; flex-shrink:0;">
                                        <img id="photo-preview"
                                             src="{{ $student->face_image_path ? asset('storage/'.$student->face_image_path) : 'data:image/svg+xml;utf8,'.rawurlencode('<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 96 96\'><rect width=\'96\' height=\'96\' fill=\'%23e2e8f0\'/><circle cx=\'48\' cy=\'38\' r=\'18\' fill=\'%2394a3b8\'/><path d=\'M16 88c0-17.7 14.3-32 32-32s32 14.3 32 32\' fill=\'%2394a3b8\'/></svg>') }}"
                                             alt="Student photo"
                                             style="width:100%; height:100%; object-fit:cover;">
                                    </div>

                                    <div style="flex:1; min-width:220px;">
                                        <div class="d-flex gap-2 flex-wrap">
                                            <button type="button" onclick="document.getElementById('face_image_input').click();"
                                                    style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; border:1px solid #cbd5e1; background:#fff; color:#0f172a; font-size:0.78rem; font-weight:700; cursor:pointer;">
                                                <i class="fas fa-upload"></i> Upload Photo
                                            </button>
                                            <button type="button" id="open-camera-btn" onclick="openCamera();"
                                                    style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; border:1px solid #cbd5e1; background:#fff; color:#0f172a; font-size:0.78rem; font-weight:700; cursor:pointer;">
                                                <i class="fas fa-camera"></i> Take Photo
                                            </button>
                                            <button type="button" id="clear-photo-btn" onclick="clearPhoto();" style="display:none; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; border:1px solid #fecaca; background:#fff; color:#dc2626; font-size:0.78rem; font-weight:700; cursor:pointer;">
                                                <i class="fas fa-times"></i> Remove Selected
                                            </button>
                                        </div>
                                        <p id="photo-filename" style="margin:8px 0 0; font-size:0.75rem; color:#64748b;"></p>
                                        <input type="file" id="face_image_input" name="face_image" accept="image/*" style="display:none;" onchange="handleFileSelect(event)">
                                    </div>
                                </div>

                                {{-- Inline camera capture panel --}}
                                <div id="camera-panel" style="display:none; margin-top:16px; border-top:1px solid #e2e8f0; padding-top:16px;">
                                    <video id="camera-video" autoplay playsinline
                                           style="width:100%; max-width:360px; border-radius:12px; background:#000; display:block;"></video>
                                    <canvas id="camera-canvas" style="display:none;"></canvas>
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" onclick="capturePhoto();"
                                                style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; border:none; background:linear-gradient(135deg,#4f46e5,#2563eb); color:#fff; font-size:0.78rem; font-weight:700; cursor:pointer;">
                                            <i class="fas fa-circle"></i> Capture
                                        </button>
                                        <button type="button" onclick="closeCamera();"
                                                style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; border:1px solid #cbd5e1; background:#fff; color:#0f172a; font-size:0.78rem; font-weight:700; cursor:pointer;">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label style="display:block; font-size:0.75rem; font-weight:700; color:#0f172a; margin-bottom:6px;">
                                Face Data Status
                            </label>
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px;">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="width:40px; height:40px; border-radius:50%; display:grid; place-items:center; background:{{ $student->face_descriptor ? '#dcfce7' : '#fee2e2' }}; border:1px solid {{ $student->face_descriptor ? '#bbf7d0' : '#fecaca' }}; flex-shrink:0;">
                                        <i class="fas {{ $student->face_descriptor ? 'fa-check' : 'fa-times' }}" style="color:{{ $student->face_descriptor ? '#16a34a' : '#ef4444' }};"></i>
                                    </div>
                                    <div>
                                        <p style="margin:0 0 2px; font-weight:700; color:#0f172a; font-size:0.9rem;">
                                            {{ $student->face_descriptor ? 'Face Data Registered' : 'No Face Data' }}
                                        </p>
                                        <p style="margin:0; font-size:0.75rem; color:#64748b;">
                                            {{ $student->face_descriptor ? 'Ready for biometric attendance.' : 'Needs to be captured during next session.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mt-5 pt-4 border-top gap-3" style="border-color:#e2e8f0;">
                            <div>
                                <button type="button"
                                        onclick="if(confirm('Are you sure you want to completely remove this student?')) document.getElementById('delete-form').submit();"
                                        style="display:inline-flex; align-items:center; gap:6px; padding:10px 20px; border-radius:999px; border:1px solid #fecaca; background:#fff; color:#dc2626; font-size:0.85rem; font-weight:700; cursor:pointer; transition:all 0.2s;"
                                        onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='#fff'">
                                    <i class="fas fa-trash-alt"></i> Remove Student
                                </button>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.academic.program.levels', $student->program_id) }}"
                                   style="display:inline-flex; align-items:center; padding:10px 24px; border-radius:999px; border:1px solid #e2e8f0; background:#f8fafc; color:#475569; font-size:0.85rem; font-weight:700; text-decoration:none; transition:background 0.2s;"
                                   onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                    Cancel
                                </a>
                                <button type="submit"
                                        style="display:inline-flex; align-items:center; gap:8px; padding:10px 28px; border-radius:999px; border:none; background:linear-gradient(135deg,#4f46e5,#2563eb); color:#fff; font-size:0.85rem; font-weight:700; cursor:pointer; box-shadow:0 6px 16px rgba(79,70,229,0.25); transition:transform 0.2s, box-shadow 0.2s;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(79,70,229,0.3)'"
                                        onmouseout="this.style.transform=''; this.style.boxShadow='0 6px 16px rgba(79,70,229,0.25)'">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </form>

                    <form id="delete-form" action="{{ route('admin.student.delete', $student->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    let cameraStream = null;
    const originalPhotoSrc = document.getElementById('photo-preview').src;

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;
        showPreview(file);
    }

    function showPreview(file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('photo-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);
        document.getElementById('photo-filename').textContent = file.name;
        document.getElementById('clear-photo-btn').style.display = 'inline-flex';
    }

    function clearPhoto() {
        const input = document.getElementById('face_image_input');
        input.value = '';
        document.getElementById('photo-preview').src = originalPhotoSrc;
        document.getElementById('photo-filename').textContent = '';
        document.getElementById('clear-photo-btn').style.display = 'none';
    }

    async function openCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Camera access is not supported in this browser.');
            return;
        }

        document.getElementById('camera-panel').style.display = 'block';

        try {
            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false
            });
            document.getElementById('camera-video').srcObject = cameraStream;
        } catch (err) {
            alert('Unable to access camera: ' + err.message);
            document.getElementById('camera-panel').style.display = 'none';
        }
    }

    function closeCamera() {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
            cameraStream = null;
        }
        document.getElementById('camera-panel').style.display = 'none';
    }

    function capturePhoto() {
        const video = document.getElementById('camera-video');
        const canvas = document.getElementById('camera-canvas');

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(blob => {
            const file = new File([blob], 'capture-' + Date.now() + '.jpg', { type: 'image/jpeg' });

            // Push the captured frame into the same file input the form submits
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('face_image_input').files = dataTransfer.files;

            showPreview(file);
            closeCamera();
        }, 'image/jpeg', 0.9);
    }
</script>

@endsection