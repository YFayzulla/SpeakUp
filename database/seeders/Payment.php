<?php

namespace Database\Seeders;

use App\Models\MonthlyPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Payment extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MonthlyPayment::create([
            'sum'=>400000
        ]);
    }
}
