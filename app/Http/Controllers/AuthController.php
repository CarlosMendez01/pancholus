<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Inicia sesión y redirige según el rol del usuario.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) { // valida las credenciales en la db.
            return back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()
        ->intended($this->homeFor(Auth::user()->role_id));
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Resuelve el destino por defecto según el rol del usuario.
     */
    private function homeFor(int $roleId): string
    {
        return match ($roleId) {
            1 => route('cliente.menu'),
            3 => route('empleado.pedidos.index'),
            default => route('admin.empleados.index'),
        };
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'integer'],
            'email' => ['nullable', 'string'],
            'password' => ['required']
        ]);

        User::create([
            'name' => $request->name,
            'dni' => $request->phone,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => $request->password,
            'role_id' => 1
        ]);

        return redirect()
        ->route('login');
    }
}
