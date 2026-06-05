<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //Admin user
        $hashedPassword = password_hash('passwordAdmin123', PASSWORD_BCRYPT);

        DB::table('users')->insert([
            'name' => 'Administrator',
            'email' => 'admin@profiles.com',
            'password' => $hashedPassword,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        //Employee Records

        DB::table('employee_records')->insert(
            [
                [
                    'employee_name'  => 'John Doe',
                    'position'   => 'Software Engineer',
                    'email'      => 'johndoe@company.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'employee_name'  => 'Jane Smith',
                    'position'   => 'Project Manager',
                    'email'      => 'janesmith@company.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'employee_name'  => 'Mark Villanueva',
                    'position'   => 'Database Administrator',
                    'email'      => 'markv@company.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
