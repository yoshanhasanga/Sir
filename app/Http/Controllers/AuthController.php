<?php

namespace App\Http\Controllers;

use App\Models\SecurityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the secured login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('timetable.index');
        }

        return view('auth.login');
    }

    /**
     * Handle high-security login request with brute-force protection.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $ip = $request->ip();
        $throttleKey = Str::transliterate(Str::lower($credentials['email']) . '|' . $ip);

        // Security: Rate limiting check (max 5 attempts per 60 seconds)
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            SecurityLog::logEvent(
                null,
                'rate_limited_lockout',
                $ip,
                $request->userAgent(),
                ['email' => $credentials['email'], 'locked_seconds' => $seconds]
            );

            return back()->withInput($request->only('email'))
                ->withErrors([
                    'email' => "Security Lockout: Too many failed login attempts. Please wait {$seconds} seconds before trying again.",
                ]);
        }

        $remember = $request->boolean('remember');

        // Check user existence & active status
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !$user->is_active) {
            RateLimiter::hit($throttleKey, 60);
            SecurityLog::logEvent(
                $user ? $user->id : null,
                'login_failed_inactive_or_not_found',
                $ip,
                $request->userAgent(),
                ['email' => $credentials['email']]
            );

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Invalid credentials or account is suspended.']);
        }

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);

            // Security: Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Record login metadata
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $ip,
            ]);

            SecurityLog::logEvent(
                $user->id,
                'login_success',
                $ip,
                $request->userAgent(),
                ['role' => $user->role, 'designation' => $user->designation]
            );

            return redirect()->intended(route('timetable.index'))
                ->with('status', 'Welcome back, ' . $user->name . '! Logged in as ' . $user->designation);
        }

        // Record failed attempt
        RateLimiter::hit($throttleKey, 60);
        $remainingAttempts = RateLimiter::remaining($throttleKey, 5);

        SecurityLog::logEvent(
            $user->id,
            'login_failed_bad_password',
            $ip,
            $request->userAgent(),
            ['email' => $credentials['email'], 'remaining_attempts' => $remainingAttempts]
        );

        return back()->withInput($request->only('email'))
            ->withErrors([
                'email' => "Invalid credentials. You have {$remainingAttempts} attempt(s) remaining before security lockout.",
            ]);
    }

    /**
     * Handle secure logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user) {
            SecurityLog::logEvent(
                $user->id,
                'logout',
                $request->ip(),
                $request->userAgent(),
                ['email' => $user->email]
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('status', 'You have been securely signed out.');
    }
}

