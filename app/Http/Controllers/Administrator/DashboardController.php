<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\Degree;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'studentCount' => Student::count(),
            'teacherCount' => Teacher::count(),
            'adminCount' => Admin::count(),
            'recentStudents' => Student::with(['userAccount', 'degree'])
                ->orderByDesc('id')
                ->take(10)
                ->get(),
            'recentTeachers' => Teacher::with('userAccount')
                ->latest()
                ->take(5)
                ->get(),
        ]);
    }

    public function studentsIndex()
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

        return view('admin.students', compact('students'));
    }

    public function teachersIndex()
    {
        $teachers = Teacher::with(['userAccount', 'degree'])
            ->orderByDesc('id')
            ->paginate(10);

        if (request()->wantsJson()) {
            return response()->json([
                'teachers' => $teachers->getCollection()->map(function (Teacher $teacher) {
                    return [
                        'id' => $teacher->id,
                        'full_name' => trim("{$teacher->fname} " . ($teacher->mname ? $teacher->mname . ' ' : '') . "{$teacher->lname}"),
                        'username' => $teacher->userAccount?->username,
                        'email' => $teacher->userAccount?->email,
                        'status' => $teacher->userAccount && $teacher->userAccount->is_active ? 'Active' : 'Inactive',
                    ];
                })->values(),
                'meta' => [
                    'total' => $teachers->total(),
                    'current_page' => $teachers->currentPage(),
                ],
            ]);
        }

        return view('admin.teachers', compact('teachers'));
    }

    public function createStudent()
    {
        $degrees = Degree::orderBy('name')->get();

        return view('admin.add-student', compact('degrees'));
    }

    public function storeStudent(Request $request)
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

        $student = Student::create([
            'user_account_id' => $user->id,
            'fname' => $request->f_name,
            'mname' => $request->filled('m_name') ? $request->m_name : null,
            'lname' => $request->l_name,
            'degree_id' => $request->degree_id,
            'contactInfo' => $request->contactInfo,
        ]);

        Log::info('Admin created student', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
            'student_id' => $student->id,
            'email' => $user->email,
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

        return redirect()->route('admin.dashboard')->with('success', 'Student added successfully.');
    }

    public function createTeacher()
    {
        $degrees = Degree::orderBy('name')->get();

        return view('admin.add-teacher', compact('degrees'));
    }

    public function storeTeacher(Request $request)
    {
        $request->validate([
            'f_name' => ['required', 'string', 'min:2', 'max:255'],
            'm_name' => ['nullable', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255', 'unique:user_accounts,email'],
            'username' => ['required', 'string', 'max:255', 'unique:user_accounts,username'],
            'password' => ['required', 'string', 'min:8'],
            'degree_id' => ['nullable', 'exists:degrees,id'],
            'contactInfo' => ['nullable', 'digits:11'],
        ]);

        $fullName = trim("{$request->f_name} {$request->m_name} {$request->l_name}");

        $user = UserAccount::create([
            'name' => $fullName,
            'username' => $request->username,
            'email' => $request->e_mail,
            'password' => Hash::make($request->input('password')),
            'role' => 'teacher',
            'is_active' => 1,
            'must_change_password' => 1,
        ]);

        $teacher = Teacher::create([
            'fname' => $request->f_name,
            'mname' => $request->filled('m_name') ? $request->m_name : null,
            'lname' => $request->l_name,
            'degree_id' => $request->degree_id,
            'contactInfo' => $request->contactInfo,
            'user_account_id' => $user->id,
        ]);

        Log::info('Admin created teacher', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
            'teacher_id' => $teacher->id,
            'email' => $user->email,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Teacher account added successfully.',
                'teacher' => [
                    'id' => $teacher->id,
                    'full_name' => trim("{$teacher->fname} " . ($teacher->mname ? $teacher->mname . ' ' : '') . "{$teacher->lname}"),
                    'username' => $user->username ?? null,
                    'email' => $user->email ?? null,
                    'status' => $user->is_active ? 'Active' : 'Inactive',
                ],
            ], 201);
        }

        return redirect()->route('admin.dashboard')->with('success', 'Teacher account added successfully.');
    }

    public function editStudent(Student $student)
    {
        $student = $student->load('userAccount');
        $degrees = Degree::orderBy('name')->get();

        return view('admin.edit-student', compact('student', 'degrees'));
    }

    public function updateStudent(Request $request, Student $student)
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

        Log::info('Admin updated student', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
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
                    'status' => $student->userAccount && $student->userAccount->is_active ? 'Active' : 'Inactive',
                ],
            ], 200);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully.');
    }

    public function deleteStudent(Student $student)
    {
        $userAccount = $student->userAccount;
        
        // Delete student first (due to foreign key constraint)
        $student->delete();
        
        // Then delete the associated user account
        if ($userAccount) {
            $userAccount->delete();
        }

        Log::info('Admin deleted student', [
            'admin_user_id' => request()->session()->get('authenticated_user_id'),
            'student_id' => $student->id,
        ]);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Student deleted successfully.'], 200);
        }

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully.');
    }

    public function editTeacher(Teacher $teacher)
    {
        $degrees = Degree::orderBy('name')->get();

        return view('admin.edit-teacher', compact('teacher', 'degrees'));
    }

    public function updateTeacher(Request $request, Teacher $teacher)
    {
        $request->validate([
            'f_name' => ['required', 'string', 'min:2', 'max:255'],
            'm_name' => ['nullable', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255'],
            'degree_id' => ['nullable', 'exists:degrees,id'],
            'contactInfo' => ['nullable', 'digits:11'],
        ]);

        $teacher->fname = $request->f_name;
        $teacher->mname = $request->filled('m_name') ? $request->m_name : null;
        $teacher->lname = $request->l_name;
        $teacher->degree_id = $request->degree_id;
        $teacher->contactInfo = $request->contactInfo;
        $teacher->save();

        if ($teacher->userAccount) {
            $fullName = trim("{$request->f_name} " . ($request->m_name ? $request->m_name . ' ' : '') . "{$request->l_name}");
            $teacher->userAccount->name = $fullName;
            $teacher->userAccount->email = $request->e_mail;
            $teacher->userAccount->save();
        }

        Log::info('Admin updated teacher', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
            'teacher_id' => $teacher->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Teacher updated successfully.',
                'teacher' => [
                    'id' => $teacher->id,
                    'full_name' => trim("{$teacher->fname} " . ($teacher->mname ? $teacher->mname . ' ' : '') . "{$teacher->lname}"),
                    'username' => $teacher->userAccount?->username,
                    'email' => $teacher->userAccount?->email,
                    'status' => $teacher->userAccount && $teacher->userAccount->is_active ? 'Active' : 'Inactive',
                ],
            ], 200);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    public function deleteTeacher(Teacher $teacher)
    {
        if ($teacher->userAccount) {
            $teacher->userAccount->delete();
        }
        $teacher->delete();

        Log::info('Admin deleted teacher', [
            'admin_user_id' => request()->session()->get('authenticated_user_id'),
            'teacher_id' => $teacher->id,
        ]);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Teacher deleted successfully.'], 200);
        }

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully.');
    }

    public function degreesIndex()
    {
        $degrees = Degree::orderBy('name')->paginate(10);

        return view('admin.degrees', compact('degrees'));
    }

    public function storeDegree(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:degrees,name'],
        ], [
            'name.min' => 'Degree name must be at least 2 letters.',
        ]);

        $degree = Degree::create($validated);

        Log::info('Admin created degree', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
            'degree_id' => $degree->id,
            'name' => $degree->name,
        ]);

        return redirect()->route('admin.degrees.index')->with('success', 'Degree added successfully');
    }

    public function editDegree(Degree $degree)
    {
        return view('admin.edit-degree', compact('degree'));
    }

    public function updateDegree(Request $request, Degree $degree): RedirectResponse
    {
        $before = $degree->only(['name']);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:degrees,name,' . $degree->id],
        ], [
            'name.min' => 'Degree name must be at least 2 letters.',
        ]);

        $degree->update($validated);

        $after = $degree->fresh()->only(['name']);
        $changedFields = [];

        foreach ($after as $field => $value) {
            if (($before[$field] ?? null) !== $value) {
                $changedFields[$field] = [
                    'from' => $before[$field] ?? null,
                    'to' => $value,
                ];
            }
        }

        Log::info('Admin updated degree', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
            'degree_id' => $degree->id,
            'changes' => $changedFields,
            'before' => $before,
            'after' => $after,
        ]);

        return redirect()->route('admin.degrees.index')->with('success', 'Degree updated successfully');
    }

    public function deleteDegree(Degree $degree): RedirectResponse
    {
        if ($degree->students()->exists()) {
            Log::warning('Admin degree delete blocked', [
                'admin_user_id' => request()->session()->get('authenticated_user_id'),
                'degree_id' => $degree->id,
                'name' => $degree->name,
                'assigned_students_count' => $degree->students()->count(),
            ]);

            return redirect()
                ->route('admin.degrees.index')
                ->with('error', 'This degree cannot be deleted because students are assigned to it.');
        }

        Log::info('Admin deleted degree', [
            'admin_user_id' => request()->session()->get('authenticated_user_id'),
            'degree_id' => $degree->id,
            'name' => $degree->name,
        ]);

        $degree->delete();

        return redirect()->route('admin.degrees.index')->with('success', 'Degree deleted successfully');
    }

    public function adminsIndex()
    {
        $admins = Admin::with('userAccount')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.admins', compact('admins'));
    }

    public function createAdmin()
    {
        return view('admin.add-admin');
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255', 'unique:user_accounts,email'],
            'username' => ['required', 'string', 'max:255', 'unique:user_accounts,username'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = UserAccount::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->e_mail,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'is_active' => 1,
            'must_change_password' => 1,
        ]);

        $admin = Admin::create([
            'user_account_id' => $user->id,
        ]);

        Log::info('Admin created admin account', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
            'new_admin_id' => $admin->id,
            'email' => $user->email,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin account added successfully.');
    }

    public function editAdmin(Admin $admin)
    {
        return view('admin.edit-admin', compact('admin'));
    }

    public function updateAdmin(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255', Rule::unique('user_accounts', 'email')->ignore($admin->userAccount->id)],
        ]);

        if ($admin->userAccount) {
            $admin->userAccount->name = $request->name;
            $admin->userAccount->email = $request->e_mail;
            $admin->userAccount->save();
        }

        Log::info('Admin updated admin account', [
            'admin_user_id' => $request->session()->get('authenticated_user_id'),
            'updated_admin_id' => $admin->id,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin updated successfully.');
    }

    public function deleteAdmin(Admin $admin)
    {
        // Prevent self-deletion
        if ($admin->userAccount->id === request()->session()->get('authenticated_user_id')) {
            return redirect()->route('admin.admins.index')->with('error', 'You cannot delete your own admin account.');
        }

        if ($admin->userAccount) {
            $admin->userAccount->delete();
        }
        $admin->delete();

        Log::info('Admin deleted admin account', [
            'admin_user_id' => request()->session()->get('authenticated_user_id'),
            'deleted_admin_id' => $admin->id,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully.');
    }
}
