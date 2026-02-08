<?php

namespace App\Services;

class WhatsAppService
{
    /**
     * Generate link wa.me dengan pesan otomatis
     */
    public function generateAccountInfoLink($nomorHP, $data)
    {
        try {
            // Format nomor HP
            $nomorHP = $this->formatPhoneNumber($nomorHP);

            // Buat pesan
            $message = $this->buildAccountMessage($data);

            // Encode pesan untuk URL dengan rawurlencode (lebih aman untuk emoji)
            $encodedMessage = rawurlencode($message);

            // Generate link wa.me (gunakan wa.me bukan api.whatsapp.com)
            $waLink = "https://wa.me/{$nomorHP}?text={$encodedMessage}";

            return [
                'success' => true,
                'link' => $waLink,
                'phone' => $nomorHP
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format nomor telepon ke format internasional (tanpa +)
     */
    private function formatPhoneNumber($phone)
    {
        // Hapus karakter non-numeric
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Jika belum ada kode negara, tambahkan 62
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Buat template pesan akun - VERSI FINAL (100% COMPATIBLE)
     */
   private function buildAccountMessage($data)
    {
        return "*AKUN ANDA TELAH DIBUAT*\n\n" .
            "Yth. *{$data['name']}*\n" .
            "Akun Anda telah siap digunakan.\n\n" .
            "*DATA LOGIN*\n" .
            "Email:{$data['email']}\n" .
            "Password:{$data['password']}\n" .
            "Link Login:https://simpel.pw/login\n\n" .
            "*GRUP WHATSAPP*\n" .
            "{$data['link_gb_wa']}\n\n" .
            "*CATATAN PENTING*\n" .
            "- Simpan data login Anda\n" .
            "- Join grup untuk informasi resmi\n" .
            "- Update data diri jika ada perubahan\n\n\n" .
            "_Hubungi admin jika ada kendala_\n\n" .
            "Salam,\n*Tim LAN PUSJAR SKMP*";
    }
}