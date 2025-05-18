<?php

namespace Database\Seeders;

// Seeder file: UpdateDiagnosasUserIdSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Diagnosa;

class UpdateDiagnosasUserIdSeeder extends Seeder
{
    public function run()
    {
        // Contoh: Mengisi semua diagnosa dengan user_id 1
        Diagnosa::whereNull('user_id')->update(['user_id' => 1]); // Gantilah sesuai dengan logika Anda
    }
}
