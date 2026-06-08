<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class FinalDataSeeder extends Seeder
{
    private const EXCEL_FILE = 'duta_pnj_batch_5.xlsx';

    private const DEFAULT_PHOTO = 'candidates/photos/default-candidate.jpg';
    private const DEFAULT_CV = 'candidates/cv/default-cv.pdf';

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
            'name' => 'Siti Nurhaliza',
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
            'name' => 'Ahmad Fauzan',
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
            'name' => 'Dewi Lestari',
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

        $periodId = DB::table('election_periods')->insertGetId([
            'election_year' => 2026,
            'registration_start' => $now->copy()->subDays(30),
            'registration_end' => $now->copy()->addDays(30),
            'interview_start' => $now->copy()->addDays(31),
            'interview_end' => $now->copy()->addDays(32),
            'status' => 'scoring',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $excelCandidates = $this->readCandidatesFromExcel();
        $candidateIds = [];

        foreach ($excelCandidates as $index => $candidate) {
            $number = $index + 1;

            $candidateIds[$candidate['code']] = DB::table('candidates')->insertGetId([
                'period_id' => $periodId,
                'registration_number' => sprintf('DPNJ-2026-%03d', $number),
                'full_name' => $candidate['name'],
                'student_number' => $this->studentNumber($candidate['generation'], $number),
                'email' => strtolower($candidate['code']) . '@student.pnj.ac.id',
                'phone' => $this->phoneNumber($number),
                'faculty' => $this->facultyFromMajor($candidate['major']),
                'study_program' => $candidate['major'],
                'semester' => $this->semesterFromGeneration($candidate['generation']),
                'vision' => 'Menjadi representasi mahasiswa PNJ yang berintegritas, percaya diri, dan komunikatif.',
                'mission' => 'Mendukung kegiatan duta kampus melalui prestasi, komunikasi, pelayanan, dan citra positif institusi.',
                'photo_file' => self::DEFAULT_PHOTO,
                'cv_file' => self::DEFAULT_CV,
                'status' => 'scored',
                'validated_by' => $adminId,
                'validated_at' => $now,
                'rejection_reason' => null,
                'created_at' => $now->copy()->subDays(20)->addDays($number % 10),
                'updated_at' => $now,
            ]);
        }

        $pendingCandidates = [
            [
                'full_name' => 'Raka Aditya Pratama',
                'student_number' => '2407415050',
                'email' => 'raka.aditya@student.pnj.ac.id',
                'phone' => '083900000050',
                'faculty' => 'Teknik Informatika dan Komputer',
                'study_program' => 'Teknik Informatika dan Komputer',
                'semester' => 4,
            ],
            [
                'full_name' => 'Salsabila Putri Azzahra',
                'student_number' => '2405415051',
                'email' => 'salsabila.putri@student.pnj.ac.id',
                'phone' => '083900000051',
                'faculty' => 'Administrasi Niaga',
                'study_program' => 'Administrasi Niaga',
                'semester' => 4,
            ],
            [
                'full_name' => 'Muhammad Farhan Alfarizi',
                'student_number' => '2303415052',
                'email' => 'farhan.alfarizi@student.pnj.ac.id',
                'phone' => '083900000052',
                'faculty' => 'Teknik Mesin',
                'study_program' => 'Teknik Mesin',
                'semester' => 6,
            ],
        ];

        foreach ($pendingCandidates as $index => $candidate) {
            $number = 50 + $index;

            DB::table('candidates')->insert([
                'period_id' => $periodId,
                'registration_number' => sprintf('DPNJ-2026-%03d', $number),
                'full_name' => $candidate['full_name'],
                'student_number' => $candidate['student_number'],
                'email' => $candidate['email'],
                'phone' => $candidate['phone'],
                'faculty' => $candidate['faculty'],
                'study_program' => $candidate['study_program'],
                'semester' => $candidate['semester'],
                'vision' => 'Menjadi mahasiswa yang aktif dan mampu membawa nama baik PNJ.',
                'mission' => 'Mengikuti proses seleksi dengan bertanggung jawab dan profesional.',
                'photo_file' => self::DEFAULT_PHOTO,
                'cv_file' => self::DEFAULT_CV,
                'status' => 'pending',
                'validated_by' => null,
                'validated_at' => null,
                'rejection_reason' => null,
                'created_at' => $now->copy()->subDays(2)->addDays($index),
                'updated_at' => $now,
            ]);
        }

        $criteria = [
            ['code' => 'C1',  'name' => 'Ekspresi, Etika, Kepercayaan Diri',      'weight' => 0.0800],
            ['code' => 'C2',  'name' => 'Berpikir Kritis, Kreatif, Inisiatif,',   'weight' => 0.0800],
            ['code' => 'C3',  'name' => 'Komitmen Terhadap Duta dan Institusi',   'weight' => 0.0700],
            ['code' => 'C4',  'name' => 'Kemampuan Komunikasi, Argumentasi',      'weight' => 0.0800],
            ['code' => 'C5',  'name' => 'Personaliti Kepemimpinan & Keteraturan', 'weight' => 0.0700],
            ['code' => 'C6',  'name' => 'Manajemen Emosi',                        'weight' => 0.0500],
            ['code' => 'C7',  'name' => 'Sikap Kooperatif',                       'weight' => 0.0500],
            ['code' => 'C8',  'name' => 'Penilaian Diri dan Tujuan Pribadi',      'weight' => 0.0500],
            ['code' => 'C9',  'name' => 'Opening & Public Speaking',              'weight' => 0.1300],
            ['code' => 'C10', 'name' => 'Wawasan & Keilmuan',                     'weight' => 0.1000],
            ['code' => 'C11', 'name' => 'Kemampuan Berbahasa inggris',            'weight' => 0.0800],
            ['code' => 'C12', 'name' => 'Personaliti Kepemimpinan',               'weight' => 0.0500],
            ['code' => 'C13', 'name' => 'Body Language',                          'weight' => 0.0400],
            ['code' => 'C14', 'name' => 'Kreativitas dan Invoasi',                'weight' => 0.0400],
            ['code' => 'C15', 'name' => 'Grooming & Look',                        'weight' => 0.0300],
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

        $juryIdsByCriterion = [
            'C1' => [$juri1Id, $juri2Id, $juri3Id],
            'C2' => [$juri1Id, $juri2Id, $juri3Id],
            'C3' => [$juri1Id, $juri2Id, $juri3Id],
            'C4' => [$juri1Id, $juri2Id, $juri3Id],
            'C5' => [$juri1Id],
            'C6' => [$juri1Id],
            'C7' => [$juri1Id],
            'C8' => [$juri1Id],
            'C9' => [$juri2Id, $juri3Id],
            'C10' => [$juri2Id],
            'C11' => [$juri2Id],
            'C12' => [$juri2Id],
            'C13' => [$juri3Id],
            'C14' => [$juri3Id],
            'C15' => [$juri3Id],
        ];

        foreach ($excelCandidates as $candidate) {
            foreach ($candidate['scores'] as $criterionCode => $score) {
                foreach ($juryIdsByCriterion[$criterionCode] as $juryId) {
                    DB::table('scores')->insert([
                        'period_id' => $periodId,
                        'candidate_id' => $candidateIds[$candidate['code']],
                        'user_id' => $juryId,
                        'criterion_id' => $criterionIds[$criterionCode],
                        'score' => $score,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    private function readCandidatesFromExcel(): array
    {
        $excelPath = $this->excelPath();

        $zip = new ZipArchive();

        if ($zip->open($excelPath) !== true) {
            throw new RuntimeException("Gagal membuka file Excel: {$excelPath}");
        }

        $sharedStrings = $this->readSharedStrings($zip);
        $sheetPath = $this->worksheetPath($zip, 'Perhitungan ARAS');
        $rows = $this->readWorksheetRows($zip, $sheetPath, $sharedStrings);

        if (empty($rows)) {
            $zip->close();

            throw new RuntimeException("Sheet {$sheetPath} kosong atau tidak terbaca.");
        }

        $candidates = [];
        $maxRow = max(array_keys($rows));

        foreach ($rows as $headerRowNumber => $headerRow) {
            $headerMap = $this->detectCandidateHeader($headerRow);

            if (!$headerMap) {
                continue;
            }

            $scoreColumns = [];
            $startScoreColumnNumber = $this->columnNumber($headerMap['generation']) + 1;

            for ($i = 0; $i < 15; $i++) {
                $scoreColumns['C' . ($i + 1)] = $this->columnName($startScoreColumnNumber + $i);
            }

            $tableCandidates = [];

            for ($rowNumber = $headerRowNumber + 1; $rowNumber <= $maxRow; $rowNumber++) {
                $row = $rows[$rowNumber] ?? [];

                $candidateNumber = $this->candidateNumber($row[$headerMap['no']] ?? null);

                if ($candidateNumber === null) {
                    if (count($tableCandidates) > 0) {
                        break;
                    }

                    continue;
                }

                if ($candidateNumber === 0) {
                    continue;
                }

                if ($candidateNumber < 1 || $candidateNumber > 49) {
                    continue;
                }

                $firstScoreColumn = $scoreColumns['C1'];
                $firstScore = $this->numericValue($row[$firstScoreColumn] ?? null);

                if ($firstScore === null || $firstScore <= 1) {
                    $tableCandidates = [];
                    break;
                }

                $scores = [];

                foreach ($scoreColumns as $criterionCode => $column) {
                    $score = $this->numericValue($row[$column] ?? null);

                    if ($score === null) {
                        throw new RuntimeException(
                            "Nilai {$criterionCode} kosong pada kandidat nomor {$candidateNumber}, baris Excel {$rowNumber}."
                        );
                    }

                    $scores[$criterionCode] = round($score, 3);
                }

                $name = trim((string) ($row[$headerMap['name']] ?? ''));

                if ($name === '') {
                    throw new RuntimeException("Nama kandidat kosong pada baris Excel {$rowNumber}.");
                }

                $major = trim((string) ($row[$headerMap['major']] ?? ''));
                $gender = trim((string) ($row[$headerMap['gender']] ?? ''));
                $generation = (int) $this->numericValue($row[$headerMap['generation']] ?? 2024);

                $tableCandidates[] = [
                    'code' => sprintf('P%02d', $candidateNumber),
                    'name' => $name,
                    'gender' => $gender,
                    'major' => $major,
                    'generation' => $generation ?: 2024,
                    'scores' => $scores,
                ];

                if (count($tableCandidates) === 49) {
                    break;
                }
            }

            if (count($tableCandidates) === 49) {
                $candidates = $tableCandidates;
                break;
            }
        }

        $zip->close();

        if (count($candidates) !== 49) {
            throw new RuntimeException(
                'Jumlah kandidat dari Excel harus 49, terbaca: ' . count($candidates)
                . '. Pastikan sheet "Perhitungan ARAS" memiliki tabel nilai asli dengan header No, Nama Peserta, Jenis Kelamin, Jurusan, Angkatan, lalu 15 kolom nilai.'
            );
        }

        return $candidates;
    }

    private function excelPath(): string
    {
        $paths = [
            database_path('seeders/data/' . self::EXCEL_FILE),
            database_path('seeders/data/Data Dummy Pemilihan Duta PNJ Batch 5.xlsx'),
            database_path('seeders/data/Data Dummy Pemilihan Duta PNJ Batch 5 (2).xlsx'),
            storage_path('app/seeders/' . self::EXCEL_FILE),
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        throw new RuntimeException(
            'File Excel tidak ditemukan. Simpan file Excel ke: database/seeders/data/' . self::EXCEL_FILE
        );
    }

    private function detectCandidateHeader(array $row): ?array
    {
        $map = [
            'no' => null,
            'name' => null,
            'gender' => null,
            'major' => null,
            'generation' => null,
        ];

        foreach ($row as $column => $value) {
            $text = $this->normalizeText($value);

            if ($text === 'NO' || $text === 'NO.') {
                $map['no'] = $column;
                continue;
            }

            if (str_contains($text, 'NAMA') && str_contains($text, 'PESERTA')) {
                $map['name'] = $column;
                continue;
            }

            if (str_contains($text, 'JENIS')) {
                $map['gender'] = $column;
                continue;
            }

            if (str_contains($text, 'JURUSAN')) {
                $map['major'] = $column;
                continue;
            }

            if (str_contains($text, 'ANGKATAN')) {
                $map['generation'] = $column;
                continue;
            }
        }

        foreach ($map as $value) {
            if ($value === null) {
                return null;
            }
        }

        return $map;
    }

    private function readSharedStrings(ZipArchive $zip): array
    {
        $content = $zip->getFromName('xl/sharedStrings.xml');

        if ($content === false) {
            return [];
        }

        $xml = simplexml_load_string($content);

        if (!$xml) {
            return [];
        }

        $namespace = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
        $xml->registerXPathNamespace('x', $namespace);

        $strings = [];

        foreach ($xml->xpath('//x:si') ?: [] as $item) {
            $item->registerXPathNamespace('x', $namespace);

            $texts = $item->xpath('.//x:t');
            $value = '';

            foreach ($texts ?: [] as $text) {
                $value .= (string) $text;
            }

            $strings[] = $value;
        }

        return $strings;
    }

    private function worksheetPath(ZipArchive $zip, string $sheetName): string
    {
        $workbookContent = $zip->getFromName('xl/workbook.xml');
        $relsContent = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($workbookContent === false || $relsContent === false) {
            throw new RuntimeException('Struktur workbook Excel tidak valid.');
        }

        $relationshipNamespace = 'http://schemas.openxmlformats.org/officeDocument/2006/relationships';

        $workbook = simplexml_load_string($workbookContent);

        if (!$workbook) {
            throw new RuntimeException('Workbook Excel gagal dibaca.');
        }

        $relationshipId = null;
        $sheetIndex = null;
        $availableSheets = [];

        $sheets = $workbook->xpath('//*[local-name()="sheet"]') ?: [];

        foreach ($sheets as $index => $sheet) {
            $name = (string) $sheet['name'];
            $availableSheets[] = $name;

            $normalizedName = strtoupper(trim($name));
            $normalizedTarget = strtoupper(trim($sheetName));

            if (
                $normalizedName === $normalizedTarget ||
                (str_contains($normalizedName, 'PERHITUNGAN') && str_contains($normalizedName, 'ARAS'))
            ) {
                $sheetIndex = $index + 1;

                $attributes = $sheet->attributes($relationshipNamespace);
                $relationshipId = (string) ($attributes['id'] ?? '');

                break;
            }
        }

        if (!$relationshipId && $sheetIndex === null) {
            throw new RuntimeException(
                'Sheet "Perhitungan ARAS" tidak ditemukan. Sheet tersedia: ' . implode(', ', $availableSheets)
            );
        }

        $rels = simplexml_load_string($relsContent);

        if (!$rels) {
            throw new RuntimeException('Relasi workbook Excel gagal dibaca.');
        }

        if ($relationshipId) {
            $relationships = $rels->xpath('//*[local-name()="Relationship"]') ?: [];

            foreach ($relationships as $relationship) {
                if ((string) $relationship['Id'] === $relationshipId) {
                    $path = $this->normalizeWorksheetTarget((string) $relationship['Target']);

                    if ($zip->locateName($path) !== false) {
                        return $path;
                    }
                }
            }
        }

        // Fallback aman berdasarkan urutan sheet di workbook.
        // Biasanya sheet ke-4 adalah "Perhitungan ARAS".
        if ($sheetIndex !== null) {
            $fallbackPath = 'xl/worksheets/sheet' . $sheetIndex . '.xml';

            if ($zip->locateName($fallbackPath) !== false) {
                return $fallbackPath;
            }
        }

        // Fallback terakhir untuk format Excel kamu sebelumnya.
        $fallbackPath = 'xl/worksheets/sheet4.xml';

        if ($zip->locateName($fallbackPath) !== false) {
            return $fallbackPath;
        }

        throw new RuntimeException(
            'Worksheet target untuk sheet "Perhitungan ARAS" tidak ditemukan. Sheet tersedia: '
            . implode(', ', $availableSheets)
        );
    }

    private function normalizeWorksheetTarget(string $target): string
    {
        $target = str_replace('\\', '/', $target);
        $target = ltrim($target, '/');
        $target = preg_replace('#^\./#', '', $target) ?? $target;

        while (str_starts_with($target, '../')) {
            $target = substr($target, 3);
        }

        if (str_starts_with($target, 'xl/')) {
            return $target;
        }

        return 'xl/' . ltrim($target, '/');
    }

    private function readWorksheetRows(ZipArchive $zip, string $sheetPath, array $sharedStrings): array
    {
        $content = $zip->getFromName($sheetPath);

        if ($content === false) {
            throw new RuntimeException("Worksheet tidak ditemukan di Excel: {$sheetPath}");
        }

        $xml = simplexml_load_string($content);

        if (!$xml) {
            throw new RuntimeException("Worksheet gagal dibaca: {$sheetPath}");
        }

        $namespace = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';
        $xml->registerXPathNamespace('x', $namespace);

        $rows = [];

        foreach ($xml->xpath('//x:sheetData/x:row') ?: [] as $row) {
            $rowNumber = (int) $row['r'];
            $rows[$rowNumber] = [];

            $row->registerXPathNamespace('x', $namespace);

            foreach ($row->xpath('x:c') ?: [] as $cell) {
                $cellReference = (string) $cell['r'];
                $column = $this->columnFromCellReference($cellReference);

                if ($column === '') {
                    continue;
                }

                $rows[$rowNumber][$column] = $this->cellValue($cell, $sharedStrings, $namespace);
            }
        }

        return $rows;
    }

    private function cellValue(SimpleXMLElement $cell, array $sharedStrings, string $namespace): string|float|int|null
    {
        $type = (string) $cell['t'];

        $cell->registerXPathNamespace('x', $namespace);

        if ($type === 'inlineStr') {
            $texts = $cell->xpath('.//x:t');

            if (!$texts) {
                return null;
            }

            $value = '';

            foreach ($texts as $text) {
                $value .= (string) $text;
            }

            return trim($value);
        }

        $values = $cell->xpath('x:v');

        if (!$values || !isset($values[0])) {
            return null;
        }

        $value = (string) $values[0];

        if ($type === 's') {
            return $sharedStrings[(int) $value] ?? null;
        }

        if ($value === '') {
            return null;
        }

        return is_numeric($value) ? (float) $value : trim($value);
    }

    private function columnFromCellReference(string $cellReference): string
    {
        preg_match('/^([A-Z]+)/', $cellReference, $matches);

        return $matches[1] ?? '';
    }

    private function normalizeText(mixed $value): string
    {
        return strtoupper(trim(preg_replace('/\s+/', ' ', (string) $value) ?? ''));
    }

    private function candidateNumber(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            $number = (int) $value;

            if (abs((float) $value - $number) < 0.00001) {
                return $number;
            }

            return null;
        }

        $value = trim((string) $value);

        if (preg_match('/^\d+$/', $value)) {
            return (int) $value;
        }

        return null;
    }

    private function numericValue(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $value = str_replace(',', '.', trim((string) $value));

        return is_numeric($value) ? (float) $value : null;
    }

    private function columnNumber(string $column): int
    {
        $column = strtoupper($column);
        $number = 0;

        for ($i = 0; $i < strlen($column); $i++) {
            $number = $number * 26 + (ord($column[$i]) - 64);
        }

        return $number;
    }

    private function columnName(int $number): string
    {
        $name = '';

        while ($number > 0) {
            $number--;
            $name = chr(65 + ($number % 26)) . $name;
            $number = intdiv($number, 26);
        }

        return $name;
    }

    private function facultyFromMajor(string $major): string
    {
        return match ($major) {
            'Administrasi Niaga' => 'Administrasi Niaga',
            'Akutansi', 'Akuntansi' => 'Akuntansi',
            'Teknik Sipil' => 'Teknik Sipil',
            'Teknik Mesin' => 'Teknik Mesin',
            'Teknik Elektro' => 'Teknik Elektro',
            'Teknik Grafika dan Penerbitan' => 'Teknik Grafika dan Penerbitan',
            'Teknik Informatika dan Komputer' => 'Teknik Informatika dan Komputer',
            default => $major ?: 'Politeknik Negeri Jakarta',
        };
    }

    private function semesterFromGeneration(int $generation): int
    {
        return match ($generation) {
            2023 => 6,
            2024 => 4,
            default => 4,
        };
    }

    private function studentNumber(int $generation, int $number): string
    {
        return sprintf('%02d%06d', $generation % 100, 500000 + $number);
    }

    private function phoneNumber(int $number): string
    {
        return sprintf('08390000%04d', $number);
    }
}