<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        try {
            $attempt = Auth::attempt($credentials, $request->boolean('remember'));
        } catch (\RuntimeException $e) {
            // Handle legacy/plaintext passwords: if stored password equals provided one,
            // re-hash it using the application's hasher and retry.
            if (str_contains($e->getMessage(), 'Bcrypt')) {
                $user = User::where('email', $credentials['email'])->first();
                if ($user && isset($user->password) && $user->password === $credentials['password']) {
                    $user->password = Hash::make($credentials['password']);
                    $user->save();

                    $attempt = Auth::attempt($credentials, $request->boolean('remember'));
                } else {
                    return back()
                        ->withErrors(['email' => 'The provided credentials do not match our records.'])
                        ->onlyInput('email');
                }
            } else {
                throw $e;
            }
        }

        if (! ($attempt ?? false)) {
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route($this->redirectRouteFor(Auth::user())));
    }

    public function showRegistration(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:user,admin,technician'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($this->redirectRouteFor($user));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    private function redirectRouteFor(User $user): string
    {
        if ($user->isAdmin()) {
            return 'admin.index';
        }

        if ($user->isTechnician()) {
            return 'complaints.tracking';
        }

        return 'dashboard';
    }
}
