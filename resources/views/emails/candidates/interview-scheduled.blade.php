<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Wawancara Seleksi Duta PNJ</title>
</head>
<body style="margin:0; padding:0; background:#f1f5f9; font-family:Arial, sans-serif; color:#0f172a;">
    <div style="max-width:640px; margin:0 auto; padding:32px 16px;">
        <div style="background:#ffffff; border-radius:16px; overflow:hidden; border:1px solid #e2e8f0;">
            <div style="background:#00288E; padding:28px 32px;">
                <h1 style="margin:0; color:#ffffff; font-size:24px; line-height:1.3;">
                    Jadwal Wawancara Duta PNJ
                </h1>

                <p style="margin:8px 0 0; color:#bfdbfe; font-size:14px;">
                    Tahap wawancara seleksi Duta PNJ
                </p>
            </div>

            <div style="padding:32px;">
                <p style="margin:0 0 16px; font-size:15px; line-height:1.7;">
                    Halo <strong>{{ $interview->full_name ?? 'Peserta' }}</strong>,
                </p>

                <p style="margin:0 0 20px; font-size:15px; line-height:1.7;">
                    Selamat, kamu telah dijadwalkan untuk mengikuti tahap wawancara seleksi Duta PNJ. Berikut detail jadwal wawancara kamu:
                </p>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:20px; margin:24px 0;">
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <td style="padding:8px 0; color:#64748b; font-size:14px; width:38%;">
                                Nomor Pendaftaran
                            </td>
                            <td style="padding:8px 0; font-weight:700; font-size:14px;">
                                {{ $interview->registration_number ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0; color:#64748b; font-size:14px;">
                                Nama
                            </td>
                            <td style="padding:8px 0; font-weight:700; font-size:14px;">
                                {{ $interview->full_name ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0; color:#64748b; font-size:14px;">
                                NIM
                            </td>
                            <td style="padding:8px 0; font-weight:700; font-size:14px;">
                                {{ $interview->student_number ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0; color:#64748b; font-size:14px;">
                                Jadwal
                            </td>
                            <td style="padding:8px 0; font-weight:700; font-size:14px;">
                                {{ $formattedSchedule }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0; color:#64748b; font-size:14px;">
                                Lokasi
                            </td>
                            <td style="padding:8px 0; font-weight:700; font-size:14px;">
                                {{ $interview->location ?: '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td style="padding:8px 0; color:#64748b; font-size:14px;">
                                Periode
                            </td>
                            <td style="padding:8px 0; font-weight:700; font-size:14px;">
                                {{ $interview->election_year ?? '-' }}
                            </td>
                        </tr>
                    </table>
                </div>

                <p style="margin:0 0 16px; font-size:15px; line-height:1.7;">
                    Mohon hadir tepat waktu dan mempersiapkan diri dengan baik. Jika terdapat kendala, segera hubungi panitia seleksi.
                </p>

                <p style="margin:24px 0 0; font-size:15px; line-height:1.7;">
                    Terima kasih,<br>
                    <strong>Panitia Pemilihan Duta PNJ</strong>
                </p>
            </div>
        </div>

        <p style="margin:20px 0 0; text-align:center; color:#94a3b8; font-size:12px;">
            Email ini dikirim otomatis oleh Sistem Seleksi Duta PNJ.
        </p>
    </div>
</body>
</html>