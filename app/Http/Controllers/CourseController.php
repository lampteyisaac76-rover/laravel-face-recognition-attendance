<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('program.faculty')
                         ->orderBy('level')
                         ->orderBy('semester')
                         ->get();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $faculties = Faculty::with('programs')->get();
        return view('admin.courses.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'code'       => 'required|string|max:20|unique:courses,code',
            'title'      => 'required|string|max:255',
            'credits'    => 'required|integer|min:1|max:6',
            'level'      => 'required|in:100,200,300,400',
            'semester'   => 'required|in:1,2',
        ]);

        Course::create([
            'program_id' => $request->program_id,
            'code'       => strtoupper($request->code),
            'title'      => $request->title,
            'credits'    => $request->credits,
            'level'      => $request->level,
            'semester'   => $request->semester,
        ]);

        return redirect()->route('admin.courses')
            ->with('success', 'Course "' . $request->title . '" registered successfully.');
    }

    public function edit(Course $course)
    {
        $faculties = Faculty::with('programs')->get();
        return view('admin.courses.edit', compact('course', 'faculties'));
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'code'       => 'required|string|max:20|unique:courses,code,' . $course->id,
            'title'      => 'required|string|max:255',
            'credits'    => 'required|integer|min:1|max:6',
            'level'      => 'required|in:100,200,300,400',
            'semester'   => 'required|in:1,2',
        ]);

        $course->update([
            'program_id' => $request->program_id,
            'code'       => strtoupper($request->code),
            'title'      => $request->title,
            'credits'    => $request->credits,
            'level'      => $request->level,
            'semester'   => $request->semester,
        ]);

        return redirect()->route('admin.courses')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses')
            ->with('success', 'Course deleted successfully.');
    }

    // Called via AJAX when faculty is selected on the create form
    public function getProgramsByFaculty(Faculty $faculty)
    {
        $programs = $faculty->programs()->get(['id', 'name', 'code']);
        return response()->json($programs);
    }
}