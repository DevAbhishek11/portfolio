<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    public function upload(UploadedFile $file, string $directory = 'projects'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Store in public disk
        $file->storeAs("{$directory}", $filename, 'public');

        return "storage/{$directory}/{$filename}";
    }

    public function delete(?string $path): bool
    {
        if (empty($path)) return false;

        $storagePath = str_replace(['storage/', '/storage/'], '', $path);

        if (Storage::disk('public')->exists($storagePath)) {
            return Storage::disk('public')->delete($storagePath);
        }

        return false;
    }
}