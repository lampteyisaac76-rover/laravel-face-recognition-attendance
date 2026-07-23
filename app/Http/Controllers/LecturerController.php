<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = User::where('role', 'lecturer')
                         ->with(['faculty', 'courses'])
                         ->get();

        return view('admin.lecturers.index', compact('lecturers'));
    }

    public function create()
    {
        $faculties = Faculty::all();
        $courses   = Course::with('program.faculty')
                           ->orderBy('level')
                           ->get();

        return view('admin.lecturers.create', compact('faculties', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'staff_id'     => 'required|string|max:20|unique:users,staff_id',
            'phone_number' => 'required|string|max:15',
            'faculty_id'   => 'required|exists:faculties,id',
            'course_ids'   => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $lecturer = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'staff_id'     => $request->staff_id,
            'phone_number' => $request->phone_number,
            'faculty_id'   => $request->faculty_id,
            'role'         => 'lecturer',
            'is_verified'  => false,
            'password'     => null,
        ]);

        if ($request->filled('course_ids')) {
            $lecturer->courses()->sync($request->course_ids);
        }

        return redirect()->route('admin.lecturers')
            ->with('success', $request->name .
                ' has been registered successfully.');
    }

    public function edit(User $lecturer)
    {
        $faculties = Faculty::all();
        $courses   = Course::with('program.faculty')
                           ->orderBy('level')
                           ->get();

        $assignedCourseIds = $lecturer->courses->pluck('id')->toArray();

        return view('admin.lecturers.edit',
            compact('lecturer', 'faculties', 'courses', 'assignedCourseIds'));
    }

    public function update(Request $request, User $lecturer)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $lecturer->id,
            'staff_id'     => 'required|string|max:20|unique:users,staff_id,' . $lecturer->id,
            'phone_number' => 'required|string|max:15',
            'faculty_id'   => 'required|exists:faculties,id',
            'course_ids'   => 'nullable|array',
            'course_ids.*' => 'exists:courses,id',
        ]);

        $lecturer->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'staff_id'     => $request->staff_id,
            'phone_number' => $request->phone_number,
            'faculty_id'   => $request->faculty_id,
        ]);

        // Sync assigned courses
        $lecturer->courses()->sync(
            $request->filled('course_ids') ? $request->course_ids : []
        );

        return redirect()->route('admin.lecturers')
            ->with('success', $lecturer->name .
                ' updated successfully.');
    }


    
}