<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Event;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $departments = Department::factory(5)->create();

        User::factory()->create([
            'name' => 'mehek lakhamje',
            'email' => 'lakhamjemehek@gmail.com',
            'password' => '123456',
            'role' => 'admin',
            'phone' => '8369247992',
            'college_name' => 'SIES College',
        ]);

        User::factory()->count(10)->create([
            'department_id' => fn() => $departments->random()->id,
            'role' => 'organizer',
        ]);
        User::factory()->count(10)->create([
            'department_id' => null, // Users without a department
            'role' => 'user',
        ]);

        Event::factory()->count(16)->create([
            'department_id' => fn() => $departments->random()->id,
        ]);
    }
}
