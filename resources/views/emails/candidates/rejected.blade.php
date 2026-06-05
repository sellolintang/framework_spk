<x-mail::message>
# Pendaftaran Belum Dapat Diterima

Halo **{{ $candidate->full_name }}**,

Terima kasih telah mendaftar sebagai calon Duta Kampus. Setelah dilakukan verifikasi oleh admin, pendaftaran Anda **belum dapat diterima**.

<x-mail::panel>
**Nomor Pendaftaran:** {{ $candidate->registration_number }}
**Nama:** {{ $candidate->full_name }}
**NIM:** {{ $candidate->student_number }}
**Program Studi:** {{ $candidate->study_program ?? '-' }}
**Status:** Ditolak
</x-mail::panel>

**Alasan penolakan:**

{{ $candidate->rejection_reason ?? '-' }}

Silakan memperbaiki data sesuai ketentuan panitia apabila pendaftaran ulang masih dibuka.

Terima kasih,
**Panitia Pemilihan Duta Kampus**
</x-mail::message>
