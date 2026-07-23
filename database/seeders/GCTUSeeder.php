<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GCTUSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Faculty of Computing and Information Systems (FoCIS)
        $focis = \App\Models\Faculty::create(['name' => 'Faculty of Computing and Information Systems', 'code' => 'FoCIS']);
        $cs = \App\Models\Program::create(['faculty_id' => $focis->id, 'name' => 'BSc Computer Science', 'code' => 'CS']);
        $it = \App\Models\Program::create(['faculty_id' => $focis->id, 'name' => 'BSc Information Technology', 'code' => 'IT']);
        $se = \App\Models\Program::create(['faculty_id' => $focis->id, 'name' => 'BSc Software Engineering', 'code' => 'SE']);
        $ds = \App\Models\Program::create(['faculty_id' => $focis->id, 'name' => 'BSc Data Science and Analytics', 'code' => 'DS']);

        // Faculty of Engineering (FoE)
        $eng = \App\Models\Faculty::create(['name' => 'Faculty of Engineering', 'code' => 'FoE']);
        $ce = \App\Models\Program::create(['faculty_id' => $eng->id, 'name' => 'BSc Computer Engineering', 'code' => 'CE']);
        $te = \App\Models\Program::create(['faculty_id' => $eng->id, 'name' => 'BSc Telecommunications Engineering', 'code' => 'TE']);
        $ee = \App\Models\Program::create(['faculty_id' => $eng->id, 'name' => 'BSc Electrical and Electronic Engineering', 'code' => 'EE']);

        // GCTU Business School
        $bus = \App\Models\Faculty::create(['name' => 'GCTU Business School', 'code' => 'GBS']);
        $acc = \App\Models\Program::create(['faculty_id' => $bus->id, 'name' => 'BSc Accounting with Computing', 'code' => 'ACC']);
        $bf = \App\Models\Program::create(['faculty_id' => $bus->id, 'name' => 'BSc Banking and Finance', 'code' => 'BF']);
        $ec = \App\Models\Program::create(['faculty_id' => $bus->id, 'name' => 'BSc E-Commerce and Marketing', 'code' => 'ECM']);

        // Expanded Courses for CS (Computing)
        $courses = [
            // CS Level 100 Sem 1
            ['program_id' => $cs->id, 'code' => 'FREN 171', 'title' => 'BASIC FRENCH I', 'level' => 100, 'semester' => 1, 'credits' => 1],
            ['program_id' => $cs->id, 'code' => 'ENGL 171', 'title' => 'COMMUNICATION SKILLS I', 'level' => 100, 'semester' => 1, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'CSNS 141', 'title' => 'DIGITAL ELECTRONICS', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'MATH 103', 'title' => 'DISCRETE MATHEMATICS FOR COMPUTER SCIENCE', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 111', 'title' => 'INTRODUCTION TO COMPUTER SYSTEMS', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'GTGE 121', 'title' => 'INTRODUCTION TO ELECTRONICS', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'MATH 105', 'title' => 'LINEAR ALGEBRA', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 101', 'title' => 'PROGRAMMING & PROBLEM-SOLVING', 'level' => 100, 'semester' => 1, 'credits' => 3],

            // CS Level 100 Sem 2
            ['program_id' => $cs->id, 'code' => 'FREN 172', 'title' => 'BASIC FRENCH II', 'level' => 100, 'semester' => 2, 'credits' => 1],
            ['program_id' => $cs->id, 'code' => 'ENGL 172', 'title' => 'COMMUNICATION SKILLS II', 'level' => 100, 'semester' => 2, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'CSSD 104', 'title' => 'COMPUTER ARCHITECTURE', 'level' => 100, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'ENGL 174', 'title' => 'CRITICAL THINKING & LOGICAL REASONING', 'level' => 100, 'semester' => 2, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'MATH 102', 'title' => 'DIFFERENTIAL & INTEGRAL CALCULUS', 'level' => 100, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'MATH 112', 'title' => 'PROBABILITY & STATISTICS', 'level' => 100, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 102', 'title' => 'PROGRAMMING WITH C++', 'level' => 100, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSC 132', 'title' => 'PHYSICS FOR COMPUTING SYSTEMS', 'level' => 100, 'semester' => 2, 'credits' => 3],

            // CS Level 200 Sem 1
            ['program_id' => $cs->id, 'code' => 'CSNS 241', 'title' => 'DATA COMMUNICATIONS', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 201', 'title' => 'DATA STRUCTURES & ALGORITHMS', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 205', 'title' => 'LOGIC IN COMPUTER SCIENCE', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 203', 'title' => 'MICROPROCESSORS & MICROCONTROLLERS', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 223', 'title' => 'SYSTEMS ANALYSIS & DESIGN', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 209', 'title' => 'WEB PROGRAMMING & APPLICATIONS', 'level' => 200, 'semester' => 1, 'credits' => 3],

            // CS Level 200 Sem 2
            ['program_id' => $cs->id, 'code' => 'CSSD 232', 'title' => 'AUTOMATA THEORY', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSNS 242', 'title' => 'COMPUTER NETWORKS', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 272', 'title' => 'DATABASE MANAGEMENT SYSTEMS', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 218', 'title' => 'FUNDAMENTAL SOFTWARE ENGINEERING', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSBC 252', 'title' => 'INTRODUCTION TO CLOUD COMPUTING', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 202', 'title' => 'OBJECT-ORIENTED ANALYSIS DESIGN & PROGRAMMING', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 216', 'title' => 'OPERATING SYSTEMS', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 204', 'title' => 'SCRIPTING LANGUAGES', 'level' => 200, 'semester' => 2, 'credits' => 2],

            // CS Level 300 Sem 1
            ['program_id' => $cs->id, 'code' => 'CSSD 301', 'title' => 'ANALYSIS & DESIGN OF ALGORITHMS', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 315', 'title' => 'COMPUTER GRAPHICS', 'level' => 300, 'semester' => 1, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'CSBC 311', 'title' => 'DISTRIBUTED SYSTEMS', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSFT 395', 'title' => 'FIELD TRIP & REPORT WRITING', 'level' => 300, 'semester' => 1, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'CSBC 333', 'title' => 'INTRODUCTION TO ARTIFICIAL INTELLIGENCE', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSBC 324', 'title' => 'MANAGEMENT INFORMATION SYSTEMS', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSSD 313', 'title' => 'MULTIMEDIA TECHNOLOGIES', 'level' => 300, 'semester' => 1, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'CSSD 353', 'title' => 'FOUNDATIONS OF HUMAN COMPUTER INTERACTION', 'level' => 300, 'semester' => 1, 'credits' => 3],

            // CS Level 300 Sem 2
            ['program_id' => $cs->id, 'code' => 'BUEA 364', 'title' => 'BASIC ECONOMICS & ACCOUNTING PRINCIPLES', 'level' => 300, 'semester' => 2, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'CSSD 314', 'title' => 'COMPILER CONSTRUCTION', 'level' => 300, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSGS 391', 'title' => 'INDUSTRIAL ATTACHMENT', 'level' => 300, 'semester' => 2, 'credits' => 1],
            ['program_id' => $cs->id, 'code' => 'CSNS 342', 'title' => 'NETWORK & DISTRIBUTED PROGRAMMING', 'level' => 300, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'BUPE 362', 'title' => 'PRINCIPLES OF BUSINESS MANAGEMENT & ENTREPRENEURSHIP', 'level' => 300, 'semester' => 2, 'credits' => 2],
            ['program_id' => $cs->id, 'code' => 'CSGS 392', 'title' => 'RESEARCH METHODS', 'level' => 300, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSBC 352', 'title' => 'SOFTWARE ENGINEERING PROJECT MANAGEMENT', 'level' => 300, 'semester' => 2, 'credits' => 3],

            // CS Level 400 Sem 1
            ['program_id' => $cs->id, 'code' => 'CSBC 421', 'title' => 'MOBILE COMPUTING', 'level' => 400, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSNS 441', 'title' => 'NETWORK MANAGEMENT', 'level' => 400, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSGS 491', 'title' => 'PROJECT WORK I', 'level' => 400, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSBC 423', 'title' => 'SERVICE-ORIENTED COMPUTING', 'level' => 400, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSCS 453', 'title' => 'SOCIAL, LEGAL & ETHICAL ASPECTS OF CS', 'level' => 400, 'semester' => 1, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSNS 431', 'title' => 'WIRELESS NETWORKS', 'level' => 400, 'semester' => 1, 'credits' => 3],

            // CS Level 400 Sem 2
            ['program_id' => $cs->id, 'code' => 'CSGS 492', 'title' => 'FINAL YEAR PROJECT II', 'level' => 400, 'semester' => 2, 'credits' => 4],
            ['program_id' => $cs->id, 'code' => 'CSBC 454', 'title' => 'ETHICS IN COMPUTING', 'level' => 400, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSBC 456', 'title' => 'ADVANCED MACHINE LEARNING', 'level' => 400, 'semester' => 2, 'credits' => 3],
            ['program_id' => $cs->id, 'code' => 'CSBC 458', 'title' => 'CLOUD SECURITY', 'level' => 400, 'semester' => 2, 'credits' => 3],

            // Computer Engineering (Engineering)
            ['program_id' => $ce->id, 'code' => 'ECEN 101', 'title' => 'Introduction to Engineering', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $ce->id, 'code' => 'ECEN 211', 'title' => 'Digital Logic Design', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $ce->id, 'code' => 'ECEN 321', 'title' => 'Microprocessor Systems', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $ce->id, 'code' => 'ECEN 499', 'title' => 'Engineering Project', 'level' => 400, 'semester' => 1, 'credits' => 4],

            // Information Technology (Computing)
            ['program_id' => $it->id, 'code' => 'ITEC 101', 'title' => 'IT Fundamentals', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $it->id, 'code' => 'ITEC 205', 'title' => 'System Administration', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $it->id, 'code' => 'ITEC 312', 'title' => 'Network Security', 'level' => 300, 'semester' => 2, 'credits' => 3],
            ['program_id' => $it->id, 'code' => 'ITEC 401', 'title' => 'IT Project Management', 'level' => 400, 'semester' => 1, 'credits' => 3],

            // Software Engineering (Computing)
            ['program_id' => $se->id, 'code' => 'SENG 101', 'title' => 'Software Process', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $se->id, 'code' => 'SENG 202', 'title' => 'Software Requirements', 'level' => 200, 'semester' => 2, 'credits' => 3],
            ['program_id' => $se->id, 'code' => 'SENG 305', 'title' => 'Software Architecture', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $se->id, 'code' => 'SENG 403', 'title' => 'Software Testing & Quality Assurance', 'level' => 400, 'semester' => 2, 'credits' => 3],

            // Telecommunications Engineering (Engineering)
            ['program_id' => $te->id, 'code' => 'TEEN 101', 'title' => 'Circuit Theory', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $te->id, 'code' => 'TEEN 305', 'title' => 'Signal Processing', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $te->id, 'code' => 'TEEN 201', 'title' => 'Analog & Digital Communications', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $te->id, 'code' => 'TEEN 402', 'title' => 'Wireless Communication Systems', 'level' => 400, 'semester' => 2, 'credits' => 3],

            // Electrical and Electronic Engineering (Engineering)
            ['program_id' => $ee->id, 'code' => 'EEEN 101', 'title' => 'Electrical Circuits I', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $ee->id, 'code' => 'EEEN 203', 'title' => 'Electronics I', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $ee->id, 'code' => 'EEEN 305', 'title' => 'Power Systems Analysis', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $ee->id, 'code' => 'EEEN 407', 'title' => 'Control Systems Engineering', 'level' => 400, 'semester' => 2, 'credits' => 3],

            // Accounting with Computing (Business)
            ['program_id' => $acc->id, 'code' => 'BACC 101', 'title' => 'Principles of Accounting', 'level' => 100, 'semester' => 1, 'credits' => 3],
            ['program_id' => $acc->id, 'code' => 'BACC 231', 'title' => 'Accounting Software Systems', 'level' => 200, 'semester' => 1, 'credits' => 3],
            ['program_id' => $acc->id, 'code' => 'BACC 351', 'title' => 'Financial Reporting', 'level' => 300, 'semester' => 1, 'credits' => 3],
            ['program_id' => $acc->id, 'code' => 'BACC 411', 'title' => 'Auditing & Assurance', 'level' => 400, 'semester' => 1, 'credits' => 3],
        ];

        foreach ($courses as $c) {
            \App\Models\Course::create($c);
        }
    }
}
