<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = DB::connection()->getPdo();
    }

    public function index(Request $request)
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        $stmt = $this->pdo->prepare(
            "SELECT id, employee_name AS full_name, position, email, created_at, updated_at
             FROM employee_records
             ORDER BY id DESC"
        );
        $stmt->execute();
        $records = $stmt->fetchAll(\PDO::FETCH_OBJ);

        return $this->noCacheView('Admin.dashboard', compact('records'));
    }

    public function store(Request $request)
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($this->employeeEmailExists($validated['email'])) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'An employee with this email address already exists.']);
        }

        $stmt = $this->pdo->prepare(
            "INSERT INTO employee_records (employee_name, position, email, created_at, updated_at)
             VALUES (:employee_name, :position, :email, :created_at, :updated_at)"
        );

        $now = now();
        $stmt->execute([
            ':employee_name' => $validated['employee_name'],
            ':position' => $validated['position'],
            ':email' => $validated['email'],
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        return redirect()->route('dashboard')->with('success', 'Employee record created successfully.');
    }

    public function update(Request $request, int $id)
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        if (!$this->employeeExists($id)) {
            return redirect()->route('dashboard')->withErrors(['record' => 'Employee record not found.']);
        }

        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($this->employeeEmailExists($validated['email'], $id)) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Another employee already uses this email address.']);
        }

        $stmt = $this->pdo->prepare(
            "UPDATE employee_records
             SET employee_name = :employee_name,
                 position = :position,
                 email = :email,
                 updated_at = :updated_at
             WHERE id = :id"
        );

        $stmt->execute([
            ':employee_name' => $validated['employee_name'],
            ':position' => $validated['position'],
            ':email' => $validated['email'],
            ':updated_at' => now(),
            ':id' => $id,
        ]);

        return redirect()->route('dashboard')->with('success', 'Employee record updated successfully.');
    }

    public function destroy(Request $request, int $id)
    {
        if (!$request->session()->has('user_id')) {
            return redirect()->route('login');
        }

        if (!$this->employeeExists($id)) {
            return redirect()->route('dashboard')->withErrors(['record' => 'Employee record not found.']);
        }

        $stmt = $this->pdo->prepare("DELETE FROM employee_records WHERE id = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return redirect()->route('dashboard')->with('success', 'Employee record deleted successfully.');
    }

    private function employeeExists(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM employee_records WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetch(\PDO::FETCH_OBJ);
    }

    private function employeeEmailExists(string $email, ?int $exceptId = null): bool
    {
        if ($exceptId) {
            $stmt = $this->pdo->prepare(
                "SELECT id FROM employee_records
                 WHERE email = :email AND id != :id
                 LIMIT 1"
            );
            $stmt->execute([
                ':email' => $email,
                ':id' => $exceptId,
            ]);

            return (bool) $stmt->fetch(\PDO::FETCH_OBJ);
        }

        $stmt = $this->pdo->prepare("SELECT id FROM employee_records WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        return (bool) $stmt->fetch(\PDO::FETCH_OBJ);
    }

    private function noCacheView(string $view, array $data = [])
    {
        return response()
            ->view($view, $data)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 1990 00:00:00 GMT');
    }
}
