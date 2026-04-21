<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileController
{
    public function download(File $file)
    {
        return Response::streamDownload(function () use ($file) {
            echo Storage::disk($file->disk)->get($file->path);
        }, $file->original_filename, [
            'Content-Type' => $file->mimetype,
        ]);
    }
}
