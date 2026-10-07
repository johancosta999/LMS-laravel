<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageUploader extends Controller
{
    public static function imageUploader($file, $path ='uploads/images', $fileName=null, $disk='public') {
         if(!$file || !$file->isValid()) {
            return null;
         } 

        $fileName = $fileName ?? time().'.'.$file->getClientOriginalExtension();

        $imagePath = $file->storeAs($path, $fileName, $disk);

        return $imagePath;
    }
}
