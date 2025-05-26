<?php

namespace Database\Seeders;

use App\Models\Line;
use Illuminate\Database\Seeder;

class LineSeeder extends Seeder
{
    public function run(): void
    {
        $lines = [
            ['line_number' => '1000', 'operator_name' => 'اپراتور 1000', 'line_type' => 'advertising', 'is_active' => true],
            ['line_number' => '50003', 'operator_name' => 'اپراتور 50003', 'line_type' => 'service', 'is_active' => true],
            ['line_number' => '9000', 'operator_name' => 'اپراتور 9000', 'line_type' => 'advertising', 'is_active' => true],
        ];

        foreach ($lines as $line) {
            Line::create($line);
        }
    }
}