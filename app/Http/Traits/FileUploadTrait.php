<?php

namespace App\Http\Traits;

use Illuminate\Http\UploadedFile;

trait FileUploadTrait
{
    public function uploadFile(UploadedFile $file,  $disk= 'public', $directory = '')
    {
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->store($directory, $disk);
        return $fileName;
    }
}
