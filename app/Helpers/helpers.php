<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('getFileSize')) {

	function getFileSize($fileUrl)
	{
        try{
            if (isset($fileUrl)) {
                $fileSize = Storage::disk('public')->size($fileUrl); // File size in bytes
                if($fileSize > 1073741824)
                {
                    // Convert the size to GB
                    $convertedFileSize = $fileSize / 1073741824; // 1 GB = 1024 * 1024 * 1024 bytes
                    return round($convertedFileSize, 2).' GB';
                }elseif($fileSize > 1048576){
                    // Convert the size to MB
                    $convertedFileSize = $fileSize / 1048576; // 1 MB = 1024 * 1024 bytes
                    return round($convertedFileSize, 2).' MB';
                }else{
                    // Convert the size to KB
                    $convertedFileSize = $fileSize / 1024; // 1 KB = 1024 bytes
                    return round($convertedFileSize, 2).' KB';
                }
            }
            return '';
        }catch(\Exception $e){
            return '';
        }
    }
}