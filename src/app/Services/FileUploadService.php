<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload file ke storage public.
     *
     * @param UploadedFile $file
     * @param string       $folder  contoh: 'designs', 'custom-referensi', 'hasil'
     * @return string      URL publik file
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path     = $file->storeAs("uploads/{$folder}", $filename, 'public');

        // Simpan path relatif (bukan full URL) agar portable di semua environment
        return $path;
    }

    /**
     * Hapus file dari storage.
     */
    public function delete(string $fileUrl): bool
    {
        // Konversi URL publik ke path storage
           // Handle both relative /storage/... and absolute http://...../storage/...
           $path = preg_replace('#^(https?://[^/]+)?/storage/#', '', $fileUrl);
           return Storage::disk('public')->delete($path);
    }
}
