<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileStorage
{
    public static function storeAvatarFromSns($file)
    {
        $filePath = "avatars/" . time() . ".jpeg";
        Storage::put($filePath, $file);
        if (config('filesystems.default') === 's3') {
            return $filePath;
        }
        $url = Storage::url($filePath);
        return $url;
    }

    public static function storeAvatarFromUpload($avatar)
    {
        $filePath =  Storage::put("avatars", $avatar);
        if (config('filesystems.default') === 's3') {
            return $filePath;
        }
        $url = Storage::url($filePath);
        return $url;
    }

    public static function getFileByUrl($url)
    {
        $file = file_get_contents($url);
        return $file;
    }

    public static function getProfileImageUrl($path)
    {
        if (config('filesystems.default') === 's3' && isset($path)) {
            return Storage::temporaryUrl($path, now()->addHours(24));
        }
        return  $path;
    }
}
