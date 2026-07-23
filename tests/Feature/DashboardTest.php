<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\AttendanceSession;
use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_loads_with_dynamic_data(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'staff_id' => 'ADMIN001'
        ]);
        
        // Create lecturers manually
        User::create([
            'name' => 'Lecturer One',
            'email' => 'l1@test.com',
            'password' => Hash::make('password'),
            'role' => 'lecturer',
            'staff_id' => 'L001',
            'is_verified' => true
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalLecturers');
        $response->assertViewHas('recentLecturers');
        $response->assertSee('System Administration');
    }

    public function test_lecturer_dashboard_loads_with_dynamic_data(): void
    {
        $this->withoutExceptionHandling();
        $lecturer = User::factory()->create([
            'role' => 'lecturer',
            'staff_id' => 'L002'
        ]);

        $faculty = Faculty::create(['name' => 'IT', 'code' => 'FTI']);
        $program = Program::create(['faculty_id' => $faculty->id, 'name' => 'CS', 'code' => 'CS']);
        
        $course = Course::create([
            'program_id' => $program->id,
            'code' => 'CS101',
            'title' => 'Intro to CS',
            'credits' => 3,
            'level' => 100,
            'semester' => 1
        ]);
        
        $lecturer->courses()->attach($course->id);
        
        // Create attendance session
        AttendanceSession::create([
            'course_id' => $course->id,
            'user_id' => $lecturer->id,
            'session_date' => now()->toDateString(),
            'period' => 'morning',
            'started_at' => now(),
            'status' => 'active'
        ]);

        $response = $this->actingAs($lecturer)->get(route('lecturer.dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('assignedCourses');
        $response->assertViewHas('recentSessions');
        $response->assertSee('Intro to CS');
        $response->assertSee('Recent Attendance Activity');
    }
}
