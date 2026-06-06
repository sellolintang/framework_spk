<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('aras_results')->truncate();
        DB::table('scores')->truncate();
        DB::table('interviews')->truncate();
        DB::table('jury_criteria')->truncate();
        DB::table('criteria')->truncate();
        DB::table('candidates')->truncate();
        DB::table('election_periods')->truncate();

        DB::table('users')->whereIn('email', [
            'admin@duta.test',
            'juri1@duta.test',
            'juri2@duta.test',
            'juri3@duta.test',
        ])->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin Seleksi',
            'email' => 'admin@duta.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'phone' => '081111111111',
            'role' => 'admin',
            'is_active' => true,
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $juri1Id = DB::table('users')->insertGetId([
            'name' => 'Juri Public Speaking',
            'email' => 'juri1@duta.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'phone' => '082111111111',
            'role' => 'juri',
            'is_active' => true,
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $juri2Id = DB::table('users')->insertGetId([
            'name' => 'Juri Kepribadian',
            'email' => 'juri2@duta.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'phone' => '082222222222',
            'role' => 'juri',
            'is_active' => true,
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $juri3Id = DB::table('users')->insertGetId([
            'name' => 'Juri Institusi',
            'email' => 'juri3@duta.test',
            'email_verified_at' => $now,
            'password' => Hash::make('password'),
            'phone' => '082333333333',
            'role' => 'juri',
            'is_active' => true,
            'remember_token' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Election Period
        |--------------------------------------------------------------------------
        */

        $periodId = DB::table('election_periods')->insertGetId([
            'election_year' => 2026,
            'registration_start' => $now->copy()->subDays(7),
            'registration_end' => $now->copy()->addDays(7),
            'interview_start' => $now->copy()->addDays(8),
            'interview_end' => $now->copy()->addDays(9),
            'status' => 'scoring',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Candidates
        |--------------------------------------------------------------------------
        */

        $candidate1Id = DB::table('candidates')->insertGetId([
            'period_id' => $periodId,
            'registration_number' => 'DPNJ-2026-001',
            'full_name' => 'Aditya Saputra',
            'student_number' => '2103421045',
            'email' => 'aditya@student.pnj.ac.id',
            'phone' => '083111111111',
            'faculty' => 'Teknik',
            'study_program' => 'Teknik Informatika',
            'semester' => 5,
            'vision' => 'Menjadi representasi mahasiswa PNJ yang aktif dan komunikatif.',
            'mission' => 'Membangun citra positif PNJ melalui kegiatan akademik dan nonakademik.',
            'photo_file' => null,
            'cv_file' => null,
            'status' => 'valid',
            'validated_by' => $adminId,
            'validated_at' => $now,
            'rejection_reason' => null,
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now,
        ]);

        $candidate2Id = DB::table('candidates')->insertGetId([
            'period_id' => $periodId,
            'registration_number' => 'DPNJ-2026-002',
            'full_name' => 'Rina Mutia',
            'student_number' => '2107411012',
            'email' => 'rina@student.pnj.ac.id',
            'phone' => '083222222222',
            'faculty' => 'Akuntansi',
            'study_program' => 'Akuntansi Terapan',
            'semester' => 5,
            'vision' => 'Mewakili PNJ dengan sikap profesional dan percaya diri.',
            'mission' => 'Meningkatkan partisipasi mahasiswa dalam kegiatan kampus.',
            'photo_file' => null,
            'cv_file' => null,
            'status' => 'interview_scheduled',
            'validated_by' => $adminId,
            'validated_at' => $now,
            'rejection_reason' => null,
            'created_at' => $now->copy()->subDays(4),
            'updated_at' => $now,
        ]);

        $candidate3Id = DB::table('candidates')->insertGetId([
            'period_id' => $periodId,
            'registration_number' => 'DPNJ-2026-003',
            'full_name' => 'Bagus Kurniawan',
            'student_number' => '2204311088',
            'email' => 'bagus@student.pnj.ac.id',
            'phone' => '083333333333',
            'faculty' => 'Teknik Mesin',
            'study_program' => 'Teknik Mesin',
            'semester' => 3,
            'vision' => 'Menjadi duta yang inspiratif bagi mahasiswa.',
            'mission' => 'Mendukung kegiatan kampus secara aktif dan bertanggung jawab.',
            'photo_file' => null,
            'cv_file' => null,
            'status' => 'scored',
            'validated_by' => $adminId,
            'validated_at' => $now,
            'rejection_reason' => null,
            'created_at' => $now->copy()->subDays(3),
            'updated_at' => $now,
        ]);

        $candidate4Id = DB::table('candidates')->insertGetId([
            'period_id' => $periodId,
            'registration_number' => 'DPNJ-2026-004',
            'full_name' => 'Nadia Putri',
            'student_number' => '2205521099',
            'email' => 'nadia@student.pnj.ac.id',
            'phone' => '083444444444',
            'faculty' => 'Administrasi Niaga',
            'study_program' => 'Administrasi Bisnis',
            'semester' => 3,
            'vision' => 'Menjadi perwakilan mahasiswa yang berintegritas.',
            'mission' => 'Mengenalkan nilai positif PNJ kepada masyarakat.',
            'photo_file' => null,
            'cv_file' => null,
            'status' => 'pending',
            'validated_by' => null,
            'validated_at' => null,
            'rejection_reason' => null,
            'created_at' => $now->copy()->subDays(2),
            'updated_at' => $now,
        ]);

        $candidate5Id = DB::table('candidates')->insertGetId([
            'period_id' => $periodId,
            'registration_number' => 'DPNJ-2026-005',
            'full_name' => 'Fajar Ramadhan',
            'student_number' => '2306612033',
            'email' => 'fajar@student.pnj.ac.id',
            'phone' => '083555555555',
            'faculty' => 'Teknik Elektro',
            'study_program' => 'Teknik Elektro',
            'semester' => 2,
            'vision' => 'Menjadi mahasiswa yang aktif dalam pengembangan citra kampus.',
            'mission' => 'Mengikuti kegiatan seleksi dengan bertanggung jawab.',
            'photo_file' => null,
            'cv_file' => null,
            'status' => 'invalid',
            'validated_by' => $adminId,
            'validated_at' => $now,
            'rejection_reason' => 'Berkas pendaftaran belum lengkap.',
            'created_at' => $now->copy()->subDays(1),
            'updated_at' => $now,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Criteria
        |--------------------------------------------------------------------------
        |
        | Kriteria disesuaikan dengan data asli Excel Duta PNJ Batch 5.
        | Di file Excel, Opening & Public Speaking muncul di dua bagian penilaian.
        | Karena tabel criteria punya unique period_id + name, bobotnya digabung
        | menjadi 0.1300 agar total bobot tetap 1.0000.
        |
        */

        $criteria = [
            [
                'code' => 'C1',
                'name' => 'Ekspresi, Etika, Kepercayaan Diri',
                'weight' => 0.0800,
            ],
            [
                'code' => 'C2',
                'name' => 'Berpikir Kritis, Kreatif, Inisiatif,',
                'weight' => 0.0800,
            ],
            [
                'code' => 'C3',
                'name' => 'Komitmen Terhadap Duta dan Institusi',
                'weight' => 0.0700,
            ],
            [
                'code' => 'C4',
                'name' => 'Kemampuan Komunikasi, Argumentasi',
                'weight' => 0.0800,
            ],
            [
                'code' => 'C5',
                'name' => 'Personaliti Kepemimpinan & Keteraturan',
                'weight' => 0.0700,
            ],
            [
                'code' => 'C6',
                'name' => 'Manajemen Emosi',
                'weight' => 0.0500,
            ],
            [
                'code' => 'C7',
                'name' => 'Sikap Kooperatif',
                'weight' => 0.0500,
            ],
            [
                'code' => 'C8',
                'name' => 'Penilaian Diri dan Tujuan Pribadi',
                'weight' => 0.0500,
            ],
            [
                'code' => 'C9',
                'name' => 'Opening & Public Speaking',
                'weight' => 0.1300,
            ],
            [
                'code' => 'C10',
                'name' => 'Wawasan & Keilmuan',
                'weight' => 0.1000,
            ],
            [
                'code' => 'C11',
                'name' => 'Kemampuan Berbahasa inggris',
                'weight' => 0.0800,
            ],
            [
                'code' => 'C12',
                'name' => 'Personaliti Kepemimpinan',
                'weight' => 0.0500,
            ],
            [
                'code' => 'C13',
                'name' => 'Body Language',
                'weight' => 0.0400,
            ],
            [
                'code' => 'C14',
                'name' => 'Kreativitas dan Invoasi',
                'weight' => 0.0400,
            ],
            [
                'code' => 'C15',
                'name' => 'Grooming & Look',
                'weight' => 0.0300,
            ],
        ];

        $criterionIds = [];

        foreach ($criteria as $criterion) {
            $criterionIds[$criterion['code']] = DB::table('criteria')->insertGetId([
                'period_id' => $periodId,
                'code' => $criterion['code'],
                'name' => $criterion['name'],
                'weight' => $criterion['weight'],
                'type' => 'benefit',
                'min_score' => 0,
                'max_score' => 100,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Jury Criteria
        |--------------------------------------------------------------------------
        |
        | Pembagian mengikuti sheet asli:
        | Juri 1: C1-C8
        | Juri 2: C1-C4 dan C9-C12
        | Juri 3: C1-C4, C9, C13-C15
        |
        */

        $juryCriteriaMap = [
            $juri1Id => ['C1', 'C2', 'C3', 'C4', 'C5', 'C6', 'C7', 'C8'],
            $juri2Id => ['C1', 'C2', 'C3', 'C4', 'C9', 'C10', 'C11', 'C12'],
            $juri3Id => ['C1', 'C2', 'C3', 'C4', 'C9', 'C13', 'C14', 'C15'],
        ];

        foreach ($juryCriteriaMap as $juryId => $criterionCodes) {
            foreach ($criterionCodes as $code) {
                DB::table('jury_criteria')->insert([
                    'period_id' => $periodId,
                    'user_id' => $juryId,
                    'criterion_id' => $criterionIds[$code],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Interviews
        |--------------------------------------------------------------------------
        */

        DB::table('interviews')->insert([
            [
                'period_id' => $periodId,
                'candidate_id' => $candidate2Id,
                'scheduled_at' => $now->copy()->addDays(8)->setTime(9, 0),
                'location' => 'Ruang Wawancara 1',
                'status' => 'scheduled',
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'period_id' => $periodId,
                'candidate_id' => $candidate3Id,
                'scheduled_at' => $now->copy()->addDays(8)->setTime(9, 15),
                'location' => 'Ruang Wawancara 1',
                'status' => 'completed',
                'created_by' => $adminId,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Scores
        |--------------------------------------------------------------------------
        |
        | Nilai lama tetap dipertahankan pada kriteria lama yang masih ada.
        | Nilai tambahan dibuat agar setiap scored candidate punya nilai lengkap
        | sesuai pembagian kriteria juri di atas.
        |
        */

        $scoreRows = [
            [
                'candidate_id' => $candidate3Id,
                'jury_id' => $juri1Id,
                'scores' => [
                    'C1' => 88,
                    'C2' => 87,
                    'C3' => 92,
                    'C4' => 90,
                    'C5' => 86,
                    'C6' => 84,
                    'C7' => 88,
                    'C8' => 85,
                ],
            ],
            [
                'candidate_id' => $candidate3Id,
                'jury_id' => $juri2Id,
                'scores' => [
                    'C1' => 89,
                    'C2' => 85,
                    'C3' => 92,
                    'C4' => 90,
                    'C9' => 88,
                    'C10' => 86,
                    'C11' => 85,
                    'C12' => 87,
                ],
            ],
            [
                'candidate_id' => $candidate3Id,
                'jury_id' => $juri3Id,
                'scores' => [
                    'C1' => 90,
                    'C2' => 88,
                    'C3' => 92,
                    'C4' => 89,
                    'C9' => 88,
                    'C13' => 87,
                    'C14' => 86,
                    'C15' => 88,
                ],
            ],
            [
                'candidate_id' => $candidate2Id,
                'jury_id' => $juri1Id,
                'scores' => [
                    'C1' => 80,
                    'C2' => 83,
                    'C3' => 86,
                    'C4' => 84,
                    'C5' => 82,
                    'C6' => 80,
                    'C7' => 81,
                    'C8' => 83,
                ],
            ],
            [
                'candidate_id' => $candidate2Id,
                'jury_id' => $juri2Id,
                'scores' => [
                    'C1' => 82,
                    'C2' => 82,
                    'C3' => 86,
                    'C4' => 84,
                    'C9' => 83,
                    'C10' => 82,
                    'C11' => 80,
                    'C12' => 82,
                ],
            ],
            [
                'candidate_id' => $candidate2Id,
                'jury_id' => $juri3Id,
                'scores' => [
                    'C1' => 83,
                    'C2' => 84,
                    'C3' => 86,
                    'C4' => 84,
                    'C9' => 84,
                    'C13' => 82,
                    'C14' => 81,
                    'C15' => 83,
                ],
            ],
        ];

        foreach ($scoreRows as $row) {
            foreach ($row['scores'] as $criterionCode => $score) {
                DB::table('scores')->insert([
                    'period_id' => $periodId,
                    'candidate_id' => $row['candidate_id'],
                    'user_id' => $row['jury_id'],
                    'criterion_id' => $criterionIds[$criterionCode],
                    'score' => $score,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | ARAS Results
        |--------------------------------------------------------------------------
        |
        | Data ini hanya dummy awal. Untuk hasil final, tetap jalankan fitur
        | hitung ulang ARAS dari aplikasi agar hasil mengikuti logic service terbaru.
        |
        */

        DB::table('aras_results')->insert([
            [
                'period_id' => $periodId,
                'candidate_id' => $candidate3Id,
                'total_score' => 0.339634,
                'utility_score' => 1.000000,
                'final_rank' => 1,
                'calculated_by' => $adminId,
                'calculated_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'period_id' => $periodId,
                'candidate_id' => $candidate2Id,
                'total_score' => 0.320731,
                'utility_score' => 0.944342,
                'final_rank' => 2,
                'calculated_by' => $adminId,
                'calculated_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
