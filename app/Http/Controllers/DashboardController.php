<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalLecturers = User::where('role', 'lecturer')
                              ->where('is_verified', true)
                              ->count();

        $pendingLecturers = User::where('role', 'lecturer')
                                ->where('is_verified', false)
                                ->count();

        $totalStudents = Student::count();

        $totalCourses = Course::count();

        $totalFaculties = Faculty::count();

        $totalPrograms = Program::count();

        $recentLecturers = User::where('role', 'lecturer')
                               ->with(['faculty', 'courses'])
                               ->latest()
                               ->take(5)
                               ->get();

        // Load the full academic hierarchy for the explorer
        $faculties = Faculty::with(['programs.courses.lecturers', 'programs.courses.students'])
                            ->withCount('programs')
                            ->get();

        // Build hierarchy JSON for the JavaScript explorer
        $academicData = $faculties->map(function ($faculty) {
            $allCourses = $faculty->programs->flatMap->courses;
            $allStudents = $allCourses->flatMap->students->unique('id');

            return [
                'id'             => $faculty->id,
                'name'           => $faculty->name,
                'code'           => $faculty->code,
                'programs_count' => $faculty->programs_count,
                'total_courses'  => $allCourses->count(),
                'total_students' => $allStudents->count(),
                'programs'       => $faculty->programs->map(function ($program) {
                    // Group courses by level
                    $coursesByLevel = $program->courses->groupBy('level');
                    $levels = $coursesByLevel->keys()->sort()->values();
                    $programStudents = $program->courses->flatMap->students->unique('id');

                    return [
                        'id'              => $program->id,
                        'name'            => $program->name,
                        'code'            => $program->code,
                        'courses_count'   => $program->courses->count(),
                        'students_count'  => $programStudents->count(),
                        'levels'          => $levels,
                        'courses'         => $program->courses->map(function ($course) {
                            $total = $course->students->count();
                            $withFace = $course->students->filter(fn($s) => !empty($s->face_image_path))->count();
                            $coverage = $total > 0 ? round(($withFace / $total) * 100) : 0;

                            return [
                                'id'              => $course->id,
                                'title'           => $course->title,
                                'code'            => $course->code,
                                'credits'         => $course->credits,
                                'level'           => $course->level,
                                'semester'        => $course->semester,
                                'students_count'  => $total,
                                'lecturers_count' => $course->lecturers->count(),
                                'face_count'      => $withFace,
                                'face_coverage'   => $coverage,
                            ];
                        }),
                    ];
                }),
            ];
        });

        // Recent activity: latest attendance sessions
        $recentActivity = \App\Models\AttendanceSession::with(['course', 'lecturer'])
            ->latest()
            ->take(8)
            ->get()
            ->map(function ($session) {
                return [
                    'type'    => 'attendance',
                    'title'   => $session->course->code . ' — ' . ucfirst($session->period) . ' session',
                    'detail'  => 'by ' . ($session->lecturer->name ?? 'Unknown'),
                    'time'    => $session->created_at->diffForHumans(),
                    'status'  => $session->status,
                ];
            });

        return view('admin.dashboard', compact(
            'totalLecturers',
            'pendingLecturers',
            'totalStudents',
            'totalCourses',
            'totalFaculties',
            'totalPrograms',
            'recentLecturers',
            'faculties',
            'academicData',
            'recentActivity'
        ));
    }

    public function lecturer()
    {
        $lecturer        = auth()->user();
        $assignedCourses = $lecturer->courses()->withCount('students')->get();
        
        // Fetch real attendance sessions as "Recent Activity"
        $recentSessions = \App\Models\AttendanceSession::where('user_id', $lecturer->id)
            ->with('course')
            ->latest()
            ->take(5)
            ->get();

        return view('lecturer.dashboard', compact('assignedCourses', 'recentSessions'));
    }
}