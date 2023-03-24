<?php

namespace App\Http\Traits;

use Illuminate\Http\UploadedFile;

trait FileUploadTrait
{
    public function uploadFile(UploadedFile $file,  $disk= 'public', $directory = '')
    {
        $image_name = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs($directory, $image_name, $disk);
        return $image_name;
    }
}
