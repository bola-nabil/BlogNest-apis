<?php

namespace App\Traits;

trait UploadFiles {
    public function uploadImage($file, $folder)
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path($folder);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $fileName);

        return url($folder . '/' . $fileName);
    }

    public function deleteImage($fileUrl)
    {
        $relativePath = parse_url($fileUrl, PHP_URL_PATH);
        $fullPath = public_path($relativePath);

        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
