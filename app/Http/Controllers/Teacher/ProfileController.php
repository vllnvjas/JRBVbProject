<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $id = $request->session()->get('authenticated_user_id');
        $user = UserAccount::findOrFail($id);

        $parts = preg_split('/\s+/', trim($user->name ?? ''), -1, PREG_SPLIT_NO_EMPTY);
        $f = $parts[0] ?? '';
        $l = $parts[count($parts) - 1] ?? '';
        $m = '';
        if (count($parts) > 2) {
            $m = implode(' ', array_slice($parts, 1, -1));
        }

        return view('teacher.edit-profile', compact('user', 'f', 'm', 'l'));
    }

    public function update(Request $request)
    {
        $id = $request->session()->get('authenticated_user_id');
        $user = UserAccount::findOrFail($id);

        $request->validate([
            'f_name' => ['required', 'string', 'min:2', 'max:255'],
            'm_name' => ['nullable', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'e_mail' => ['required', 'email', 'max:255', Rule::unique('user_accounts', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $fullName = trim("{$request->f_name} " . ($request->m_name ? $request->m_name . ' ' : '') . "{$request->l_name}");

        $user->name = $fullName;
        $user->email = $request->e_mail;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->must_change_password = 0;
        }

        $user->save();

        Log::info('Teacher updated own profile', [
            'teacher_user_id' => $user->id,
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Profile updated successfully.');
    }
}
