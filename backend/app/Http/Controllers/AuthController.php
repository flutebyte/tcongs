<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Same "invalid credentials" message regardless of whether the email
        // exists, so login can't be used to enumerate registered accounts.
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $oldSessionId = $request->session()->getId();
        $request->session()->regenerate();
        Cart::transferSession($oldSessionId, $request->session()->getId());

        return redirect()->intended(route('account.index'))->with('success', 'Welcome back!');
    }

    public function showRegister()
    {
        // Carried over from the OTP login flow (see OtpAuthController::verifyCode)
        // when a verified phone number isn't tied to an account yet — flashed for
        // exactly one request, so a plain page refresh doesn't keep re-showing it.
        return view('auth.register', [
            'prefillPhone' => session('prefill_phone'),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'digits_between:10,15', 'unique:users,phone'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        $oldSessionId = $request->session()->getId();
        Auth::login($user);
        $request->session()->regenerate();
        Cart::transferSession($oldSessionId, $request->session()->getId());

        // ->intended() (not a flat route('account.index')) so Buy It Now →
        // unregistered phone → OTP-verified → this registration form still
        // lands back on checkout afterward, same as the login() method above
        // — url.intended survives the whole hop since nothing here calls
        // session()->invalidate()/flush(), only regenerate().
        return redirect()->intended(route('account.index'))->with('success', 'Account created — welcome!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
