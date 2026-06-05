<x-mail::message>
# Pendaftaran Berhasil

Halo **{{ $candidate->full_name }}**,

Terima kasih telah mendaftar sebagai calon Duta Kampus.

Berikut detail pendaftaran Anda:

<x-mail::panel>
**Nomor Pendaftaran:** {{ $candidate->registration_number }}
**Nama:** {{ $candidate->full_name }}
**NIM:** {{ $candidate->student_number }}
**Email:** {{ $candidate->email }}
**Status:** Menunggu validasi admin
</x-mail::panel>

Simpan nomor pendaftaran ini untuk keperluan informasi seleksi berikutnya.

Terima kasih,
**Panitia Pemilihan Duta Kampus**
</x-mail::message>
