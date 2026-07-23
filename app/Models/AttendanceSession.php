<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'session_date',
        'period',
        'started_at',
        'ended_at',
        'status',
    ];

    protected $casts = [
        'session_date' => 'date',
        'started_at'   => 'datetime',
        'ended_at'     => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function presentStudents()
    {
        return $this->hasMany(Attendance::class)
                    ->where('status', 'present');
    }

    public function absentStudents()
    {
        return $this->hasMany(Attendance::class)
                    ->where('status', 'absent');
    }
}