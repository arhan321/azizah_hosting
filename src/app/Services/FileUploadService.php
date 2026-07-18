<?php

namespace App\Services;

use App\Support\StoragePath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class FileUploadService
{
    /**
     * Upload file ke storage public.
     *
     * @param UploadedFile $file
     * @param string       $folder  contoh: 'designs', 'custom-referensi', 'hasil'
     * @return string      Path relatif file
     */
    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        return $this->uploadTo($file, 'uploads/'.trim($folder, '/'));
    }

    /**
     * Upload a file to an exact directory on the public disk.
     */
    public function uploadTo(UploadedFile $file, string $directory): string
    {
        $filename = Str::uuid().'.'.strtolower($file->getClientOriginalExtension());
        $path = $file->storeAs(trim($directory, '/'), $filename, 'public');

        if (!is_string($path) || $path === '') {
            throw new RuntimeException('File gagal disimpan ke public storage.');
        }

        if (!Storage::disk('public')->exists($path)) {
            throw new RuntimeException('File upload tidak ditemukan setelah disimpan.');
        }

        return StoragePath::normalize($path) ?? $path;
    }

    /**
     * Hapus file dari storage.
     */
    public function delete(?string $filePath): bool
    {
        $path = StoragePath::normalize($filePath);

        if ($path === null || StoragePath::isUrl($path)) {
            return false;
        }

        return Storage::disk('public')->delete($path);
    }
}