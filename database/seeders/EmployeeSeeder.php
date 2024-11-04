<?php

namespace Database\Seeders;

use App\Models\EmployeePhoto;
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

            // Desabilitar eventos para a criação da foto
            EmployeePhoto::withoutEvents(function () use ($employee, $folder, $photo) {
                $employee->photos()->create([
                    'path' => "$folder/$photo",
                ]);
            });
        }
    }
}
