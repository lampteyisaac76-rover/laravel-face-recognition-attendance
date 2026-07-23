<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['program_id', 'code', 'title', 'credits', 'level', 'semester'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function lecturers()
    {
        return $this->belongsToMany(User::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }
}
