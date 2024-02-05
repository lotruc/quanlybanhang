<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Exception;

class BaseService
{

    /**
     * hàm lưu file vào storage
     */
    public function uploadFile($files, $newFolder = null)
    {
        try {
            $imagePath = $files;
            $imageName = $imagePath->getClientOriginalName();
            $filename = explode('.', $imageName)[0];
            $extension = $imagePath->getClientOriginalExtension();
            $picName =  Str::slug(time() . "_" . $filename, "_") . "." . $extension;
            $folder = $newFolder ? 'uploads/' . $newFolder : 'uploads';
            $path = $files->storeAs($folder, $picName, 'public');
            return $path;
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }


    /**
     * Hàm xóa file trong storage
     */
    public function deleteFile($path)
    {
        try {
            if (Storage::exists('public/' . $path)) {
                Storage::delete('public/' . $path);
            }
            return true;
        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }

    /**
     * Hàm tạo 1 đoạn chữ số ngẫu nhiên
     */
    public function generateRandomCode($length = 10)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $code;
    }
}
