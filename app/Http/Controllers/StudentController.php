<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Program;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;

class StudentController extends Controller
{
    // ─────────────────────────────────────────────
    // INDEX — All students
    // ─────────────────────────────────────────────

    public function index(Request $request)
    {
        $search      = $request->get('search', '');
        $level       = $request->get('level', '');
        $programId   = $request->get('program_id', '');
        $faceStatus  = $request->get('face_status', '');

        $students = Student::with('program.faculty')
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('index_number', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->when($level, function ($q) use ($level) {
                $q->where('level', $level);
            })
            ->when($programId, function ($q) use ($programId) {
                $q->where('program_id', $programId);
            })
            ->when($faceStatus === 'registered', function ($q) {
                $q->whereNotNull('face_image_path');
            })
            ->when($faceStatus === 'missing', function ($q) {
                $q->whereNull('face_image_path');
            })
            ->orderBy('name')
            ->paginate(50)
            ->withQueryString();

        $totalStudents     = Student::count();
        $withFace          = Student::whereNotNull('face_image_path')->count();
        $withoutFace       = $totalStudents - $withFace;
        $programs          = Program::orderBy('name')->get();

        return view('admin.students.index',
            compact('students', 'search', 'level', 'programId', 'faceStatus',
                    'totalStudents', 'withFace', 'withoutFace', 'programs'));
    }

    // ─────────────────────────────────────────────
    // CREATE — Manual registration form
    // ─────────────────────────────────────────────

    public function create()
    {
        $courses  = Course::with('program.faculty')->orderBy('code')->get();
        $programs = Program::with('faculty')->get();
        return view('admin.students.create', compact('courses', 'programs'));
    }

    // ─────────────────────────────────────────────
    // STORE — Save manually registered student
    // ─────────────────────────────────────────────

    public function store(Request $request)
    {
        $request->validate([
            'index_number' => 'required|string|unique:students,index_number',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:students,email',
            'level'        => 'required|in:100,200,300,400',
            'program_id'   => 'required|exists:programs,id',
            'course_ids'   => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'face_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Create student
        $student = Student::create([
            'program_id'   => $request->program_id,
            'index_number' => $request->index_number,
            'name'         => $request->name,
            'email'        => $request->email,
            'level'        => $request->level,
        ]);

        // Enroll in selected courses
        $student->courses()->sync($request->course_ids);

        // Save face image if provided
        if ($request->hasFile('face_image')) {
            $ext      = $request->file('face_image')
                                ->getClientOriginalExtension();
            $filename = $student->index_number . '.' . $ext;
            $path     = $request->file('face_image')
                                ->storeAs('faces', $filename, 'public');
            $student->update(['face_image_path' => $path]);
        }

        // Handle webcam capture (base64 image)
        if ($request->filled('webcam_image')) {
            $this->saveWebcamImage(
                $request->webcam_image,
                $student
            );
        }

        return redirect()->route('admin.students')
            ->with('success',
                $student->name . ' registered successfully.');
    }

    // ─────────────────────────────────────────────
    // EXCEL IMPORT
    // ─────────────────────────────────────────────

    public function importExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'course_id'  => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        try {
            $import = new StudentsImport($course);
            Excel::import($import, $request->file('excel_file'));

            $message = $import->imported .
                ' student(s) imported into ' . $course->code . '.';
            if ($import->skipped > 0) {
                $message .= ' ' . $import->skipped . ' skipped (duplicates).';
            }

            return redirect()->route('admin.students')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error',
                'Import failed: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────
    // ZIP IMPORT (Excel + Face Images)
    // ─────────────────────────────────────────────

    public function importZip(Request $request)
    {
        $request->validate([
            'zip_file'  => 'required|file|mimes:zip|max:102400',
            'course_id' => 'required|exists:courses,id',
        ]);

        if (!class_exists('ZipArchive')) {
            return back()->with('error',
                'ZIP support is not enabled. ' .
                'Enable the PHP zip extension.');
        }

        $course  = Course::findOrFail($request->course_id);
        $zip     = new \ZipArchive();
        $zipPath = $request->file('zip_file')->getPathname();

        if ($zip->open($zipPath) !== true) {
            return back()->with('error', 'Could not open ZIP file.');
        }

        // Extract to temp folder
        $tempDir = storage_path('app/temp/zip_' . time());
        File::makeDirectory($tempDir, 0755, true);
        $zip->extractTo($tempDir);
        $zip->close();

        // Find spreadsheet
        $spreadsheetFile = null;
        $allFiles        = File::allFiles($tempDir);

        foreach ($allFiles as $file) {
            $ext = strtolower($file->getExtension());
            if (in_array($ext, ['xlsx', 'xls', 'csv'])) {
                $spreadsheetFile = $file->getPathname();
                break;
            }
        }

        if (!$spreadsheetFile) {
            File::deleteDirectory($tempDir);
            return back()->with('error',
                'No Excel or CSV file found inside the ZIP.');
        }

        // Import students
        $imported = 0;
        $skipped  = 0;

        try {
            $import = new StudentsImport($course);
            Excel::import($import, $spreadsheetFile);
            $imported = $import->imported;
            $skipped  = $import->skipped;
        } catch (\Exception $e) {
            File::deleteDirectory($tempDir);
            return back()->with('error',
                'Spreadsheet error: ' . $e->getMessage());
        }

        // Match and save face images
        $facesSaved      = 0;
        $imageExtensions = ['jpg', 'jpeg', 'png'];

        foreach ($allFiles as $file) {
            $ext = strtolower($file->getExtension());
            if (!in_array($ext, $imageExtensions)) continue;

            $indexNumber = $file->getFilenameWithoutExtension();
            $student     = Student::where('index_number', $indexNumber)
                                  ->first();

            if (!$student) continue;

            // Delete old face if exists
            if ($student->face_image_path) {
                Storage::disk('public')->delete($student->face_image_path);
            }

            $filename    = $indexNumber . '.' . $ext;
            $destination = storage_path('app/public/faces/' . $filename);

            File::makeDirectory(
                storage_path('app/public/faces'),
                0755, true, true
            );
            File::copy($file->getPathname(), $destination);

            $student->update(['face_image_path' => 'faces/' . $filename]);
            $facesSaved++;
        }

        File::deleteDirectory($tempDir);

        $message = $imported . ' student(s) imported, ' .
                   $facesSaved . ' face image(s) matched.';
        if ($skipped > 0) {
            $message .= ' ' . $skipped . ' row(s) skipped.';
        }

        return redirect()->route('admin.students')
            ->with('success', $message);
    }

    // ─────────────────────────────────────────────
    // DOWNLOAD TEMPLATE
    // ─────────────────────────────────────────────

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' =>
                'attachment; filename="gctu_students_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['index_number', 'name', 'email', 'level']);
            fputcsv($file, ['4235230001', 'John Mensah',
                            'john@gctu.edu.gh', '100']);
            fputcsv($file, ['4235230002', 'Abena Asante',
                            'abena@gctu.edu.gh', '200']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ─────────────────────────────────────────────
    // HELPER — Save webcam base64 image
    // ─────────────────────────────────────────────

    private function saveWebcamImage(string $base64, Student $student): void
    {
        try {
            $data = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
            $data = base64_decode($data);

            $filename    = $student->index_number . '.jpg';
            $destination = storage_path('app/public/faces/' . $filename);

            File::makeDirectory(
                storage_path('app/public/faces'),
                0755, true, true
            );

            file_put_contents($destination, $data);

            $student->update(['face_image_path' => 'faces/' . $filename]);
        } catch (\Exception $e) {
            // Silently fail — student is still registered
        }
    }
}