<?php
namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Str;


trait StorageImage {
    public function storageUpload(Request $request, $fieldName, $folderName)
    {
        if($request->hasFile($fieldName)){
            $file = $request->$fieldName;
            $name = $file->getClientOriginalName();
            $image = Str::random(10).'-'.$name;
            $path = $request->file($fieldName)->storeAs('public/'.$folderName.Auth()->id(), $image);
            $data = [
                'file_name' => $name,
                'file_path' => Storage::url($path)
            ];
            return $data;
        }
        return null;
    }

    public function storageUploadMultiple($file, $folderName)
    {
        $name = $file->getClientOriginalName();
        $nameHash = Str::random(10).'-'.$file->getClientOriginalExtension();
        $path = $file->storeAs('public/'.$folderName.Auth()->id(), $nameHash);
        $data = [
            'file_name' => $name,
            'file_path' => Storage::url($path)
        ];
        return $data;
    
    }
}

