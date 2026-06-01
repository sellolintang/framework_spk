<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Criterion;
use App\Models\ElectionPeriod;
use App\Models\Interview;
use App\Models\JuryCriterion;
use App\Models\Score;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Default Password
        |--------------------------------------------------------------------------
        |
        | Semua akun login menggunakan password:
        | P@ssw0rd
        |
        */

        $defaultPassword = 'P@ssw0rd';

        /*
        |--------------------------------------------------------------------------
        | Users: Admin dan Juri
        |--------------------------------------------------------------------------
        */

        $admin = User::updateOrCreate(
            ['email' => 'admin@pnj.ac.id'],
            [
                'name' => 'Admin Seleksi Duta PNJ',
                'password' => Hash::make($defaultPassword),
                'phone' => '081200000001',
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $juri1 = User::updateOrCreate(
            ['email' => 'juri1@pnj.ac.id'],
            [
                'name' => 'Bambang Prasetyo',
                'password' => Hash::make($defaultPassword),
                'phone' => '081200000002',
                'role' => 'juri',
                'is_active' => true,
            ]
        );

        $juri2 = User::updateOrCreate(
            ['email' => 'juri2@pnj.ac.id'],
            [
                'name' => 'Siti Rahmawati',
                'password' => Hash::make($defaultPassword),
                'phone' => '081200000003',
                'role' => 'juri',
                'is_active' => true,
            ]
        );

        $juri3 = User::updateOrCreate(
            ['email' => 'juri3@pnj.ac.id'],
            [
                'name' => 'Andi Wijaya',
                'password' => Hash::make($defaultPassword),
                'phone' => '081200000004',
                'role' => 'juri',
                'is_active' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Election Period
        |--------------------------------------------------------------------------
        */

        $period = ElectionPeriod::updateOrCreate(
            ['election_year' => 2026],
            [
                'registration_start' => now()->subDays(10),
                'registration_end' => now()->addDays(20),
                'interview_start' => now()->addDays(3),
                'interview_end' => now()->addDays(10),
                'status' => 'registration',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Criteria
        |--------------------------------------------------------------------------
        */

        $criteriaData = [
            [
                'code' => 'C1',
                'name' => 'Komunikasi',
                'weight' => 0.2500,
                'type' => 'benefit',
                'min_score' => 0,
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'C2',
                'name' => 'Public Speaking',
                'weight' => 0.2500,
                'type' => 'benefit',
                'min_score' => 0,
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'C3',
                'name' => 'Wawasan Kampus',
                'weight' => 0.2000,
                'type' => 'benefit',
                'min_score' => 0,
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'C4',
                'name' => 'Kepribadian',
                'weight' => 0.2000,
                'type' => 'benefit',
                'min_score' => 0,
                'max_score' => 100,
                'is_active' => true,
            ],
            [
                'code' => 'C5',
                'name' => 'Kedisiplinan',
                'weight' => 0.1000,
                'type' => 'benefit',
                'min_score' => 0,
                'max_score' => 100,
                'is_active' => true,
            ],
        ];

        $criteria = [];

        foreach ($criteriaData as $item) {
            $criteria[$item['code']] = Criterion::updateOrCreate(
                [
                    'period_id' => $period->id,
                    'code' => $item['code'],
                ],
                [
                    'name' => $item['name'],
                    'weight' => $item['weight'],
                    'type' => $item['type'],
                    'min_score' => $item['min_score'],
                    'max_score' => $item['max_score'],
                    'is_active' => $item['is_active'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Jury Criteria
        |--------------------------------------------------------------------------
        */

        $juryCriteriaMap = [
            $juri1->id => ['C1', 'C2'],
            $juri2->id => ['C3', 'C4'],
            $juri3->id => ['C1', 'C2', 'C3', 'C4', 'C5'],
        ];

        foreach ($juryCriteriaMap as $juryId => $criterionCodes) {
            foreach ($criterionCodes as $code) {
                JuryCriterion::updateOrCreate(
                    [
                        'period_id' => $period->id,
                        'user_id' => $juryId,
                        'criterion_id' => $criteria[$code]->id,
                    ],
                    []
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Candidates
        |--------------------------------------------------------------------------
        */

        $candidatesData = [
            [
                'registration_number' => 'DK-2026-001',
                'full_name' => 'Aditya Saputra',
                'student_number' => '2103421045',
                'email' => 'aditya.saputra@student.pnj.ac.id',
                'phone' => '081300000001',
                'faculty' => 'Teknik Informatika dan Komputer',
                'study_program' => 'Teknik Informatika',
                'semester' => 5,
                'vision' => 'Menjadi duta kampus yang mampu membangun citra positif PNJ.',
                'mission' => 'Mengembangkan komunikasi mahasiswa dan memperkuat promosi kampus.',
                'status' => 'pending',
            ],
            [
                'registration_number' => 'DK-2026-002',
                'full_name' => 'Rina Mutia',
                'student_number' => '2107411012',
                'email' => 'rina.mutia@student.pnj.ac.id',
                'phone' => '081300000002',
                'faculty' => 'Akuntansi',
                'study_program' => 'Akuntansi Terapan',
                'semester' => 5,
                'vision' => 'Menjadi representasi mahasiswa PNJ yang profesional dan inspiratif.',
                'mission' => 'Meningkatkan partisipasi mahasiswa dalam kegiatan kampus.',
                'status' => 'valid',
            ],
            [
                'registration_number' => 'DK-2026-003',
                'full_name' => 'Bagus Kurniawan',
                'student_number' => '2204311008',
                'email' => 'bagus.kurniawan@student.pnj.ac.id',
                'phone' => '081300000003',
                'faculty' => 'Teknik Mesin',
                'study_program' => 'Teknik Mesin',
                'semester' => 3,
                'vision' => 'Membangun semangat kolaborasi dan prestasi mahasiswa.',
                'mission' => 'Mengajak mahasiswa aktif dalam kegiatan akademik dan nonakademik.',
                'status' => 'invalid',
                'rejection_reason' => 'Berkas CV belum sesuai ketentuan.',
            ],
            [
                'registration_number' => 'DK-2026-004',
                'full_name' => 'Nabila Putri',
                'student_number' => '2105521090',
                'email' => 'nabila.putri@student.pnj.ac.id',
                'phone' => '081300000004',
                'faculty' => 'Administrasi Niaga',
                'study_program' => 'Administrasi Bisnis',
                'semester' => 5,
                'vision' => 'Menjadi duta kampus yang komunikatif, ramah, dan berintegritas.',
                'mission' => 'Meningkatkan branding kampus melalui kegiatan mahasiswa.',
                'status' => 'interview_scheduled',
            ],
            [
                'registration_number' => 'DK-2026-005',
                'full_name' => 'Fajar Ramadhan',
                'student_number' => '2106331022',
                'email' => 'fajar.ramadhan@student.pnj.ac.id',
                'phone' => '081300000005',
                'faculty' => 'Teknik Elektro',
                'study_program' => 'Teknik Elektronika',
                'semester' => 5,
                'vision' => 'Menjadi duta kampus yang inovatif dan adaptif terhadap perkembangan teknologi.',
                'mission' => 'Mendorong mahasiswa untuk aktif dalam inovasi dan kegiatan kampus.',
                'status' => 'interviewed',
            ],
            [
                'registration_number' => 'DK-2026-006',
                'full_name' => 'Citra Maharani',
                'student_number' => '2108121033',
                'email' => 'citra.maharani@student.pnj.ac.id',
                'phone' => '081300000006',
                'faculty' => 'Teknik Grafika dan Penerbitan',
                'study_program' => 'Desain Grafis',
                'semester' => 5,
                'vision' => 'Membawa citra kreatif mahasiswa PNJ ke tingkat yang lebih luas.',
                'mission' => 'Mengembangkan media komunikasi visual untuk promosi kampus.',
                'status' => 'scored',
            ],
        ];

        $candidates = [];

        foreach ($candidatesData as $item) {
            $candidate = Candidate::updateOrCreate(
                [
                    'period_id' => $period->id,
                    'student_number' => $item['student_number'],
                ],
                [
                    'registration_number' => $item['registration_number'],
                    'full_name' => $item['full_name'],
                    'email' => $item['email'],
                    'phone' => $item['phone'],
                    'faculty' => $item['faculty'],
                    'study_program' => $item['study_program'],
                    'semester' => $item['semester'],
                    'vision' => $item['vision'],
                    'mission' => $item['mission'],
                    'photo_file' => null,
                    'cv_file' => null,
                    'status' => $item['status'],
                    'validated_by' => in_array($item['status'], ['valid', 'invalid', 'interview_scheduled', 'interviewed', 'scored'])
                        ? $admin->id
                        : null,
                    'validated_at' => in_array($item['status'], ['valid', 'invalid', 'interview_scheduled', 'interviewed', 'scored'])
                        ? now()->subDays(2)
                        : null,
                    'rejection_reason' => $item['rejection_reason'] ?? null,
                ]
            );

            $candidates[$item['student_number']] = $candidate;
        }

        /*
        |--------------------------------------------------------------------------
        | Interviews
        |--------------------------------------------------------------------------
        */

        $interviewsData = [
            [
                'candidate' => '2107411012',
                'scheduled_at' => now()->addDay()->setTime(8, 0),
                'location' => 'Ruang Sidang 1',
                'status' => 'scheduled',
            ],
            [
                'candidate' => '2105521090',
                'scheduled_at' => now()->addDay()->setTime(8, 15),
                'location' => 'Ruang Sidang 1',
                'status' => 'scheduled',
            ],
            [
                'candidate' => '2106331022',
                'scheduled_at' => now()->subDay()->setTime(9, 0),
                'location' => 'Ruang Sidang 2',
                'status' => 'completed',
            ],
            [
                'candidate' => '2108121033',
                'scheduled_at' => now()->subDay()->setTime(9, 15),
                'location' => 'Ruang Sidang 2',
                'status' => 'completed',
            ],
        ];

        foreach ($interviewsData as $item) {
            Interview::updateOrCreate(
                [
                    'candidate_id' => $candidates[$item['candidate']]->id,
                ],
                [
                    'period_id' => $period->id,
                    'scheduled_at' => $item['scheduled_at'],
                    'location' => $item['location'],
                    'status' => $item['status'],
                    'created_by' => $admin->id,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Scores
        |--------------------------------------------------------------------------
        |
        | Nilai hanya diberikan untuk sebagian calon agar dashboard tetap realistis.
        |
        */

        $scoreRows = [
            [
                'candidate' => '2106331022',
                'jury' => $juri1->id,
                'scores' => [
                    'C1' => 86,
                    'C2' => 88,
                ],
            ],
            [
                'candidate' => '2106331022',
                'jury' => $juri2->id,
                'scores' => [
                    'C3' => 82,
                    'C4' => 85,
                ],
            ],
            [
                'candidate' => '2108121033',
                'jury' => $juri1->id,
                'scores' => [
                    'C1' => 90,
                    'C2' => 87,
                ],
            ],
            [
                'candidate' => '2108121033',
                'jury' => $juri2->id,
                'scores' => [
                    'C3' => 89,
                    'C4' => 91,
                ],
            ],
            [
                'candidate' => '2108121033',
                'jury' => $juri3->id,
                'scores' => [
                    'C1' => 88,
                    'C2' => 89,
                    'C3' => 90,
                    'C4' => 92,
                    'C5' => 85,
                ],
            ],
        ];

        foreach ($scoreRows as $row) {
            $candidate = $candidates[$row['candidate']];

            foreach ($row['scores'] as $criterionCode => $scoreValue) {
                Score::updateOrCreate(
                    [
                        'candidate_id' => $candidate->id,
                        'user_id' => $row['jury'],
                        'criterion_id' => $criteria[$criterionCode]->id,
                    ],
                    [
                        'period_id' => $period->id,
                        'score' => $scoreValue,
                    ]
                );
            }
        }
    }
}
