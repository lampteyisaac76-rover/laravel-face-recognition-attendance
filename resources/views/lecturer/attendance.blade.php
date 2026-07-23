@extends('layouts.dashboard')

@section('title', 'Attendance - ' . $course->code)

@section('content')
@php
    $studentPayload = $students->map(fn ($student) => [
        'id' => $student->id,
        'name' => $student->name,
        'student_id' => $student->index_number,
    ])->values();
@endphp

<div style="max-width:1440px; margin:0 auto; padding:0 0.5rem 2rem;">

    {{-- PAGE HEADER --}}
    <section style="background:linear-gradient(135deg, #0f172a 0%, #064e3b 50%, #b45309 100%); border-radius:16px; padding:2rem; color:#fff; box-shadow:0 12px 24px rgba(15,23,42,0.15); margin-bottom:1.5rem; position:relative; overflow:hidden;">
        <div style="position:absolute; right:-100px; bottom:-100px; width:300px; height:300px; border-radius:50%; background:rgba(255,255,255,0.05);"></div>
        <div style="position:relative; z-index:2;">
            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                <div>
                    <div style="color:rgba(255,255,255,0.7); font-size:0.75rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase;">Live attendance console</div>
                    <h1 style="margin:0.25rem 0 0.5rem; font-size:2rem; font-weight:900;">{{ $course->code }}</h1>
                    <p style="margin:0; color:rgba(255,255,255,0.85); max-width:600px; font-size:0.95rem;">
                        {{ $course->title }}
                    </p>
                </div>

                <div class="d-flex flex-wrap align-items-start gap-2">
                    <span style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); font-size:0.8rem; font-weight:800;">
                        <i class="fas fa-users"></i> {{ $students->count() }} students
                    </span>
                    <a href="{{ route('lecturer.attendance.history', $course->id) }}" style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#fff; font-size:0.8rem; font-weight:800; text-decoration:none; transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fas fa-history"></i> History
                    </a>
                    <a href="{{ route('lecturer.dashboard') }}" style="display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:999px; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.2); color:#fff; font-size:0.8rem; font-weight:800; text-decoration:none; transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                        <i class="fas fa-arrow-left"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="dash-panel mb-4">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <h2 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Face Scanner</h2>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#64748b;">Start a period, allow camera access, then scan students as they arrive.</p>
                    </div>
                    <span id="session-status" style="display:inline-block; padding:6px 14px; border-radius:999px; background:#fffbeb; color:#d97706; font-size:0.75rem; font-weight:800; white-space:nowrap;">
                        <i class="fas fa-clock me-1"></i> Waiting
                    </span>
                </div>

                <div class="p-4">
                    <div style="position:relative; width:100%; aspect-ratio:4/3; min-height:320px; background:#0f172a; border-radius:16px; overflow:hidden; margin-bottom:20px; box-shadow:inset 0 4px 20px rgba(0,0,0,0.5);">
                        <div id="camera-placeholder" style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center; color:rgba(255,255,255,0.8); background:linear-gradient(135deg, rgba(6,78,59,0.9), rgba(15,23,42,0.95)); z-index:2;">
                            <i class="fas fa-camera fa-3x mb-3"></i>
                            <h5 style="margin:0 0 8px; font-weight:800; color:#fff;">Camera is idle</h5>
                            <p style="margin:0; font-size:0.9rem;">Choose a session period and start attendance.</p>
                        </div>
                        <video id="webcam" autoplay muted playsinline style="position:absolute; inset:0; width:100%; height:100%; object-fit:contain; background:#0f172a; display:none; transform:scaleX(-1);"></video>
                        <canvas id="overlay" style="position:absolute; inset:0; width:100%; height:100%; display:none; z-index:3; pointer-events:none; transform:scaleX(-1);"></canvas>
                    </div>

                    <div id="face-match-result" style="display:none; padding:16px; border-radius:12px; margin-bottom:20px; font-size:0.85rem; font-weight:700;"></div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <select id="session-period" style="width:100%; height:44px; border:1px solid #cbd5e1; border-radius:10px; padding:0 14px; font-weight:700; color:#0f172a; background:#fff; outline:none;" onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                                <option value="morning">Morning session</option>
                                <option value="afternoon">Afternoon session</option>
                                <option value="evening">Evening session</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button id="btn-start-session" style="width:100%; height:44px; border:none; border-radius:10px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-weight:800; font-size:0.9rem; cursor:pointer; box-shadow:0 4px 12px rgba(16,185,129,0.2); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                                <i class="fas fa-play me-2"></i> Start Session
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button id="btn-stop-session" style="display:none; width:100%; height:44px; border:none; border-radius:10px; background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; font-weight:800; font-size:0.9rem; cursor:pointer; box-shadow:0 4px 12px rgba(239,68,68,0.2); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform=''">
                                <i class="fas fa-stop me-2"></i> End Session
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <div>
                        <h2 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Recent Scans</h2>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#64748b;">This list updates during the active class session.</p>
                    </div>
                </div>

                <div class="p-4">
                    <div id="recent-scans-list" style="max-height:560px; overflow-y:auto;">
                        <div style="text-align:center; padding:64px 20px; color:#94a3b8;">
                            <i class="fas fa-fingerprint fa-3x mb-3"></i>
                            <p style="font-weight:800; font-size:1.1rem; color:#0f172a; margin:0;">No scans yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 d-flex flex-column gap-4">
            <div class="dash-panel">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <div>
                        <h2 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Attendance Progress</h2>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#64748b;">Live class completion count.</p>
                    </div>
                </div>

                <div class="p-4">
                    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:12px; margin-bottom:20px;">
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:12px;">
                            <div style="font-size:0.7rem; font-weight:800; color:#64748b; text-transform:uppercase;">Present</div>
                            <div style="font-size:1.5rem; font-weight:900; color:#0f172a;"><span id="present-count">0</span></div>
                        </div>
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:12px;">
                            <div style="font-size:0.7rem; font-weight:800; color:#64748b; text-transform:uppercase;">Absent</div>
                            <div style="font-size:1.5rem; font-weight:900; color:#0f172a;"><span id="absent-count">{{ $students->count() }}</span></div>
                        </div>
                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:12px;">
                            <div style="font-size:0.7rem; font-weight:800; color:#64748b; text-transform:uppercase;">Total</div>
                            <div style="font-size:1.5rem; font-weight:900; color:#0f172a;">{{ $students->count() }}</div>
                        </div>
                    </div>

                    <div style="height:10px; background:#f1f5f9; border-radius:999px; overflow:hidden;">
                        <div id="attendance-progress" style="width:0%; height:100%; background:linear-gradient(90deg,#047857,#10b981); transition:width 0.3s ease;"></div>
                    </div>
                </div>
            </div>

            <div class="dash-panel flex-grow-1">
                <div class="panel-header-pro" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:1.25rem 1.5rem;">
                    <div>
                        <h2 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;">Class Roster</h2>
                        <p style="margin:4px 0 0; font-size:0.8rem; color:#64748b;">Students remain absent until scanned or manually marked.</p>
                    </div>
                </div>

                <div class="p-4 d-flex flex-column h-100">
                    <div style="position:relative; margin-bottom:16px;">
                        <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94a3b8;"></i>
                        <input type="text" id="roster-search" placeholder="Search name or index number..." 
                               style="width:100%; height:44px; border:1px solid #cbd5e1; border-radius:10px; padding:0 12px 0 36px; font-size:0.85rem; font-weight:700; outline:none;"
                               onfocus="this.style.borderColor='#6366f1'" onblur="this.style.borderColor='#cbd5e1'">
                    </div>

                    <div id="student-roster" style="max-height:560px; overflow-y:auto; flex:1;">
                        @forelse($students as $student)
                            <div class="student-row" data-id="{{ $student->id }}" data-search="{{ strtolower($student->name . ' ' . $student->index_number) }}"
                                 style="display:flex; align-items:center; justify-content:space-between; padding:12px; border-radius:12px; border:1px solid #e2e8f0; background:#fff; margin-bottom:8px;">
                                <div style="display:flex; align-items:center; gap:12px; overflow:hidden;">
                                    <div style="width:36px; height:36px; border-radius:50%; background:#f1f5f9; color:#0f172a; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.85rem; flex-shrink:0;">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div style="overflow:hidden;">
                                        <p style="margin:0; font-weight:800; color:#0f172a; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $student->name }}</p>
                                        <p style="margin:0; font-size:0.75rem; color:#64748b; font-weight:700;">{{ $student->index_number }}</p>
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span class="status-pill" style="display:inline-block; padding:4px 10px; border-radius:999px; background:#fee2e2; color:#dc2626; font-size:0.7rem; font-weight:800;">Absent</span>
                                    <button class="btn-manual-mark" data-id="{{ $student->id }}" title="Mark present manually"
                                            style="width:32px; height:32px; border-radius:8px; border:1px solid #bbf7d0; background:#f0fdf4; color:#16a34a; cursor:pointer; opacity:0; pointer-events:none; transition:opacity 0.2s;"
                                            onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div style="text-align:center; padding:32px 0; color:#94a3b8;">
                                <i class="fas fa-users-slash fa-2x mb-2"></i>
                                <p style="font-weight:800; color:#0f172a; margin:0;">No students enrolled</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .student-row.present { border-color: rgba(34,197,94,0.3) !important; background: #f0fdf4 !important; }
    .student-row.present .status-pill { background: #dcfce7 !important; color: #16a34a !important; }
    .student-row:hover .btn-manual-mark, .student-row:focus-within .btn-manual-mark { opacity: 1 !important; pointer-events: auto !important; }
    .student-row.present .btn-manual-mark { display: none !important; }
    .session-status.active { background: #dcfce7 !important; color: #16a34a !important; }
    .session-status.ended { background: #fee2e2 !important; color: #dc2626 !important; }
</style>
<script src="{{ asset('assets/js/face-api.min.js') }}"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';
    const startSessionUrl = "{{ route('lecturer.attendance.start', $course) }}";
    const endSessionUrl = "{{ route('lecturer.attendance.end') }}";
    const markAttendanceUrl = "{{ route('lecturer.attendance.mark') }}";
    const descriptorsUrl = "{{ route('lecturer.attendance.descriptors', $course) }}";
    
    // Safety Fallback: Normalizes index IDs dynamically (s.id or matching fallbacks)
    const rawStudentsData = @json($studentPayload);
    const studentsData = rawStudentsData.map(s => ({
        id: s.id || s.student_id || s.user_id,
        name: s.name,
        student_code: s.student_id
    }));

    const totalStudents = {{ $students->count() }};

    let currentSessionId = null;
    let isSessionActive = false;
    let faceMatcher = null;
    let stream = null;
    let detectionTimer = null;
    let presentStudents = new Set();

    const videoEl = document.getElementById('webcam');
    const canvasEl = document.getElementById('overlay');
    const placeholder = document.getElementById('camera-placeholder');
    const btnStart = document.getElementById('btn-start-session');
    const btnStop = document.getElementById('btn-stop-session');
    const periodSelect = document.getElementById('session-period');
    const statusBadge = document.getElementById('session-status');
    const resultBox = document.getElementById('face-match-result');
    const recentScansList = document.getElementById('recent-scans-list');
    const presentCountEl = document.getElementById('present-count');
    const absentCountEl = document.getElementById('absent-count');
    const progressEl = document.getElementById('attendance-progress');

    function setResult(type, message) {
        resultBox.style.display = 'block';
        resultBox.style.background = type === 'success' ? '#f0fdf4' : (type === 'warning' ? '#fffbeb' : (type === 'danger' ? '#fef2f2' : '#eff6ff'));
        resultBox.style.color = type === 'success' ? '#166534' : (type === 'warning' ? '#92400e' : (type === 'danger' ? '#991b1b' : '#1e40af'));
        resultBox.style.border = '1px solid ' + (type === 'success' ? '#bbf7d0' : (type === 'warning' ? '#fde68a' : (type === 'danger' ? '#fecaca' : '#bfdbfe')));
        resultBox.innerHTML = message;
    }

    function setStatus(mode, html) {
        statusBadge.className = mode ? 'session-status ' + mode : 'session-status';
        statusBadge.innerHTML = html;
    }

    function resetLiveSessionUi() {
        presentStudents = new Set();
        presentCountEl.textContent = '0';
        absentCountEl.textContent = totalStudents;
        progressEl.style.width = '0%';

        document.querySelectorAll('.student-row').forEach(row => {
            row.classList.remove('present');
            row.querySelector('.status-pill').textContent = 'Absent';
            const button = row.querySelector('.btn-manual-mark');
            if (button) button.style.display = '';
        });

        recentScansList.innerHTML = `
            <div style="text-align:center; padding:64px 20px; color:#94a3b8;">
                <i class="fas fa-fingerprint fa-3x mb-3"></i>
                <p style="font-weight:800; font-size:1.1rem; color:#0f172a; margin:0;">No scans yet</p>
            </div>
        `;
    }

    function syncCanvasToVideo() {
        const width = videoEl.videoWidth;
        const height = videoEl.videoHeight;

        if (!width || !height) return false;

        canvasEl.width = width;
        canvasEl.height = height;
        canvasEl.style.width = videoEl.clientWidth + 'px';
        canvasEl.style.height = videoEl.clientHeight + 'px';

        return true;
    }

    async function initFaceAPI() {
        setResult('info', '<i class="fas fa-spinner fa-spin me-2"></i> Loading face recognition models...');

        try {
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri('/models'),
                faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
                faceapi.nets.faceRecognitionNet.loadFromUri('/models')
            ]);

            const response = await fetch(descriptorsUrl, {
                headers: { 'Accept': 'application/json' }
            });

            const faceStudents = await response.json();
            const labeledDescriptors = [];

            for (const student of faceStudents) {
                try {
                    const image = await faceapi.fetchImage(student.image_url);
                    const detection = await faceapi
                        .detectSingleFace(image, new faceapi.TinyFaceDetectorOptions())
                        .withFaceLandmarks()
                        .withFaceDescriptor();

                    if (detection) {
                        const calculatedId = student.id || student.student_id || student.user_id;
                        labeledDescriptors.push(
                            new faceapi.LabeledFaceDescriptors(String(calculatedId), [detection.descriptor])
                        );
                    }
                } catch (error) {
                    console.warn('Face image skipped:', student.name, error);
                }
            }

            if (labeledDescriptors.length > 0) {
                faceMatcher = new faceapi.FaceMatcher(labeledDescriptors, 0.48);
                setResult('success', `<i class="fas fa-check-circle me-2"></i> Ready. ${labeledDescriptors.length} student face profile(s) loaded.`);
            } else {
                setResult('warning', '<i class="fas fa-exclamation-triangle me-2"></i> No usable face profiles found. Manual marking is available.');
            }
        } catch (error) {
            console.error(error);
            setResult('danger', '<i class="fas fa-times-circle me-2"></i> Face recognition could not load. Manual marking is available.');
        }
    }

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            });

            videoEl.srcObject = stream;
            placeholder.style.display = 'none';
            videoEl.style.display = 'block';
            canvasEl.style.display = 'block';

            return new Promise(resolve => {
                videoEl.onloadedmetadata = () => {
                    videoEl.play();
                    setTimeout(() => resolve(syncCanvasToVideo()), 250);
                };
            });
        } catch (error) {
            console.error(error);
            alert('Could not access the camera. Please allow camera permission and try again.');
            return false;
        }
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }

        videoEl.style.display = 'none';
        canvasEl.style.display = 'none';
        placeholder.style.display = 'flex';

        const context = canvasEl.getContext('2d');
        context.clearRect(0, 0, canvasEl.width, canvasEl.height);
    }

    btnStart.addEventListener('click', async () => {
        btnStart.disabled = true;
        btnStart.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Starting...';

        try {
            resetLiveSessionUi();

            const response = await fetch(startSessionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    period: periodSelect.value
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Could not start attendance session.');
            }

            currentSessionId = data.session_id;
            isSessionActive = true;

            periodSelect.disabled = true;

            btnStart.style.display = 'none';
            btnStop.style.display = 'block';

            setStatus(
                'active',
                '<i class="fas fa-circle me-1"></i> Session Active'
            );

            await startCamera();
            startDetectionLoop();

            setResult(
                'success',
                '<i class="fas fa-check-circle me-2"></i> Session started successfully. Face recognition is now active.'
            );
        } catch (error) {
            console.error(error);
            alert(error.message || 'Session could not start.');
        } finally {
            btnStart.disabled = false;
            btnStart.innerHTML = '<i class="fas fa-play me-2"></i> Start Session';
        }
    });

    btnStop.addEventListener('click', async () => {
        if (!currentSessionId) return;

        btnStop.disabled = true;
        btnStop.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Ending...';

        try {
            await fetch(endSessionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: currentSessionId
                })
            });
        } catch (error) {
            console.error(error);
        }

        isSessionActive = false;
        clearInterval(detectionTimer);
        stopCamera();

        btnStop.style.display = 'none';
        btnStart.style.display = 'block';
        btnStop.disabled = false;
        btnStop.innerHTML = '<i class="fas fa-stop me-2"></i> End Session';
        periodSelect.disabled = false;

        setStatus('ended', '<i class="fas fa-stop-circle me-1"></i> Session Ended');
        setResult('info', '<i class="fas fa-check me-2"></i> Session ended. This report is saved in attendance history.');
    });

    function startDetectionLoop() {
        clearInterval(detectionTimer);

        detectionTimer = setInterval(async () => {
            if (!isSessionActive || !faceMatcher || videoEl.paused || videoEl.ended) return;
            if (!syncCanvasToVideo()) return;

            const displaySize = {
                width: videoEl.videoWidth,
                height: videoEl.videoHeight
            };

            const detections = await faceapi
                .detectAllFaces(videoEl, new faceapi.TinyFaceDetectorOptions())
                .withFaceLandmarks()
                .withFaceDescriptors();

            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            const context = canvasEl.getContext('2d');
            context.clearRect(0, 0, canvasEl.width, canvasEl.height);

            const results = resizedDetections.map(d => faceMatcher.findBestMatch(d.descriptor));

            results.forEach((result, i) => {
                const box = resizedDetections[i].detection.box;

                let drawColor = '#dc2626'; // red
                let labelText = 'Unknown';

                if (result.label !== 'unknown') {
                    drawColor = '#16a34a'; // green
                    
                    const st = studentsData.find(s => String(s.id) === String(result.label));
                    if (st && st.id) { // Robust safety validation guard
                        labelText = st.name;
                        markStudentPresent(st.id, st.name, 'realtime');
                    }
                }

                const drawBox = new faceapi.draw.DrawBox(box, {
                    label: labelText,
                    lineWidth: 3,
                    boxColor: drawColor,
                    drawLabelOptions: {
                        anchorPosition: 'TOP_LEFT',
                        backgroundColor: drawColor
                    }
                });
                drawBox.draw(canvasEl);
            });

        }, 800); 
    }

    async function markStudentPresent(studentId, studentName, method = 'manual') {
        if (!isSessionActive || !currentSessionId || !studentId) return;
        if (presentStudents.has(Number(studentId))) return;

        presentStudents.add(Number(studentId));

        console.log({

    session: currentSessionId,

    student: studentId,

    method: method

});
        try {
            const response = await fetch(markAttendanceUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    session_id: currentSessionId,
                    student_id: studentId,
                    method: method
                })
            });

           const data = await response.json();

