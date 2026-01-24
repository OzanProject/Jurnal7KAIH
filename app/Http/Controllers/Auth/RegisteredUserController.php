<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $classes = \App\Models\ClassRoom::all();
        return view('auth.register', compact('classes'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:siswa,guru,orang_tua'],
            'class_id' => ['required_if:role,siswa', 'nullable', 'exists:classes,id'],
            'gender' => ['required_if:role,siswa', 'nullable', 'in:L,P'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => \App\Models\School::first()->id ?? null, // Default to first school for now
        ]);

        if ($request->role === 'siswa') {
            \App\Models\Student::create([
                'user_id' => $user->id,
                'class_id' => $request->class_id,
                'nama' => $user->name,
                'gender' => $request->gender,
                'nis' => null, 
                'nisn' => null,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->role === 'siswa') return redirect()->route('dashboard.siswa');
        if ($user->role === 'guru') return redirect()->route('dashboard.guru');
        if ($user->role === 'orang_tua') return redirect()->route('dashboard.orang_tua');

        return redirect(route('dashboard', absolute: false));
    }
}
