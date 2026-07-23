<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Throwable;

class StudentsImport implements
    ToCollection,
    WithHeadingRow,
    SkipsOnError
{
    use SkipsErrors;

    protected Course $course;
    public int   $imported = 0;
    public int   $skipped  = 0;
    public array $errors   = [];

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                $indexNumber = trim(
                    $row['index_number']
                    ?? $row['index number']
                    ?? $row['indexnumber']
                    ?? $row['id']
                    ?? ''
                );

                $name = trim(
                    $row['name']
                    ?? $row['full_name']
                    ?? $row['full name']
                    ?? ''
                );

                $email = trim($row['email'] ?? '');

                $level = trim($row['level'] ?? $this->course->level);

                if (empty($indexNumber) || empty($name)) {
                    $this->skipped++;
                    continue;
                }

                if (Student::where('index_number', $indexNumber)->exists()) {
                    $this->skipped++;
                    continue;
                }

                $student = Student::create([
                    'program_id'   => $this->course->program_id,
                    'index_number' => $indexNumber,
                    'name'         => $name,
                    'email'        => $email ?: null,
                    'level'        => in_array((int)$level,
                                        [100, 200, 300, 400])
                                        ? (int)$level
                                        : $this->course->level,
                ]);

                $this->course->students()->attach($student->id);
                $this->imported++;

            } catch (Throwable $e) {
                $this->skipped++;
                $this->errors[] = $e->getMessage();
            }
        }
    }
}