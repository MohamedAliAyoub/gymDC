<?php

namespace App\Http\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('uploadImage')) {
    /**
     * Upload an image to the specified disk and directory.
     *
     * @param UploadedFile $file
     * @param string $disk
     * @param string|null $directory
     * @return string
     */
    function uploadImage(UploadedFile $file, $disk = 'public', $directory = null)
    {
        $filename = generateUniqueFileName($file);

        // Check if the disk configuration exists
        if (!config("filesystems.disks.{$disk}")) {
            // Create the disk configuration dynamically
            config(["filesystems.disks.{$disk}" => [
                'driver' => 'local',
                'root' => storage_path("app/{$disk}"),
            ]]);
        }

        // Create the directory if it doesn't exist
        if ($directory !== null && !Storage::disk($disk)->exists($directory)) {
            Storage::disk($disk)->makeDirectory($directory);
        }

        // Store the file in the directory
        return  $file->storeAs($directory, $filename, $disk);


    }

    /**
     * Generate a unique filename for the uploaded image.
     *
     * @param UploadedFile $file
     * @return string
     */
    function generateUniqueFileName(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '.' . $extension;
    }

    function image_url($image)
    {
        return $image ? asset($image) : null;
    }
}
