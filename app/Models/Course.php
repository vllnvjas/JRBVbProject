<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    public function students()
    {
        return $this->belongsToMany(Student::class, 'course__students', 'course_id', 'student_id');
    }
}
