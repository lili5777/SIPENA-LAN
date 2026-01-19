<?php

use App\Models\Aktifitas;

if (!function_exists('aktifitas')) {
    function aktifitas(string $action, $subject = null)
    {
        if (!auth()->check()) {
            return null;
        }

        $name = null;

        if ($subject) {
            $name = $subject->nama_lengkap
                ?? $subject->title
                ?? $subject->nama_angkatan
                ?? $subject->nama_mentor
                ?? $subject->name
                ?? ($subject->id ?? null);
        }

        return Aktifitas::create([
            'deskripsi' => $name
                ? "{$action} ({$name})"
                : $action,
            'user_id' => auth()->id(),
        ]);
    }
}
