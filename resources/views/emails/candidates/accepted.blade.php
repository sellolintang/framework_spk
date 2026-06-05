<x-mail::message>
# Pendaftaran Diterima

Halo **{{ $candidate->full_name }}**,

Selamat! Pendaftaran Anda sebagai calon Duta Kampus telah **diterima** oleh admin.

<x-mail::panel>
**Nomor Pendaftaran:** {{ $candidate->registration_number }}
**Nama:** {{ $candidate->full_name }}
**NIM:** {{ $candidate->student_number }}
**Program Studi:** {{ $candidate->study_program ?? '-' }}
**Status:** Diterima
</x-mail::panel>

Silakan menunggu informasi berikutnya dari panitia terkait tahapan seleksi selanjutnya.

Terima kasih,
**Panitia Pemilihan Duta Kampus**
</x-mail::message>
