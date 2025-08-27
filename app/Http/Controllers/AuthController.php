<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Processa o login do usuário
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Atualizar informações de login
            $user = Auth::user();
            $user->updateLastLogin($request->ip());

            // Log da atividade
            ActivityLog::log('login', 'User', "Usuário {$user->name} fez login", $user->id);

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => 'As credenciais informadas não conferem com nossos registros.',
        ]);
    }

    /**
     * Faz logout do usuário
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
