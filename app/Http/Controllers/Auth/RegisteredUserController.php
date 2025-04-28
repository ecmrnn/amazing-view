<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255', "regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]+$/u"],
            'last_name' => ['required', 'string', 'max:255', "regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]+$/u"],
            'password' => ['required', 'confirmed', Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                ],
        ]);

        // Check if the user already has a reservation
        $user = User::whereEmail($request->email)->first();

        if ($user && $user->registered_at == null) {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255'],
            ]);
    
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'registered_at' => now(),
            ]);

            event(new Registered($user));

            Auth::login($user);
        } else {
            $request->validate([
                'email' => ['required', 'string', 'lowercase', 'email:rfc,dns', 'max:255', 'unique:'.User::class],
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole('guest');

            event(new Registered($user));

            Auth::login($user);
        }

        return redirect()->route('dashboard');
    }
}   
