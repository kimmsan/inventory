<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoEmployeesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('employees')->delete();

        \DB::table('employees')->insert([
            0 => [
                'id' => 1,
                'name' => 'Whilemina Watts',
                'emp_id' => '1',
                'slug' => 'whilemina-watts',
                'designation' => 'Sales Manager',
                'salary' => 10000.0,
                'commission' => 2.0,
                'mobile_number' => '017000000',
                'birth_date' => '1990-08-07',
                'gender' => 'Female',
                'blood_group' => 'AB+',
                'religion' => 'Christians',
                'appointment_date' => '2022-01-01',
                'joining_date' => '2022-01-01',
                'address' => 'Dhaka, Bangladesh',
                'status' => 1,
                'image_path' => 'avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
                'department_id' => 2,
                'user_id' => null,
            ],
            1 => [
                'id' => 2,
                'name' => 'Paki Wolf',
                'emp_id' => '2',
                'slug' => 'paki-wolf',
                'designation' => 'Marketing Manager',
                'salary' => 8000.0,
                'commission' => 9.0,
                'mobile_number' => '018000000',
                'birth_date' => '1990-07-11',
                'gender' => 'Male',
                'blood_group' => 'B-',
                'religion' => 'Buddhists',
                'appointment_date' => '2022-01-01',
                'joining_date' => '2022-01-01',
                'address' => 'A eligendi et aut pr',
                'status' => 1,
                'image_path' => 'avatar.png',
                'created_at' => now(),
                'updated_at' => now(),
                'department_id' => 1,
                'user_id' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'Mari Johns',
                'emp_id' => '3',
                'slug' => 'mari-johns',
                'designation' => 'Salesman',
                'salary' => 1000.0,
                'commission' => 3.0,
                'mobile_number' => '017000000',
                'birth_date' => '1990-09-11',
                'gender' => 'Male',
                'blood_group' => 'B+',
                'religion' => 'Christians',
                'appointment_date' => '2022-02-01',
                'joining_date' => '2022-02-01',
                'address' => 'Nulla quaerat aliqua',
                'status' => 1,
                'image_path' => '',
                'created_at' => now(),
                'updated_at' => now(),
                'department_id' => 2,
                'user_id' => null,
            ],
        ]);
    }
}