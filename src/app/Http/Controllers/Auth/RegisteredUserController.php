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
use Illuminate\Support\Str;

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
        // 1. Validatsiya - Barcha fieldlarni tekshirish
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'unique:users',
                'regex:/^[a-zA-Z0-9_]+$/', // Faqat harf, raqam va _
                'not_in:admin,root,moderator,support' // Reserved usernames
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users'
            ],
            'password' => [
                'required',
                'confirmed',
                'min:8',
                Rules\Password::min(8)
                    ->letters()      // Kamida 1 ta harf
                    ->mixedCase()    // Katta va kichik harf
                    ->numbers()      // Kamida 1 ta raqam
                    ->symbols()      // Kamida 1 ta belgi (!@#$)
            ],
        ], [
            // Custom error messages
            'name.required' => 'Ismingizni kiriting',
            'name.min' => 'Ism kamida 2 ta belgidan iborat bo\'lishi kerak',
            'username.required' => 'Username kiriting',
            'username.unique' => 'Bu username band, boshqa tanlang',
            'username.regex' => 'Username faqat harf, raqam va _ dan iborat bo\'lishi mumkin',
            'username.not_in' => 'Bu username ishlatib bo\'lmaydi',
            'email.required' => 'Email manzilini kiriting',
            'email.unique' => 'Bu email allaqachon ro\'yxatdan o\'tgan',
            'password.required' => 'Parol kiriting',
            'password.min' => 'Parol kamida 8 ta belgidan iborat bo\'lishi kerak',
            'password.confirmed' => 'Parollar mos kelmadi',
        ]);

        // 2. Username'ni lowercase qilamiz
        $validated['username'] = strtolower($validated['username']);

        // 3. User yaratish
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            // Default values
            'bio' => null,
            'website' => null,
            'location' => null,
            'avatar' => null, // Default avatar User model'da handle qilinadi
        ]);

        // 4. Event fire qilish (email verification uchun)
        event(new Registered($user));

        // 5. Avtomatik login
        Auth::login($user);

        // 6. Feed sahifasiga yo'naltirish
        return redirect()->route('posts.index')
            ->with('success', 'Welcome to Social Media! 🎉');
    }
}
