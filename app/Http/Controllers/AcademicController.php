<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Program;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AcademicController extends Controller
{
    // ─────────────────────────────────────────────
    // FACULTIES
    // ─────────────────────────────────────────────

    public function faculties()
    {
        $faculties = Faculty::withCount('programs')->get();
        return view('admin.academic.faculties', compact('faculties'));
    }

    public function showFaculty(Faculty $faculty)
    {
        $programs = $faculty->programs()->withCount('courses')->get();
        return view('admin.academic.programs', compact('faculty', 'programs'));
    }

    // ─────────────────────────────────────────────
    // PROGRAMS & LEVELS
    // ─────────────────────────────────────────────

    public function showLevels(Program $program)
    {
        return view('admin.academic.levels', compact('program'));
    }

    public function showProgram(Program $program, Request $request)
    {
        $level    = $request->get('level', 100);
        $semester = $request->get('semester', 1);

        $courses = $program->courses()
            ->where('level', $level)
            ->where('semester', $semester)
            ->get();

        return view('admin.academic.courses',
            compact('program', 'courses', 'level', 'semester'));
    }

    // ─────────────────────────────────────────────
    // COURSE DETAILS
    // ─────────────────────────────────────────────

    public function showCourse(Course $course)
    {
        $lecturers         = User::where('role', 'lecturer')->get();
        $assignedLecturers = $course->lecturers;

        return view('admin.academic.course-details',
            compact('course', 'lecturers', 'assignedLecturers'));
    }

    // ─────────────────────────────────────────────
    // LECTURER ASSIGNMENT
    // ─────────────────────────────────────────────

    public function assignLecturer(Request $request, Course $course)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $course->lecturers()->syncWithoutDetaching([$request->user_id]);

        return back()->with('success', 'Lecturer assigned successfully.');
    }

    public function removeLecturer(Course $course, User $lecturer)
    {
        $course->lecturers()->detach($lecturer->id);
        return back()->with('success',
            $lecturer->name . ' removed from this course.');
    }

    // ─────────────────────────────────────────────
    // STUDENT ROSTER (paginated, searchable)
    // ─────────────────────────────────────────────

    public function courseStudents(Course $course, Request $request)
    {
        $search = $request->get('search', '');

        $students = $course->students()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('students.name', 'like', '%' . $search . '%')
                      ->orWhere('students.index_number', 'like', '%' . $search . '%')
                      ->orWhere('students.email', 'like', '%' . $search . '%');
                });
            })
            ->select('students.*')
            ->paginate(50)
            ->withQueryString();

        $programs = Program::all();

        return view('admin.academic.course-students',
            compact('course', 'students', 'search', 'programs'));
    }

    // ─────────────────────────────────────────────
    // STUDENT EDIT / UPDATE
    // ─────────────────────────────────────────────

    public function editStudent(Student $student)
{
    $programs = Program::with('faculty')->get();

    return view('admin.academic.edit-student', [
        'student' => $student,
        'programs' => $programs,
    ]);
}

    public function updateStudent(Request $request, Student $student)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',

        'email' => [
            'nullable',
            'email',
            Rule::unique('students')->ignore($student->id),
        ],

        'index_number' => [
            'required',
            'string',
            'max:50',
            Rule::unique('students')->ignore($student->id),
        ],

        'program_id' => 'required|exists:programs,id',

        'level' => 'required|in:100,200,300,400',

        'face_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Replace Face Image
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('face_image')) {

        // Delete previous face image
        if (
            $student->face_image_path &&
            Storage::disk('public')->exists($student->face_image_path)
        ) {
            Storage::disk('public')->delete($student->face_image_path);
        }

        $image = $request->file('face_image');

        $filename = $validated['index_number'] . '.' . $image->getClientOriginalExtension();

        $validated['face_image_path'] = $image->storeAs(
            'faces',
            $filename,
            'public'
        );
    }

    $student->update($validated);

    return redirect()
        ->route('admin.students')
        ->with('success', 'Student updated successfully.');
}
    // ─────────────────────────────────────────────
    // STUDENT DELETE (single)
    // ─────────────────────────────────────────────

    public function deleteStudent(Student $student, Request $request)
    {
        $courseId = $request->get('course_id');

        // Remove from all courses and delete
        $student->courses()->detach();
        $student->delete();

        return redirect()
            ->route('admin.course.students', $courseId)
            ->with('success', 'Student deleted successfully.');
    }

    // ─────────────────────────────────────────────
    // STUDENT BULK DELETE
    // ─────────────────────────────────────────────

    public function bulkDeleteStudents(Request $request, Course $course)
    {
        $request->validate([
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);

        $students = Student::whereIn('id', $request->student_ids)->get();

        foreach ($students as $student) {
            $student->courses()->detach();
            $student->delete();
        }

        $count = count($request->student_ids);

        return redirect()
            ->route('admin.course.students', $course->id)
            ->with('success', $count . ' student(s) deleted successfully.');
    }

    // ─────────────────────────────────────────────
    // SINGLE STUDENT ADD
    // ─────────────────────────────────────────────

    public function addStudent(Request $request, Course $course)
    {
        $request->validate([
            'index_number' => 'required|string',
            'name'         => 'required|string',
            'captured_face' => 'nullable|string',
        ]);

        $indexNumber = trim($request->index_number);
        $name        = trim($request->name);

        // 1. Check for Index Duplication
        $existingByIndex = Student::where('index_number', $indexNumber)->first();
        if ($existingByIndex) {
            if ($course->students->contains($existingByIndex->id)) {
                return back()->with('error', "Conflict: Student with Index Number '{$indexNumber}' is already enrolled in this course.");
            }
            
            // Enroll existing student
            $course->students()->attach($existingByIndex->id);
            
            // Re-capture face if provided
            if (!empty($request->captured_face)) {
                $this->saveCapturedFace($existingByIndex, $request->captured_face);
            }

            return back()->with('success', "Found in System: Student '{$existingByIndex->name}' ({$indexNumber}) has been added to this course.");
        }

        // 2. Check for Name Duplication Warning
        $existingByName = Student::where('name', $name)->first();
        $warning = $existingByName ? " (Note: Another student with this exact name already exists in the system with ID: {$existingByName->index_number})" : "";

        // 3. Create New Student
        $student = Student::create([
            'program_id'   => $course->program_id,
            'index_number' => $indexNumber,
            'name'         => $name,
            'level'        => $request->level ?? $course->level,
            'email'        => null,
        ]);

        // Handle captured face
        if (!empty($request->captured_face)) {
            $this->saveCapturedFace($student, $request->captured_face);
        }

        $course->students()->attach($student->id);

        return back()->with('success', "Student enrolled successfully.{$warning}");
    }

    private function saveCapturedFace($student, $base64)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            $data = substr($base64, strpos($base64, ',') + 1);
            $data = base64_decode($data);
            $extension = strtolower($type[1]);
            $filename  = $student->index_number . '.' . $extension;
            $path      = 'faces/' . $filename;
            
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, $data);
            $student->update(['face_image_path' => $path]);
            return $path;
        }
        return null;
    }

    // ─────────────────────────────────────────────
    // FACE IMAGE UPLOAD
    // ─────────────────────────────────────────────

    public function uploadFace(Request $request, Student $student)
    {
        $request->validate([
            'face_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'captured_face' => 'nullable|string',
        ]);

        if ($student->face_image_path) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($student->face_image_path);
        }

        // Handle base64 captured image
        if (!empty($request->captured_face)) {
            $imageData = $request->captured_face;
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $imageData = base64_decode($imageData);
                $extension = strtolower($type[1]);
                $filename  = $student->index_number . '.' . $extension;
                $path      = 'faces/' . $filename;
                
                \Illuminate\Support\Facades\Storage::disk('public')->put($path, $imageData);
                $student->update(['face_image_path' => $path]);
                
                return back()->with('success', 'Face image captured and updated for ' . $student->index_number);
            }
        }

        // Handle standard file upload
        if ($request->hasFile('face_image')) {
            $extension = $request->file('face_image')->getClientOriginalExtension();
            $filename  = $student->index_number . '.' . $extension;
            $path      = $request->file('face_image')->storeAs('faces', $filename, 'public');

            $student->update(['face_image_path' => $path]);
            return back()->with('success', 'Face image uploaded and updated for ' . $student->index_number);
        }

        return back()->with('error', 'No image data provided.');
    }

    // ─────────────────────────────────────────────
    // ZIP / EXCEL / CSV IMPORT
    // ─────────────────────────────────────────────

    public function importStudents(Request $request, Course $course)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:zip,csv,txt,xlsx,xls|max:10240', // 10MB limit
        ]);

        $file = $request->file('excel_file');
        $extension = $file->getClientOriginalExtension();

        if ($extension === 'zip') {
            return $this->handleZipImport($file, $course);
        }

        if (in_array($extension, ['xlsx', 'xls'])) {
            return $this->handleExcelImport($file, $course);
        }

        return $this->handleCsvImport($file, $course);
    }

    private function handleExcelImport($file, $course)
    {
        $rows = Excel::toArray([], $file)[0];
        if (count($rows) < 1) return back()->with('error', 'Excel file is empty.');

        $headers = array_shift($rows);
        $mapping = $this->getColumnMapping($headers);

        $stats = ['new' => 0, 'enrolled' => 0, 'skipped' => 0];

        foreach ($rows as $row) {
            $mapped = [
                'name'  => $row[$mapping['name']] ?? null,
                'index' => $row[$mapping['index']] ?? null,
                'level' => $row[$mapping['level']] ?? $course->level,
            ];
            $res = $this->enrollOrUpdate($mapped, $course);
            if ($res) {
                $stats[$res === 'new' ? 'new' : 'enrolled']++;
            } else {
                $stats['skipped']++;
            }
        }

        return back()->with('success', "Excel import complete: {$stats['new']} new students created, {$stats['enrolled']} enrolled from existing system. {$stats['skipped']} skipped.");
    }

    private function handleCsvImport($file, $course)
    {
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);
        if (!$headers) return back()->with('error', 'CSV file is empty.');

        $mapping = $this->getColumnMapping($headers);
        $stats = ['new' => 0, 'enrolled' => 0, 'skipped' => 0];

        while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
            $mapped = [
                'name'  => $data[$mapping['name']] ?? null,
                'index' => $data[$mapping['index']] ?? null,
                'level' => $data[$mapping['level']] ?? $course->level,
            ];
            $res = $this->enrollOrUpdate($mapped, $course);
            if ($res) {
                $stats[$res === 'new' ? 'new' : 'enrolled']++;
            } else {
                $stats['skipped']++;
            }
        }

        fclose($handle);
        return back()->with('success', "CSV import complete: {$stats['new']} new students created, {$stats['enrolled']} enrolled from existing system. {$stats['skipped']} skipped.");
    }

    private function handleZipImport($file, $course)
    {
        $zip = new \ZipArchive();
        if ($zip->open($file->getRealPath()) !== TRUE) {
            return back()->with('error', 'Could not open ZIP file.');
        }

        $extractPath = storage_path('app/temp_import_' . time());
        $zip->extractTo($extractPath);
        $zip->close();

        // 1. Find Data File (CSV/Excel)
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($extractPath));
        $dataFile = null;
        $dataExt = null;
        foreach ($it as $f) {
            if ($f->isFile()) {
                $ext = strtolower($f->getExtension());
                if (in_array($ext, ['csv', 'txt', 'xlsx', 'xls'])) {
                    $dataFile = $f->getRealPath();
                    $dataExt = $ext;
                    break;
                }
            }
        }

        if ($dataFile) {
            $studentData = [];
            if (in_array($dataExt, ['xlsx', 'xls'])) {
                $rows = Excel::toArray([], $dataFile)[0];
                $headers = array_shift($rows);
                $mapping = $this->getColumnMapping($headers);
                foreach ($rows as $row) {
                    $studentData[] = [
                        'name'  => $row[$mapping['name']] ?? null,
                        'index' => $row[$mapping['index']] ?? null,
                        'level' => $row[$mapping['level']] ?? $course->level,
                    ];
                }
            } else {
                $handle = fopen($dataFile, 'r');
                $headers = fgetcsv($handle);
                $mapping = $this->getColumnMapping($headers);
                while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
                    $studentData[] = [
                        'name'  => $data[$mapping['name']] ?? null,
                        'index' => $data[$mapping['index']] ?? null,
                        'level' => $data[$mapping['level']] ?? $course->level,
                    ];
                }
                fclose($handle);
            }

            $imported = 0;
            $imagesLinked = 0;
            foreach ($studentData as $sd) {
                $student = $this->enrollOrUpdate($sd, $course);
                if ($student) {
                    $imported++;
                    if ($this->matchImageForStudent($student, $extractPath)) $imagesLinked++;
                }
            }
            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return back()->with('success', "Processed ZIP with data: {$imported} students, {$imagesLinked} images linked.");
        } else {
            // Photo-only ZIP
            $imagesLinked = 0;
            $stds = $course->students()->get();
            foreach ($stds as $student) {
                if ($this->matchImageForStudent($student, $extractPath)) $imagesLinked++;
            }
            \Illuminate\Support\Facades\File::deleteDirectory($extractPath);
            return back()->with('success', "Processed photo-only ZIP: {$imagesLinked} images matched to existing students.");
        }
    }

    private function matchImageForStudent($student, $dir)
    {
        foreach (['jpg', 'jpeg', 'png'] as $ext) {
            $imgName = $student->index_number . '.' . $ext;
            $foundImg = $this->findFileInDir($dir, $imgName);
            if ($foundImg) {
                $newPath = 'faces/' . $imgName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($newPath, file_get_contents($foundImg));
                $student->update(['face_image_path' => $newPath]);
                return $newPath;
            }
        }
        return null;
    }

    private function enrollOrUpdate($mapped, $course)
    {
        $name        = trim($mapped['name']);
        $indexNumber = trim($mapped['index']);
        $level       = isset($mapped['level']) ? trim($mapped['level']) : $course->level;

        if (empty($indexNumber) || empty($name)) return null;

        $student = \App\Models\Student::where('index_number', $indexNumber)->first();
        $status = 'new';

        if (!$student) {
            $student = \App\Models\Student::create([
                'program_id'   => $course->program_id,
                'index_number' => $indexNumber,
                'name'         => $name,
                'level'        => $level,
                'email'        => null,
            ]);
        } else {
            $student->update(['name' => $name, 'level' => $level]);
            $status = 'enrolled';
        }

        if (!$course->students->contains($student->id)) {
            $course->students()->attach($student->id);
            return $status;
        }

        return $status; // Student already in course, but we still count them as processed
    }

    private function getColumnMapping($headers)
    {
        $mapping = ['name' => -1, 'index' => -1, 'level' => -1];
        foreach ($headers as $idx => $header) {
            $h = strtolower(trim($header));
            if ($mapping['name'] == -1 && (str_contains($h, 'name') || str_contains($h, 'names'))) {
                $mapping['name'] = $idx;
            } elseif ($mapping['index'] == -1 && (str_contains($h, 'index') || str_contains($h, 'id') || str_contains($h, 'number') || str_contains($h, 'no'))) {
                $mapping['index'] = $idx;
            } elseif ($mapping['level'] == -1 && (str_contains($h, 'level') || str_contains($h, 'lvl') || str_contains($h, 'class'))) {
                $mapping['level'] = $idx;
            }
        }
        
        // Fallbacks
        if ($mapping['name'] == -1) $mapping['name'] = 0;
        if ($mapping['index'] == -1) $mapping['index'] = 1;
        if ($mapping['level'] == -1) $mapping['level'] = 2;
        
        return $mapping;
    }

    private function findFileInDir($dir, $filename)
    {
        $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($it as $file) {
            if ($file->isFile() && strtolower($file->getFilename()) === strtolower($filename)) {
                return $file->getRealPath();
            }
        }
        return null;
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="gctu_enrollment_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Full Name', 'Index Number', 'Level']);
            fputcsv($file, ['John Doe', '01210001', '300']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─────────────────────────────────────────────
    // LECTURER COURSE VIEW
    // ─────────────────────────────────────────────

    public function lecturerCourse(Course $course)
    {
        if (!auth()->user()->courses->contains($course->id)) {
            abort(403);
        }

        $students = $course->students;
        return view('lecturer.course-students',
            compact('course', 'students'));
    }
}