console.log("Attendance Response:", data);

if (!response.ok) {

    presentStudents.delete(Number(studentId));

    console.error(data);

    alert(data.message || "Attendance failed.");

    return;

}

if (data.success) {

    updateStudentRowUI(studentId, true);

    addRecentScanUI(studentName, method);

    updateStatsUI();

} else {

    presentStudents.delete(Number(studentId));

}
        } catch (error) {
            console.error(error);
            presentStudents.delete(Number(studentId));
        }
    }

    function updateStudentRowUI(studentId, isPresent) {
        const row = document.querySelector(`.student-row[data-id="${studentId}"]`);
        if (!row) return;

        if (isPresent) {
            row.classList.add('present');
            row.querySelector('.status-pill').textContent = 'Present';
        }
    }

    function addRecentScanUI(studentName, method) {
        if (recentScansList.querySelector('.empty-state') || recentScansList.querySelector('.fa-fingerprint')) {
            recentScansList.innerHTML = '';
        }

        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const icon = method === 'realtime' ? '<i class="fas fa-camera" style="color:#10b981;"></i>' : '<i class="fas fa-user-check" style="color:#2563eb;"></i>';

        const itemHtml = `
            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px; border-radius:12px; border:1px solid #e2e8f0; background:#f8fafc; margin-bottom:8px; animation:fadeIn 0.3s ease;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:50%; background:#fff; border:1px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                        ${icon}
                    </div>
                    <div>
                        <p style="margin:0; font-size:0.85rem; font-weight:800; color:#0f172a;">${studentName}</p>
                        <p style="margin:0; font-size:0.75rem; color:#64748b;">Marked at ${time}</p>
                    </div>
                </div>
            </div>
        `;
        
        recentScansList.insertAdjacentHTML('afterbegin', itemHtml);
    }

    function updateStatsUI() {
        const count = presentStudents.size;
        presentCountEl.textContent = count;
        absentCountEl.textContent = totalStudents - count;

        const percent = totalStudents > 0 ? (count / totalStudents) * 100 : 0;
        progressEl.style.width = `${percent}%`;
    }

    // Manual marking
    document.querySelectorAll('.btn-manual-mark').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const sid = e.currentTarget.dataset.id;
            const sname = e.currentTarget.closest('.student-row').querySelector('p').textContent.trim();
            
            if (!isSessionActive) {
                alert('Please start a session first.');
                return;
            }

            markStudentPresent(sid, sname, 'manual');
        });
    });

    // Roster search
    document.getElementById('roster-search')?.addEventListener('input', (e) => {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.student-row').forEach(row => {
            const text = row.dataset.search;
            row.style.display = text.includes(q) ? 'flex' : 'none';
        });
    });

    // Initialize FaceAPI on page load
    window.addEventListener('DOMContentLoaded', initFaceAPI);

    const style = document.createElement('style');
    style.innerHTML = `@keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }`;
    document.head.appendChild(style);

</script>
@endpush
@endsection