<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenghuniSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kontrakData = [
            [
                'nama_lengkap' => 'John Doe',
                'foto_ktp' => 'default.jpg',
                'status_penghuni' => 'Kontrak',
                'nomor_telepon' => '081234567891',
                'status_pernikahan' => 'Menikah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Jane Doe',
                'foto_ktp' => 'default.jpg',
                'status_penghuni' => 'Kontrak',
                'nomor_telepon' => '081234567892',
                'status_pernikahan' => 'Menikah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Adam Smith',
                'foto_ktp' => 'default.jpg',
                'status_penghuni' => 'Kontrak',
                'nomor_telepon' => '081234567893',
                'status_pernikahan' => 'Single',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Eve Johnson',
                'foto_ktp' => 'default.jpg',
                'status_penghuni' => 'Kontrak',
                'nomor_telepon' => '081234567894',
                'status_pernikahan' => 'Menikah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_lengkap' => 'Mark Wilson',
                'foto_ktp' => 'default.jpg',
                'status_penghuni' => 'Kontrak',
                'nomor_telepon' => '081234567895',
                'status_pernikahan' => 'Single',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('penghuni')->insert($kontrakData);
        $tetapData = [];

        $firstNames = ['Michael', 'Jessica', 'Christopher', 'Ashley', 'Matthew', 'Amanda', 'Joshua', 'Jennifer', 'David', 'Elizabeth'];

        for ($i = 1; $i <= 15; $i++) {
            $tetapData[] = [
                'nama_lengkap' => $firstNames[rand(0, count($firstNames) - 1)] . ' ' . 'Doe',
                'foto_ktp' => 'default.jpg',
                'status_penghuni' => 'Tetap',
                'nomor_telepon' => '0812345678' . $i,
                'status_pernikahan' => 'Menikah',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('penghuni')->insert($tetapData);
    }
}
