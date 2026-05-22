<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Degree;
use App\Models\Student;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['degree', 'userAccount'])->paginate(10);

        if (request()->wantsJson()) {
            return response()->json([
                'students' => $students->getCollection()->map(function (Student $student) {
                    return [
                        'id' => $student->id,
                        'full_name' => trim("{$student->fname} " . ($student->mname ? $student->mname . ' ' : '') . "{$student->lname}"),
                        'email' => $student->userAccount?->email,
                        'degree' => $student->degree?->name,
                        'contactInfo' => $student->contactInfo,
                    ];
                })->values(),
                'meta' => [
                    'total' => $students->total(),
                    'current_page' => $students->currentPage(),
                ],
            ]);
        }

        return view('teacher.students', compact('students'));
    }

    public function create()
    {
        $degrees = Degree::orderBy('name')->get();

        return view('teacher.add-student', compact('degrees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'f_name' => ['required', 'string', 'min:2', 'max:255'],
            'm_name' => ['nullable', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255', 'unique:user_accounts,email'],
            'username' => ['required', 'string', 'max:255', 'unique:user_accounts,username'],
            'password' => ['required', 'string', 'min:8'],
            'degree_id' => ['required', 'exists:degrees,id'],
            'contactInfo' => ['required', 'digits:11'],
        ]);

        $user = UserAccount::create([
            'username' => $request->username,
            'email' => $request->e_mail,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'is_active' => 1,
            'must_change_password' => 1,
        ]);

        $student = Student::create([
            'fname' => $request->f_name,
            'mname' => $request->filled('m_name') ? $request->m_name : null,
            'lname' => $request->l_name,
            'degree_id' => $request->degree_id,
            'contactInfo' => $request->contactInfo,
            'user_account_id' => $user->id,
        ]);

        Log::info('Teacher created student', [
            'teacher_user_id' => $request->session()->get('authenticated_user_id'),
            'student_id' => $student->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Student added successfully.',
                'student' => [
                    'id' => $student->id,
                    'full_name' => trim("{$student->fname} " . ($student->mname ? $student->mname . ' ' : '') . "{$student->lname}"),
                    'email' => $user->email,
                    'degree' => $student->degree?->name,
                    'contactInfo' => $student->contactInfo,
                ],
            ], 201);
        }

        return redirect()->route('teacher.students.index')->with('success', 'Student added successfully.');
    }

    public function edit(Student $student)
    {
        $student = $student->load('userAccount');
        $degrees = Degree::orderBy('name')->get();

        return view('teacher.edit-student', compact('student', 'degrees'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'f_name' => ['required', 'string', 'min:2', 'max:255'],
            'm_name' => ['nullable', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255'],
            'degree_id' => ['required', 'exists:degrees,id'],
            'contactInfo' => ['required', 'digits:11'],
        ]);

        $student->fname = $request->f_name;
        $student->mname = $request->filled('m_name') ? $request->m_name : null;
        $student->lname = $request->l_name;
        $student->degree_id = $request->degree_id;
        $student->contactInfo = $request->contactInfo;
        $student->save();

        if ($student->userAccount) {
            $student->userAccount->email = $request->e_mail;
            $student->userAccount->save();
        }

        Log::info('Teacher updated student', [
            'teacher_user_id' => $request->session()->get('authenticated_user_id'),
            'student_id' => $student->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Student updated successfully.',
                'student' => [
                    'id' => $student->id,
                    'full_name' => trim("{$student->fname} " . ($student->mname ? $student->mname . ' ' : '') . "{$student->lname}"),
                    'email' => $student->userAccount?->email,
                    'degree' => $student->degree?->name,
                    'contactInfo' => $student->contactInfo,
                ],
            ], 200);
        }

        return redirect()->route('teacher.students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $userAccount = $student->userAccount;
        $deletedStudent = [
            'id' => $student->id,
            'full_name' => trim("{$student->fname} " . ($student->mname ? $student->mname . ' ' : '') . "{$student->lname}"),
            'email' => $userAccount?->email,
            'degree' => $student->degree?->name,
            'contactInfo' => $student->contactInfo,
        ];
        
        // Delete student first (due to foreign key constraint)
        $student->delete();
        
        // Then delete the associated user account
        if ($userAccount) {
            $userAccount->delete();
        }

        Log::info('Teacher deleted student', [
            'teacher_user_id' => request()->session()->get('authenticated_user_id'),
            'student_id' => $student->id,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Student deleted successfully.',
                'student' => $deletedStudent,
            ], 200);
        }

        return redirect()->route('teacher.students.index')->with('success', 'Student deleted successfully.');
    }
}
