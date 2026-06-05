<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::connection()->getPdo();
    }

    public function showLogin(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('dashboard');
        }

        return view('Auth.login');
    }

    public function showRegister(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('dashboard');
        }
        return view('Auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $request->email]);
        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($user && password_verify($request->password, $user->password)) {
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_role', $user->role);
            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['error' => 'Invalid email or password.']);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $validated['email']]);

        if ($stmt->fetch(\PDO::FETCH_OBJ)) {
            return back()
                ->withInput($request->only('name', 'email'))
                ->withErrors(['email' => 'An account with this email address already exists.']);
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password)
             VALUES (:name, :email, :password)"
        );

        $stmt->execute([
            ':name' => $validated['name'],
            ':email' => $validated['email'],
            ':password' => password_hash($validated['password'], PASSWORD_BCRYPT),
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful. You can now sign in.');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
