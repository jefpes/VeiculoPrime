<?php

namespace Database\Seeders;

use App\Models\{Employee};
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $photo    = "employee_$i.webp";
            $employee = Employee::factory()->withAddress()->create();

            $folder = "employee_photos";

            $employee->photos()->create([
                'photo_name' => $photo,
                'format'     => 'webp',
                'full_path'  => base_path("storage/app/$folder/$photo"),
                'path'       => "storage/$folder/$photo",
            ]);
        }
    }
}
