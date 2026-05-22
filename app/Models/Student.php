<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable=[
            'fname',
            'mname',
            'lname',
            'degree_id',
            'contactInfo',
            'user_account_id'

    ];

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course__students', 'student_id', 'course_id');
    }

    public function userAccount(): BelongsTo
    {
        return $this->belongsTo(UserAccount::class, 'user_account_id');
    }

}
