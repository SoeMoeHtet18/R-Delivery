<?php

namespace App\Http\Traits;

use Illuminate\Http\UploadedFile;

trait FileUploadTrait
{
    public function uploadFile(UploadedFile $file, $path)
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path($path), $fileName);
        return $fileName;
    }
}
