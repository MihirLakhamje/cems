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



        // Create Departments
        $cs = Department::factory()->create([
            'name' => 'Computer Science',
            'head_name' => 'Dr. Alice Smith',
            'head_email' => 'rGd8t@example.com',
            'head_phone' => '1234567890',
            'fest_type' => 'dept_fest',
            'is_active' => true,
        ]);

        $it = Department::factory()->create([
            'name' => 'Information Technology',
            'head_name' => 'Dr. Bob Johnson',
            'head_email' => 'sadFq@example.com',
            'head_phone' => '1234567891',
            'fest_type' => 'dept_fest',
            'is_active' => true,
        ]);

        $ds = Department::factory()->create([
            'name' => 'Data Science',
            'head_name' => 'Dr. Jane Doe',
            'head_email' => 'M4P5M@example.com',
            'head_phone' => '1234567892',
            'fest_type' => 'dept_fest',
            'is_active' => true,
        ]);

        $cultural = Department::factory()->create([
            'name' => 'Cultural Association',
            'head_name' => 'Mr. Charlie Brown',
            'head_email' => 'rGd55t@example.com',
            'head_phone' => '1234567894',
            'fest_type' => 'association',
            'is_active' => false,
        ]);

        $visions = Department::factory()->create([
            'name' => 'Visions',
            'head_name' => 'Ms. Eve Davis',
            'head_email' => 'rhgyw@example.com',
            'head_phone' => '1234567899',
            'fest_type' => 'clg_fest',
            'is_active' => false,
        ]);

        $rotract = Department::factory()->create([
            'name' => 'Rotract Club',
            'head_name' => 'Mr. Jhon Willow',
            'head_email' => 'rshfjs@example.com',
            'head_phone' => '1234567500',
            'fest_type' => 'association',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'mehek lakhamje',
            'email' => 'lakhamjemehek@gmail.com',
            'password' => '123456',
            'role' => 'admin',
            'phone' => '8369247992',
            'college_name' => 'SIES College',
        ]);

        User::factory()->create([
            'name' => 'mihir lakhamje',
            'email' => 'lakhamjemihir@gmail.com',
            'password' => '123456',
            'role' => 'user',
            'phone' => '8369247996',
            'college_name' => 'SIES College',
        ]);

        User::factory()->create([
            'department_id' => $it->id,
            'name' => 'mansi lakhamje',
            'email' => 'lakhamjemansi@gmail.com',
            'password' => '123456',
            'role' => 'organizer',
            'phone' => '8369247994',
            'college_name' => 'SIES College',
        ]);

        User::factory()->count(5)->create([
            'department_id' => fn() => $departments->random()->id,
            'role' => 'organizer',
        ]);
        User::factory()->count(10)->create([
            'department_id' => null, // Users without a department
            'role' => 'user',
        ]);

        // Attach Events 
        Event::factory()->create([
            'name' => 'Iterationz',
            'description' => 'Annual tech fest of IT department.',
            'start_date' => '2025-09-05',
            'end_date' => '2025-09-07',
            'event_start_date' => '2025-09-05',
            'event_end_date' => '2025-09-07',
            'location' => 'Auditorium',
            'fees' => 100,
            'department_id' => $it->id,
        ]);

        Event::factory()->create([
            'name' => 'Cypher',
            'description' => 'Coding competition for all students.',
            'start_date' => '2025-10-10',
            'end_date' => '2025-10-10',
            'event_start_date' => '2025-10-10',
            'event_end_date' => '2025-10-10',
            'location' => 'Computer Lab',
            'fees' => 50,
            'department_id' => $cs->id,
        ]);

        Event::factory()->create([
            'name' => 'Cultural Fest',
            'description' => 'Annual cultural fest of Cultural Association.',
            'start_date' => '2025-09-15',
            'end_date' => '2025-09-17',
            'event_start_date' => '2025-09-15',
            'event_end_date' => '2025-09-17',
            'location' => 'Auditorium',
            'fees' => 0,
            'department_id' => $cultural->id,
        ]);

        Event::factory()->create([
            'name' => 'Data Science Workshop',
            'description' => 'Workshop on latest trends in Data Science.',
            'start_date' => '2025-11-01',
            'end_date' => '2025-11-01',
            'event_start_date' => '2025-11-01',
            'event_end_date' => '2025-11-01',
            'location' => 'Room 101',
            'fees' => 150,
            'department_id' => $ds->id,
        ]);

        Event::factory()->create([
            'name' => 'Vision 2025',
            'description' => 'Annual college fest organized by Visions.',
            'start_date' => '2025-12-05',
            'end_date' => '2025-12-07',
            'event_start_date' => '2025-12-05',
            'event_end_date' => '2025-12-07',
            'location' => 'College Grounds',
            'fees' => 200,
            'department_id' => $visions->id,
        ]);

        Event::factory()->count(3)->create([
            'department_id' => $cs->id,
        ]);

        Event::factory()->count(5)->create([
            'department_id' => $it->id,
        ]);

        Event::factory()->count(2)->create([
            'department_id' => $ds->id,
        ]);

        Event::factory()->count(1)->create([
            'department_id' => $cultural->id,
        ]);

        Event::factory()->count(1)->create([
            'department_id' => $visions->id,
        ]);

        Event::factory()->count(2)->create([
            'department_id' => $rotract->id,
        ]);
    }
}
