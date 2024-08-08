<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatkulSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('matkuls')->insert([
            [
                'kd_mk' => 'JKD',
                'nama'  => 'Jaringan Komputer Dasar',
                'smt'   => 4,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'PJKD',
                'nama'  => 'Praktikum Jaringan Komputer Dasar',
                'smt'   => 4,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'DW',
                'nama'  => 'Desain Web',
                'smt'   => 4,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'PDW',
                'nama'  => 'Praktikum Desain Web',
                'smt'   => 2,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'PM',
                'nama'  => 'Pemrograman Mobile',
                'smt'   => 2,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'PPM',
                'nama'  => 'Praktikum Pemrograman Mobile',
                'smt'   => 2,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'BI2',
                'nama'  => 'Bahasa Inggris 2',
                'smt'   => 2,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'GK',
                'nama'  => 'Grafika Komputer',
                'smt'   => 2,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kd_mk' => 'PGK',
                'nama'  => 'Praktikum Grafika Komputer',
                'smt'   => 2,
                'sks'   => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
