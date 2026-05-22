<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAccount;
use App\Models\Student;

class TeacherController extends Controller
{
    public function dashboard(Request $request)
    {
        $authId = $request->session()->get('authenticated_user_id');

        $teacher = UserAccount::find($authId);

        $students = Student::with(['degree', 'userAccount'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('teacher.dashboard', compact('teacher', 'students'));
    }
}
