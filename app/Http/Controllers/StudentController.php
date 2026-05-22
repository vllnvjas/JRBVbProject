<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Degree;
use App\Models\Student;
use Illuminate\Support\Facades\Log;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    /**
     * Display student list
     */


    public function manageStudents(Request $request)
    {
        return view('students');
    }

    public function index()
    {
        $authenticatedUserId = request()->session()->get('authenticated_user_id');
        $authenticatedUser = $authenticatedUserId
            ? UserAccount::find($authenticatedUserId)
            : null;

        if ($authenticatedUser?->role === 'student') {
            $studentId = Student::where('user_account_id', $authenticatedUser->id)->value('id');

            if ($studentId) {
                return redirect()->route('students.show', $studentId);
            }
        }

        // $students = Student::all();
        // // $students = Student::with(['degree', 'userAccount'])->paginate(4);
        // $degrees = Degree::orderBy('name')->get();

        // return view('studentsList', compact('students', 'degrees'));

        $students = Student::with(['degree', 'userAccount'])->paginate(4);
        $degrees = Degree::orderBy('name')->get();

        return view('students', compact('students', 'degrees'));
    }

    /**
     * Show add student form
     */
    public function create()
    {
        $degrees = Degree::orderBy('name')->get();
        return view('addStudent', compact('degrees'));
    }

    /**
     * Store new student
     */
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
        ], [
            'contactInfo.required' => 'Contact number is required.',
            'contactInfo.digits' => 'Contact number must be exactly 11 numbers.',
        ], [
            'contactInfo' => 'contact number',
        ]);

        $user = UserAccount::create([
            'username' => $request->username,
            'email' => $request->e_mail,
            'password' => Hash::make($request->input('password')),
            'role' => 'student',
            'is_active' => 1,
            'must_change_password' => 1,
        ]);

        $student = new Student;
        $student->user_account_id = $user->id;
        $student->fname = $request->f_name;
        $student->mname = $request->filled('m_name') ? $request->m_name : null;
        $student->lname = $request->l_name;
        $student->degree_id = $request->degree_id;
        $student->contactInfo = $request->contactInfo;
        $student->save();

        Log::info('Student created', [
            'student_id' => $student->id,
            'full_name' => trim("{$student->fname} {$student->mname} {$student->lname}"),
            'email' => $user->email,
            'degree_id' => $student->degree_id,
            'contactInfo' => $student->contactInfo,
        ]);

        return redirect()->route('students.index')->with('success', 'Student added successfully');
    }

    public function show(string $id)
    {
        $authenticatedUserId = request()->session()->get('authenticated_user_id');
        $authenticatedUser = $authenticatedUserId
            ? UserAccount::find($authenticatedUserId)
            : null;

        if ($authenticatedUser?->role === 'student') {
            $studentId = Student::where('user_account_id', $authenticatedUser->id)->value('id');

            if ($studentId && (string) $studentId !== (string) $id) {
                return redirect()->route('students.show', $studentId);
            }
        }

        $student = Student::with(['degree', 'userAccount'])->findOrFail($id);
        return view('studentDetails', [
            'student' => $student,
            'canEdit' => $authenticatedUser?->role !== 'student',
        ]);
    }

    public function edit(string $id)
    {
        $authenticatedUserId = request()->session()->get('authenticated_user_id');
        $authenticatedUser = $authenticatedUserId
            ? UserAccount::find($authenticatedUserId)
            : null;

        if ($authenticatedUser?->role === 'student') {
            $studentId = Student::where('user_account_id', $authenticatedUser->id)->value('id');

            if ($studentId) {
                return redirect()->route('students.show', $studentId);
            }
        }

        $student = Student::with('userAccount')->findOrFail($id);
        $degrees = Degree::orderBy('name')->get();
        return view('editStudent', compact('student', 'degrees'));
    }

    public function update(Request $request, string $id)
    {
        $student = Student::with('userAccount')->findOrFail($id);
        $before = [
            'fname' => $student->fname,
            'mname' => $student->mname,
            'lname' => $student->lname,
            'email' => $student->userAccount?->email,
            'degree_id' => $student->degree_id,
            'contactInfo' => $student->contactInfo,
        ];

        $request->validate([
            'f_name' => ['required', 'string', 'min:2', 'max:255'],
            'm_name' => ['nullable', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255', Rule::unique('user_accounts', 'email')->ignore($student->user_account_id)],
            'degree_id' => ['required', 'exists:degrees,id'],
            'contactInfo' => ['required', 'digits:11'],
        ], [
            'contactInfo.required' => 'Contact number is required.',
            'contactInfo.digits' => 'Contact number must be exactly 11 numbers.',
        ], [
            'contactInfo' => 'contact number',
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

        $after = [
            'fname' => $student->fname,
            'mname' => $student->mname,
            'lname' => $student->lname,
            'email' => $student->userAccount?->email,
            'degree_id' => $student->degree_id,
            'contactInfo' => $student->contactInfo,
        ];
        $changedFields = [];

        foreach ($after as $field => $value) {
            if (($before[$field] ?? null) !== $value) {
                $changedFields[$field] = [
                    'from' => $before[$field] ?? null,
                    'to' => $value,
                ];
            }
        }

        Log::info('Student updated', [
            'student_id' => $student->id,
            'full_name' => trim("{$student->fname} {$student->mname} {$student->lname}"),
            'changes' => $changedFields,
            'before' => $before,
            'after' => $after,
        ]);

        return redirect('/students')->with('success', 'Student updated successfully');
    }

    public function destroy(string $id)
    {
        $student = Student::with('userAccount')->findOrFail($id);

        Log::info('Student deleted', [
            'student_id' => $student->id,
            'full_name' => trim("{$student->fname} {$student->mname} {$student->lname}"),
            'email' => $student->userAccount?->email,
            'degree_id' => $student->degree_id,
            'contactInfo' => $student->contactInfo,
        ]);

        $student->delete();
        return redirect('/students')->with('success', 'Student deleted successfully');
    }

    

    
}
