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
        //Before rendering the login page, check if the user is already authenticated
        if ($request->session()->has('user_id')) {
            return redirect()->route('dashboard');
        }

        return $this->noCacheView('Auth.login');
    }

    public function showRegister(Request $request)
    {
        if ($request->session()->has('user_id')) {
            return redirect()->route('dashboard');
        }
        return $this->noCacheView('Auth.register');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $request->email]);

        //Fetch the user as an object to access properties directly
        $user = $stmt->fetch(\PDO::FETCH_OBJ);

        if ($user && password_verify($request->password, $user->password)) {
            $request->session()->put('user_id', $user->id);
            $request->session()->put('user_name', $user->name);
            //adding also the user role to the session for authorization purposes
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

    private function noCacheView(string $view)
    {
        return response()
            ->view($view)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}
