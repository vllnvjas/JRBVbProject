<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserAccount;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function showLogin()
    {
        return view('format.loginPage');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $username = trim($credentials['username']);
        $normalizedUsername = strtolower($username);

        $attemptsKey = 'login-attempts:' . $normalizedUsername . '|' . $request->ip();
        $lockoutKey = 'login-lockout:' . $normalizedUsername . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($lockoutKey, 1)) {
            $seconds = RateLimiter::availableIn($lockoutKey);

            return back()
                ->withInput($request->only('username'))
                ->with('msg', "Too many failed attempts. Please wait {$seconds} seconds and try again.")
                ->with('lockout_seconds', $seconds);
        }

        try {
            $user = UserAccount::where('username', $username)->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                // If the application default hasher changed (to bcrypt) and the
                // stored password was hashed with a different algorithm (e.g.
                // argon2id), re-hash it with the current hasher on successful
                // authentication. This upgrades hashes transparently without
                // requiring users to reset passwords.
                if (Hash::needsRehash($user->password)) {
                    $user->password = Hash::make($credentials['password']);
                    $user->save();
                }
                RateLimiter::clear($attemptsKey);
                RateLimiter::clear($lockoutKey);
                $request->session()->put('authenticated_user_id', $user->id);

                if ((bool) ($user->must_change_password ?? false)) {
                    return redirect()->route('first-login.password.form');
                }

                $studentId = Student::where('user_account_id', $user->id)->value('id');

                $redirectTo = match ($user->role) {
                    'admin' => route('admin.dashboard'),
                    'teacher' => route('teacher.dashboard'),
                    'student' => $studentId ? route('students.show', $studentId) : route('students.index'),
                    default => route('students.index'),
                };

                if ($studentId && $user->role !== 'admin' && $user->role !== 'teacher' && $user->role !== 'student') {
                    $redirectTo = route('students.show', $studentId);
                }

                return redirect()->route('login')
                    ->with('success', 'Succesful login')
                    ->with('redirect_to', $redirectTo);
            }
        } catch (QueryException $e) {
            Log::error('Database error during login: ' . $e->getMessage());

            if (view()->exists('errors.503')) {
                return response()->view('errors.503', ['message' => 'Service temporarily unavailable.'], 503);
            }

            return back()->with('msg', 'Service temporarily unavailable. Please try again later.');
        }

        RateLimiter::hit($attemptsKey, 600);
        $failedAttempts = RateLimiter::attempts($attemptsKey);
        $remainingAttempts = max(0, 3 - $failedAttempts);

        $message = 'Incorrect credentials.';
        if ($remainingAttempts > 0) {
            $message = "Incorrect credentials. {$remainingAttempts} attempt(s) left.";
        } else {
            RateLimiter::hit($lockoutKey, 5);
            $seconds = RateLimiter::availableIn($lockoutKey);
            $message = "Too many failed attempts. Please wait {$seconds} seconds and try again.";
            RateLimiter::clear($attemptsKey);

            return back()
                ->withInput($request->only('username'))
                ->with('msg', $message)
                ->with('lockout_seconds', $seconds);
        }

        return back()
            ->withInput($request->only('username'))
            ->with('msg', $message);
    }

    public function showFirstLoginPasswordForm(Request $request)
    {
        $authenticatedUserId = $request->session()->get('authenticated_user_id');
        if (!$authenticatedUserId) {
            return redirect()->route('login')->with('msg', 'Please log in first.');
        }
        try {
            $user = UserAccount::find($authenticatedUserId);
            if (!$user || !(bool) ($user->must_change_password ?? false)) {
                if ($user?->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                if ($user?->role === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                }

                if ($user?->role === 'student') {
                    $studentId = Student::where('user_account_id', $user->id)->value('id');

                    return $studentId
                        ? redirect()->route('students.show', $studentId)
                        : redirect()->route('students.index');
                }

                return redirect()->route('students.index');
            }

            return view('format.firstLoginChangePassword');
        } catch (QueryException $e) {
            Log::error('Database error in showFirstLoginPasswordForm: ' . $e->getMessage());

            if (view()->exists('errors.503')) {
                return response()->view('errors.503', ['message' => 'Service temporarily unavailable.'], 503);
            }

            return redirect()->route('login')->with('msg', 'Service temporarily unavailable. Please try again later.');
        }
    }

    public function updateFirstLoginPassword(Request $request)
    {
        $authenticatedUserId = $request->session()->get('authenticated_user_id');
        if (!$authenticatedUserId) {
            return redirect()->route('login')->with('msg', 'Please log in first.');
        }
        try {
            $user = UserAccount::find($authenticatedUserId);
            if (!$user) {
                return redirect()->route('login')->with('msg', 'Account not found.');
            }

            if (!(bool) ($user->must_change_password ?? false)) {
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                if ($user->role === 'teacher') {
                    return redirect()->route('teacher.dashboard');
                }

                return redirect()->route('students.index');
            }

            $validated = $request->validate([
                'old_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'old_password.required' => 'Old password is required.',
            ]);

            if (!Hash::check($validated['old_password'], $user->password)) {
                return back()
                    ->withErrors(['old_password' => 'Old password is incorrect.'])
                    ->withInput($request->except(['old_password', 'password', 'password_confirmation']));
            }

            $user->password = Hash::make($validated['password']);
            $user->must_change_password = 0;
            $user->save();

            $redirectRoute = match ($user->role) {
                'admin' => 'admin.dashboard',
                'teacher' => 'teacher.dashboard',
                'student' => Student::where('user_account_id', $user->id)->value('id')
                    ? 'students.show'
                    : 'students.index',
                default => 'students.index',
            };

            if ($user->role === 'student') {
                $studentId = Student::where('user_account_id', $user->id)->value('id');

                if ($studentId) {
                    return redirect()->route('students.show', $studentId)
                        ->with('success', 'Password changed successfully.');
                }
            }

            return redirect()->route($redirectRoute)
                ->with('success', 'Password changed successfully.');
        } catch (QueryException $e) {
            Log::error('Database error in updateFirstLoginPassword: ' . $e->getMessage());

            if (view()->exists('errors.503')) {
                return response()->view('errors.503', ['message' => 'Service temporarily unavailable.'], 503);
            }

            return redirect()->route('login')->with('msg', 'Service temporarily unavailable. Please try again later.');
        }
    }

    public function studentProfile(Request $request)
    {
        $authenticatedUserId = $request->session()->get('authenticated_user_id');

        if (!$authenticatedUserId) {
            return redirect()->route('login')->with('msg', 'Please log in first.');
        }
        try {
            $user = UserAccount::with(['students.degree', 'students.userAccount'])->find($authenticatedUserId);

            if (!$user || $user->role !== 'student' || !$user->students) {
                return redirect()->route('login')->with('msg', 'Student profile not available.');
            }

            return view('format.studentProfile', [
                'student' => $user->students,
            ]);
        } catch (QueryException $e) {
            Log::error('Database error in studentProfile: ' . $e->getMessage());

            if (view()->exists('errors.503')) {
                return response()->view('errors.503', ['message' => 'Service temporarily unavailable.'], 503);
            }

            return redirect()->route('login')->with('msg', 'Service temporarily unavailable. Please try again later.');
        }
    }

    public function logout(Request $request)
    {
        $request->session()->forget('authenticated_user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
