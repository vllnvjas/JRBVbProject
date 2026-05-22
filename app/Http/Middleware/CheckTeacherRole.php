<?php

namespace App\Http\Middleware;

use App\Models\UserAccount;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTeacherRole
{
    public function handle(Request $request, Closure $next): Response
    {
        $authenticatedUserId = $request->session()->get('authenticated_user_id');

        if (!$authenticatedUserId) {
            return redirect()->route('login')->with('msg', 'Please log in first.');
        }

        $user = UserAccount::find($authenticatedUserId);

        if (!$user || $user->role !== 'teacher') {
            return redirect()->route('login')->with('msg', 'You do not have permission to access the teacher dashboard.');
        }

        return $next($request);
    }
}
