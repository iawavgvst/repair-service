<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Форма входа
     *
     *  Метод GET: /login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Обработка входа
     *
     *  Метод POST: /login
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isDispatcher()) {
                return redirect()->route('dispatcher.index'); // страница для диспетчера
            } elseif ($user->isMaster()) {
                return redirect()->route('master.index'); // страница для мастера
            }
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ])->onlyInput('email');
    }

    /**
     * Выход из системы
     *
     *  Метод POST: /logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
