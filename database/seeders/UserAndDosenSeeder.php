<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAndDosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosenData = [
            ['nip' => '687240280095093489', 'nidn' => '4244849895', 'nama' => 'WENDHI YUNIARTO, ST.,MT', 'is_kaprodi' => 0],
            ['nip' => '197302061995011001', 'nidn' => '6027306', 'nama' => 'FERRY FAISAL, SST.,MT', 'is_kaprodi' => 0],
            ['nip' => '006751175192088314', 'nidn' => '5215248799', 'nama' => 'MARIANA SYAMSUDIN, ST.,MT., PhD', 'is_kaprodi' => 1],
            ['nip' => '386346682674060220', 'nidn' => '2285726529', 'nama' => 'IR. ABU BAKAR', 'is_kaprodi' => 0],
            ['nip' => '251809105136721447', 'nidn' => '5252223615', 'nama' => 'YUNITA, ST.,M.SC', 'is_kaprodi' => 0],
            ['nip' => '815342245254416531', 'nidn' => '281824559', 'nama' => 'MUHAMMAD HASBI, ST.,MT', 'is_kaprodi' => 0],
            ['nip' => '304687564980461225', 'nidn' => '5267497323', 'nama' => 'NENY FIRDYANTI, ST.,MT', 'is_kaprodi' => 0],
            ['nip' => '306488997250044619', 'nidn' => '5212682945', 'nama' => 'DR. ARDI MARWAN', 'is_kaprodi' => 0],
            ['nip' => '470032806155924912', 'nidn' => '1224206244', 'nama' => 'NURUL FADILLAH, S.PD.,M.ED,TESSOL', 'is_kaprodi' => 0],
            ['nip' => '994297516269447018', 'nidn' => '4217428790', 'nama' => 'H. IRAWAN SUHARTO, ST', 'is_kaprodi' => 0],
            ['nip' => '606467138608304765', 'nidn' => '5270822579', 'nama' => 'WAWAN HERYAWAN, ST.,MT', 'is_kaprodi' => 0],
            ['nip' => '157593920080017035', 'nidn' => '5260630694', 'nama' => 'RAMLI, ST.,MT', 'is_kaprodi' => 0],
            ['nip' => '979528368778498101', 'nidn' => '3246452420', 'nama' => 'YASIR ARAFAT, SST., MT', 'is_kaprodi' => 0],
            ['nip' => '175016049479603243', 'nidn' => '1253229447', 'nama' => 'SUHERI, ST., M.CS', 'is_kaprodi' => 0],
            ['nip' => '211317077313112937', 'nidn' => '3238885571', 'nama' => 'FRESKA ROLANSA, ST, M.CS', 'is_kaprodi' => 0],
            ['nip' => '194163352113998976', 'nidn' => '6267956128', 'nama' => 'M. ILYAS HADIKUSUMA, ST.,M.ENG', 'is_kaprodi' => 0],
            ['nip' => '758363406712984415', 'nidn' => '2295550578', 'nama' => 'BUDIANINGSIH, ST.,MT', 'is_kaprodi' => 0],
            ['nip' => '521747708450415011', 'nidn' => '260336329', 'nama' => 'FITRI WIBOWO, S.ST., MT', 'is_kaprodi' => 0],
            ['nip' => '712957946152129056', 'nidn' => '9228258191', 'nama' => 'PAUSTA YUGIANAUS, S.KOM, MT', 'is_kaprodi' => 0],
            ['nip' => '712957946152129057', 'nidn' => '9228258192', 'nama' => 'RAHMAD WAHID, M.CS', 'is_kaprodi' => 0],
            ['nip' => '712957946152129058', 'nidn' => '9228258193', 'nama' => 'TRI BOWO ATMOJO, S.T., M.CS.', 'is_kaprodi' => 0],
            ['nip' => '712957946152129059', 'nidn' => '9228258194', 'nama' => 'PZULKIFLI AL HAJAR, S.PD.I., M.S.I', 'is_kaprodi' => 0],
            ['nip' => '712957946152129060', 'nidn' => '9228258195', 'nama' => 'LINDUNG SISWANTO, S.KOM., M.ENG', 'is_kaprodi' => 0],
            ['nip' => '712957946152129061', 'nidn' => '9228258196', 'nama' => 'TOMMI SURYANTO, S.KOM, M.KOM', 'is_kaprodi' => 0],
            ['nip' => '712957946152129062', 'nidn' => '9228258197', 'nama' => 'MUHAMMAD DIPONEGORO, S.KOM . M.CS', 'is_kaprodi' => 0],
            ['nip' => '712957946152129063', 'nidn' => '9228258198', 'nama' => 'SARAH BIBI, SST., M.PD', 'is_kaprodi' => 0],
            ['nip' => '712957946152129064', 'nidn' => '9228258199', 'nama' => 'SUHARSONO, S.KOM., M.KOM', 'is_kaprodi' => 0],
            ['nip' => '712957946152129065', 'nidn' => '9228258200', 'nama' => 'NOVI ARYANI FITRI, S.T., M.TR.KOM', 'is_kaprodi' => 0],
            ['nip' => '712957946152129066', 'nidn' => '9228258201', 'nama' => 'SAFRI ADAM, S.KOM., M.KOM', 'is_kaprodi' => 0],
        ];

        foreach ($dosenData as $dosen) {
            $userId = DB::table('users')->insertGetId([
                'role' => '2', // Assuming role '1' is for Dosen
                'name' => $dosen['nama'],
                'nomor_induk' => $dosen['nip'],
                'email' => strtolower(str_replace(' ', '.', $dosen['nama'])) . '@example.com', // Generate a dummy email
                'password' => Hash::make('password123'),
            ]);

            DB::table('dosens')->insert([
                'user_id' => $userId,
                'nip' => $dosen['nip'],
                'nidn' => $dosen['nidn'],
                'nama' => $dosen['nama'],
                'is_kaprodi' => $dosen['is_kaprodi'],
                'no_hp' => '08123456789', // Default phone number
                'foto' => 'default.png', // Default photo
            ]);
        }
    }
}
