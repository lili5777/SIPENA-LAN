<?php
// database/seeders/CleanupMentorDuplicatesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupMentorDuplicatesSeeder extends Seeder
{
    public function run(): void
    {
        echo "🔧 MEMULAI CLEANUP MENTOR DUPLIKAT\n";
        echo "==================================\n\n";

        $this->cleanDuplicatesByNip();
        $this->mergeSimilarMentors();
        $this->reportResults();
    }

    private function cleanDuplicatesByNip(): void
    {
        echo "1. MENCARI MENTOR DENGAN NIP SAMA:\n";
        
        // Cari NIP yang muncul lebih dari 1 kali
        $duplicateNips = DB::table('mentor')
            ->select('nip_mentor', DB::raw('COUNT(*) as count'))
            ->whereNotNull('nip_mentor')
            ->where('nip_mentor', '!=', '')
            ->groupBy('nip_mentor')
            ->having('count', '>', 1)
            ->get();

        if ($duplicateNips->isEmpty()) {
            echo "   ✅ Tidak ditemukan NIP duplikat\n\n";
            return;
        }

        echo "   📊 Ditemukan " . $duplicateNips->count() . " NIP duplikat\n\n";

        foreach ($duplicateNips as $dup) {
            echo "   🔍 NIP: {$dup->nip_mentor} (ada {$dup->count} duplikat)\n";
            
            // Ambil semua mentor dengan NIP ini (order by id, bukan id_mentor)
            $mentors = DB::table('mentor')
                ->where('nip_mentor', $dup->nip_mentor)
                ->orderBy('id') // PERUBAHAN DI SINI
                ->get();

            $keepMentor = $mentors->first();
            $deleteMentors = $mentors->slice(1);

            foreach ($deleteMentors as $deleteMentor) {
                // 1. Pindahkan semua peserta ke mentor pertama
                $affected = DB::table('peserta_mentor')
                    ->where('id_mentor', $deleteMentor->id) // PERUBAHAN DI SINI
                    ->update(['id_mentor' => $keepMentor->id]); // PERUBAHAN DI SINI

                if ($affected > 0) {
                    echo "      📝 Pindah {$affected} peserta ke mentor ID {$keepMentor->id}\n";
                }

                // 2. Gabungkan data jika ada yang kosong
                $this->mergeMentorData($keepMentor, $deleteMentor);

                // 3. Hapus mentor duplikat
                DB::table('mentor')->where('id', $deleteMentor->id)->delete(); // PERUBAHAN DI SINI
                echo "      🗑️  Hapus mentor ID {$deleteMentor->id} ({$deleteMentor->nama_mentor})\n";
            }
        }
        echo "\n";
    }

    private function mergeMentorData($keepMentor, $deleteMentor): void
    {
        $updates = [];

        // Gabungkan data yang lebih lengkap
        $fields = ['jabatan_mentor', 'nomor_rekening', 'npwp_mentor', 'email_mentor', 'nomor_hp_mentor'];
        
        foreach ($fields as $field) {
            if (empty($keepMentor->$field) && !empty($deleteMentor->$field)) {
                $updates[$field] = $deleteMentor->$field;
            }
        }

        if (!empty($updates)) {
            DB::table('mentor')
                ->where('id', $keepMentor->id) // PERUBAHAN DI SINI
                ->update($updates);
            
            echo "      🔄 Update data mentor: " . implode(', ', array_keys($updates)) . "\n";
        }
    }

    private function mergeSimilarMentors(): void
    {
        echo "2. MENCARI MENTOR DENGAN NAMA SAMA (NIP KOSONG):\n";
        
        // Cari nama mentor yang sama tapi NIP kosong
        $similarNames = DB::table('mentor')
            ->select('nama_mentor', DB::raw('COUNT(*) as count'))
            ->where(function($query) {
                $query->whereNull('nip_mentor')
                      ->orWhere('nip_mentor', '');
            })
            ->groupBy('nama_mentor')
            ->having('count', '>', 1)
            ->get();

        if ($similarNames->isEmpty()) {
            echo "   ✅ Tidak ditemukan nama duplikat\n\n";
            return;
        }

        echo "   📊 Ditemukan " . $similarNames->count() . " nama duplikat\n\n";

        foreach ($similarNames as $dup) {
            echo "   🔍 Nama: {$dup->nama_mentor} (ada {$dup->count} duplikat)\n";
            
            $mentors = DB::table('mentor')
                ->where('nama_mentor', $dup->nama_mentor)
                ->where(function($query) {
                    $query->whereNull('nip_mentor')
                          ->orWhere('nip_mentor', '');
                })
                ->orderBy('id') // PERUBAHAN DI SINI
                ->get();

            $keepMentor = $mentors->first();
            $deleteMentors = $mentors->slice(1);

            foreach ($deleteMentors as $deleteMentor) {
                // Pindahkan peserta
                $affected = DB::table('peserta_mentor')
                    ->where('id_mentor', $deleteMentor->id) // PERUBAHAN DI SINI
                    ->update(['id_mentor' => $keepMentor->id]); // PERUBAHAN DI SINI

                if ($affected > 0) {
                    echo "      📝 Pindah {$affected} peserta\n";
                }

                // Gabungkan data
                $this->mergeMentorData($keepMentor, $deleteMentor);

                // Hapus
                DB::table('mentor')->where('id', $deleteMentor->id)->delete(); // PERUBAHAN DI SINI
                echo "      🗑️  Hapus mentor ID {$deleteMentor->id}\n";
            }
        }
        echo "\n";
    }

    private function reportResults(): void
    {
        echo "📊 HASIL SETELAH CLEANUP:\n";
        echo "========================\n\n";

        // Total mentor
        $total = DB::table('mentor')->count();
        echo "1. TOTAL MENTOR: {$total}\n";

        // Mentor dengan NIP
        $withNip = DB::table('mentor')
            ->whereNotNull('nip_mentor')
            ->where('nip_mentor', '!=', '')
            ->count();
        echo "2. MENTOR DENGAN NIP: {$withNip}\n";

        // Mentor tanpa NIP
        $withoutNip = $total - $withNip;
        echo "3. MENTOR TANPA NIP: {$withoutNip}\n";

        // Cek NIP duplikat (seharusnya 0)
        $duplicateNips = DB::table('mentor')
            ->select('nip_mentor', DB::raw('COUNT(*) as count'))
            ->whereNotNull('nip_mentor')
            ->where('nip_mentor', '!=', '')
            ->groupBy('nip_mentor')
            ->having('count', '>', 1)
            ->count();
        
        echo "4. NIP DUPLIKAT (harusnya 0): {$duplicateNips}\n";
        
        if ($duplicateNips === 0) {
            echo "   ✅ SELAMAT! Tidak ada NIP duplikat\n";
        } else {
            echo "   ⚠️  MASIH ADA NIP DUPLIKAT! Jalankan seeder lagi.\n";
        }

        echo "\n✨ CLEANUP SELESAI!\n";
    }
}