<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSession;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;

use App\Exports\AttendanceExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{
    // ─────────────────────────────────────────────
    // Show the attendance page for a course
    // ─────────────────────────────────────────────

    public function index(Course $course)
    {
        // Ensure lecturer is assigned to this course
        if (!Auth::user()->courses->contains($course->id)) {
            abort(403, 'You are not assigned to this course.');
        }

        $students = $course->students()->get();

        // Load existing sessions for this course today
        $todaySessions = AttendanceSession::where('course_id', $course->id)
            ->whereDate('session_date', today())
            ->with('attendances.student')
            ->orderBy('period')
            ->get();

        return view('lecturer.attendance', compact('course', 'students', 'todaySessions'));
    }

    // ─────────────────────────────────────────────
    // Start a new attendance session
    // ─────────────────────────────────────────────

    public function startSession(Request $request, Course $course)
    {
        $request->validate([
            'period' => 'required|in:morning,afternoon,evening',
        ]);

        // Check if a session already exists for this course + date + period
        $existing = AttendanceSession::where('course_id', $course->id)
            ->whereDate('session_date', today())
            ->where('period', $request->period)
            ->first();

        if ($existing && $existing->status === 'active') {
            // Auto-close the stale session so a fresh one can start
            $existing->update([
                'ended_at' => now(),
                'status'   => 'ended',
            ]);
        }

        // Create a new session
        $session = AttendanceSession::create([
            'course_id'    => $course->id,
            'user_id'      => Auth::id(),
            'session_date' => today(),
            'period'       => $request->period,
            'started_at'   => now(),
            'status'       => 'active',
        ]);

        // Pre-populate all enrolled students as absent
        $students = $course->students;
        foreach ($students as $student) {
            Attendance::create([
                'attendance_session_id' => $session->id,
                'course_id'             => $course->id,
                'student_id'            => $student->id,
                'status'                => 'absent',
                'method'                => null,
                'marked_at'             => null,
            ]);
        }

        return response()->json([
            'success'    => true,
            'session_id' => $session->id,
            'message'    => ucfirst($request->period) . ' session started.',
        ]);
    }

    // ─────────────────────────────────────────────
    // Mark a student as present (called by face-api.js)
    // ─────────────────────────────────────────────

    public function markPresent(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
            'student_id' => 'required|exists:students,id',
            'method'     => 'required|in:realtime,snapshot,manual',
        ]);

        $attendance = Attendance::where('attendance_session_id', $request->session_id)
            ->where('student_id', $request->student_id)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance record not found.',
            ], 404);
        }

        // Only update if not already marked present
        if ($attendance->status === 'present') {
            return response()->json([
                'success' => true,
                'message' => 'Already marked present.',
                'already' => true,
            ]);
        }

        $attendance->update([
            'status'    => 'present',
            'method'    => $request->method,
            'marked_at' => now(),
        ]);

        $student = Student::find($request->student_id);

        return response()->json([
            'success'      => true,
            'message'      => $student->name . ' marked present.',
            'student_id'   => $student->id,
            'student_name' => $student->name,
            'marked_at'    => now()->format('H:i:s'),
        ]);
    }

    // ─────────────────────────────────────────────
    // End a session
    // ─────────────────────────────────────────────

    public function endSession(Request $request)
    {
        $request->validate([
            'session_id' => 'required|exists:attendance_sessions,id',
        ]);

        $session = AttendanceSession::findOrFail($request->session_id);

        $session->update([
            'ended_at' => now(),
            'status'   => 'ended',
        ]);

        $presentCount = $session->presentStudents()->count();
        $totalCount   = $session->attendances()->count();

        return response()->json([
            'success' => true,
            'message' => 'Session ended. '
                       . $presentCount . ' of ' . $totalCount
                       . ' students marked present.',
            'present' => $presentCount,
            'total'   => $totalCount,
        ]);
    }

    // ─────────────────────────────────────────────
    // Load student face descriptors for face-api.js
    // ─────────────────────────────────────────────

    public function getFaceDescriptors(Course $course)
{
    try {
        $students = $course->students()
            ->whereNotNull('students.face_image_path')
            ->get([
                'students.id',
                'students.index_number',
                'students.name',
                'students.face_image_path',
            ]);

        $data = $students->map(function ($student) {
            return [
                'id'        => $student->id,
                'name'      => $student->name,
                'index'     => $student->index_number,
                'image_url' => asset('storage/' . $student->face_image_path),
            ];
        });

        return response()->json($data);

    } catch (\Exception $e) {
        return response()->json([
            'error'   => true,
            'message' => $e->getMessage(),
        ], 500);
    }
}

public function deleteSession(AttendanceSession $session)
{
    // Make sure only the lecturer who created it can delete it
    if ($session->user_id !== Auth::id()) {
        abort(403, 'You are not authorised to delete this session.');
    }

    $courseId = $session->course_id;

    // This also deletes all attendance records via cascade
    $session->delete();

    return redirect()
        ->route('lecturer.attendance.history', $courseId)
        ->with('success', 'Attendance session deleted successfully.');
}

    // ─────────────────────────────────────────────
    // Export attendance to Excel
    // ─────────────────────────────────────────────

    public function export(AttendanceSession $session)
    {
        $filename = 'attendance_'
                  . $session->course->code . '_'
                  . $session->session_date->format('Y-m-d') . '_'
                  . $session->period
                  . '.xlsx';

        return Excel::download(new AttendanceExport($session), $filename);
    }

    // ─────────────────────────────────────────────
    // Session history for a course
    // ─────────────────────────────────────────────

    public function history(Course $course, Request $request)
    {
        if (!Auth::user()->courses->contains($course->id)) {
            abort(403);
        }

        $sessions = AttendanceSession::where('course_id', $course->id)
            ->with(['attendances.student'])
            ->orderByDesc('session_date')
            ->orderByDesc('started_at')
            ->paginate(10);

        $selectedSession = null;
        $attendances = collect();

        if ($request->filled('session_id')) {
            $selectedSession = AttendanceSession::where('course_id', $course->id)
                ->with(['attendances.student'])
                ->find($request->input('session_id'));

            if ($selectedSession) {
                $attendances = $selectedSession->attendances;
            }
        }

        return view('lecturer.attendance-history', compact('course', 'sessions', 'selectedSession', 'attendances'));
    }
}