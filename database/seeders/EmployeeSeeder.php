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
            $photo = "employee_$i.webp";
            $model = Employee::factory()->withAddress()->withPhone()->create();

            $folder = "photos/employee";

            \App\Models\Photo::withoutEvents(function () use ($model, $folder, $photo) {
                $model->photos()->create([
                    'path' => "$folder/$photo",
                ]);
            });
        }
    }
}
