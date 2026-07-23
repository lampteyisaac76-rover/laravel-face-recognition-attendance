<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faculty;
use App\Models\Program;
use App\Models\Course;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Faculties
        $focis = Faculty::create(['name' => 'Faculty of Computing and Information Systems', 'code' => 'FOCIS']);
        $foe = Faculty::create(['name' => 'Faculty of Engineering', 'code' => 'FOE']);
        $gsb = Faculty::create(['name' => 'GCTU School of Business', 'code' => 'GSB']);

        // 2. Create Programs for FOCIS
        $cs = Program::create(['faculty_id' => $focis->id, 'name' => 'Computer Science', 'code' => 'CS']);
        $bit = Program::create(['faculty_id' => $focis->id, 'name' => 'Information Technology', 'code' => 'BIT']);
        $mobile = Program::create(['faculty_id' => $focis->id, 'name' => 'Mobile Computing', 'code' => 'MC']);

        // 3. Seed Computer Science Courses (Level 100-400)
        $csCourses = [
            // Level 100 - Sem 1
            ['code' => 'FREN 171', 'title' => 'BASIC FRENCH I', 'credits' => 1, 'level' => 100, 'semester' => 1],
            ['code' => 'ENGL 171', 'title' => 'COMMUNICATION SKILLS I', 'credits' => 2, 'level' => 100, 'semester' => 1],
            ['code' => 'CSNS 141', 'title' => 'DIGITAL ELECTRONICS', 'credits' => 3, 'level' => 100, 'semester' => 1],
            ['code' => 'MATH 103', 'title' => 'DISCRETE MATHEMATICS FOR COMPUTER SCIENCE', 'credits' => 3, 'level' => 100, 'semester' => 1],
            ['code' => 'CSSD 111', 'title' => 'INTRODUCTION TO COMPUTER SYSTEMS', 'credits' => 3, 'level' => 100, 'semester' => 1],
            ['code' => 'GTGE 121', 'title' => 'INTRODUCTION TO ELECTRONICS', 'credits' => 3, 'level' => 100, 'semester' => 1],
            ['code' => 'MATH 105', 'title' => 'LINEAR ALGEBRA', 'credits' => 3, 'level' => 100, 'semester' => 1],
            ['code' => 'CSSD 101', 'title' => 'PROGRAMMING & PROBLEM-SOLVING', 'credits' => 3, 'level' => 100, 'semester' => 1],

            // Level 100 - Sem 2
            ['code' => 'FREN 172', 'title' => 'BASIC FRENCH II', 'credits' => 1, 'level' => 100, 'semester' => 2],
            ['code' => 'ENGL 172', 'title' => 'COMMUNICATION SKILLS II', 'credits' => 2, 'level' => 100, 'semester' => 2],
            ['code' => 'CSSD 104', 'title' => 'COMPUTER ARCHITECTURE', 'credits' => 3, 'level' => 100, 'semester' => 2],
            ['code' => 'ENGL 174', 'title' => 'CRITICAL THINKING & LOGICAL REASONING', 'credits' => 2, 'level' => 100, 'semester' => 2],
            ['code' => 'MATH 102', 'title' => 'DIFFERENTIAL & INTEGRAL CALCULUS', 'credits' => 3, 'level' => 100, 'semester' => 2],
            ['code' => 'MATH 112', 'title' => 'PROBABILITY & STATISTICS', 'credits' => 3, 'level' => 100, 'semester' => 2],
            ['code' => 'CSSD 102', 'title' => 'PROGRAMMING WITH C++', 'credits' => 3, 'level' => 100, 'semester' => 2],
            ['code' => 'CSC 132', 'title' => 'PHYSICS FOR COMPUTING SYSTEMS', 'credits' => 3, 'level' => 100, 'semester' => 2],

            // Level 200 - Sem 1
            ['code' => 'CSNS 241', 'title' => 'DATA COMMUNICATIONS', 'credits' => 3, 'level' => 200, 'semester' => 1],
            ['code' => 'CSSD 201', 'title' => 'DATA STRUCTURES & ALGORITHMS', 'credits' => 3, 'level' => 200, 'semester' => 1],
            ['code' => 'CSSD 205', 'title' => 'LOGIC IN COMPUTER SCIENCE', 'credits' => 3, 'level' => 200, 'semester' => 1],
            ['code' => 'CSSD 203', 'title' => 'MICROPROCESSORS & MICROCONTROLLERS', 'credits' => 3, 'level' => 200, 'semester' => 1],
            ['code' => 'CSSD 223', 'title' => 'SYSTEMS ANALYSIS & DESIGN', 'credits' => 3, 'level' => 200, 'semester' => 1],
            ['code' => 'CSSD 209', 'title' => 'WEB PROGRAMMING & APPLICATIONS', 'credits' => 3, 'level' => 200, 'semester' => 1],

            // Level 200 - Sem 2
            ['code' => 'CSSD 232', 'title' => 'AUTOMATA THEORY', 'credits' => 3, 'level' => 200, 'semester' => 2],
            ['code' => 'CSNS 242', 'title' => 'COMPUTER NETWORKS', 'credits' => 3, 'level' => 200, 'semester' => 2],
            ['code' => 'CSSD 272', 'title' => 'DATABASE MANAGEMENT SYSTEMS', 'credits' => 3, 'level' => 200, 'semester' => 2],
            ['code' => 'CSSD 218', 'title' => 'FUNDAMENTAL SOFTWARE ENGINEERING', 'credits' => 3, 'level' => 200, 'semester' => 2],
            ['code' => 'CSBC 252', 'title' => 'INTRODUCTION TO CLOUD COMPUTING', 'credits' => 3, 'level' => 200, 'semester' => 2],
            ['code' => 'CSSD 202', 'title' => 'OBJECT-ORIENTED ANALYSIS DESIGN & PROGRAMMING', 'credits' => 3, 'level' => 200, 'semester' => 2],
            ['code' => 'CSSD 216', 'title' => 'OPERATING SYSTEMS', 'credits' => 3, 'level' => 200, 'semester' => 2],
            ['code' => 'CSSD 204', 'title' => 'SCRIPTING LANGUAGES', 'credits' => 2, 'level' => 200, 'semester' => 2],

            // Level 300 - Sem 1
            ['code' => 'CSSD 301', 'title' => 'ANALYSIS & DESIGN OF ALGORITHMS', 'credits' => 3, 'level' => 300, 'semester' => 1],
            ['code' => 'CSSD 315', 'title' => 'COMPUTER GRAPHICS', 'credits' => 2, 'level' => 300, 'semester' => 1],
            ['code' => 'CSBC 311', 'title' => 'DISTRIBUTED SYSTEMS', 'credits' => 3, 'level' => 300, 'semester' => 1],
            ['code' => 'CSFT 395', 'title' => 'FIELD TRIP & REPORT WRITING', 'credits' => 2, 'level' => 300, 'semester' => 1],
            ['code' => 'CSBC 333', 'title' => 'INTRODUCTION TO ARTIFICIAL INTELLIGENCE', 'credits' => 3, 'level' => 300, 'semester' => 1],
            ['code' => 'CSBC 324', 'title' => 'MANAGEMENT INFORMATION SYSTEMS', 'credits' => 3, 'level' => 300, 'semester' => 1],
            ['code' => 'CSSD 313', 'title' => 'MULTIMEDIA TECHNOLOGIES', 'credits' => 2, 'level' => 300, 'semester' => 1],
            ['code' => 'CSSD 353', 'title' => 'FOUNDATIONS OF HUMAN COMPUTER INTERACTION', 'credits' => 3, 'level' => 300, 'semester' => 1],

            // Level 300 - Sem 2
            ['code' => 'BUEA 364', 'title' => 'BASIC ECONOMICS & ACCOUNTING PRINCIPLES', 'credits' => 2, 'level' => 300, 'semester' => 2],
            ['code' => 'CSSD 314', 'title' => 'COMPILER CONSTRUCTION', 'credits' => 3, 'level' => 300, 'semester' => 2],
            ['code' => 'CSGS 391', 'title' => 'INDUSTRIAL ATTACHMENT', 'credits' => 1, 'level' => 300, 'semester' => 2],
            ['code' => 'CSNS 342', 'title' => 'NETWORK & DISTRIBUTED PROGRAMMING', 'credits' => 3, 'level' => 300, 'semester' => 2],
            ['code' => 'BUPE 362', 'title' => 'PRINCIPLES OF BUSINESS MANAGEMENT & ENTREPRENEURSHIP', 'credits' => 2, 'level' => 300, 'semester' => 2],
            ['code' => 'CSGS 392', 'title' => 'RESEARCH METHODS', 'credits' => 3, 'level' => 300, 'semester' => 2],
            ['code' => 'CSBC 352', 'title' => 'SOFTWARE ENGINEERING PROJECT MANAGEMENT', 'credits' => 3, 'level' => 300, 'semester' => 2],

            // Level 400 - Sem 1
            ['code' => 'CSBC 421', 'title' => 'MOBILE COMPUTING', 'credits' => 3, 'level' => 400, 'semester' => 1],
            ['code' => 'CSNS 441', 'title' => 'NETWORK MANAGEMENT', 'credits' => 3, 'level' => 400, 'semester' => 1],
            ['code' => 'CSGS 491', 'title' => 'PROJECT WORK I', 'credits' => 3, 'level' => 400, 'semester' => 1],
            ['code' => 'CSBC 423', 'title' => 'SERVICE-ORIENTED COMPUTING', 'credits' => 3, 'level' => 400, 'semester' => 1],
            ['code' => 'CSCS 453', 'title' => 'SOCIAL, LEGAL & ETHICAL ASPECTS OF COMPUTER SCIENCE', 'credits' => 3, 'level' => 400, 'semester' => 1],
            ['code' => 'CSNS 431', 'title' => 'WIRELESS NETWORKS', 'credits' => 3, 'level' => 400, 'semester' => 1],
        ];

        foreach ($csCourses as $courseData) {
            $cs->courses()->create($courseData);
        }

        // Add dummy courses for other programs
        $foe->programs()->create(['name' => 'Electrical Engineering', 'code' => 'EE'])->courses()->create([
            'code' => 'EE 101', 'title' => 'Circuit Theory I', 'credits' => 3, 'level' => 100, 'semester' => 1
        ]);
    }
}
