<?php

use App\Models\Aktifikat;

if (!function_exists('aktifitas')) {
    function aktifitas(string $action, $subject = null)
    {
        if (!auth()->check()) {
            return null;
        }

        $name = null;

        if ($subject) {
            $name = $subject->name
                ?? $subject->title
                ?? ($subject->id ?? null);
        }

        return Aktifikat::create([
            'deskripsi' => $name
                ? "{$action} dengan {$name}"
                : $action,
            'user_id' => auth()->id(),
        ]);
    }
}
