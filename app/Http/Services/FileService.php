<?php

namespace App\Http\Services;

use App\Enums\FileTypes;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{
    public static function process(
        string $raw,
        ?string $storeName = null,
        ?string $mimetype = null,
        int $size = 0,
        FileTypes $type = FileTypes::UNKNOWN,
        array $metadata = []
    ) {
        $storeName ??= Str::ulid()
            ->toString();

        $userId = Auth::id();
        $hash = hash('sha256', $raw);

        $matched = File::query()
            ->where('hash', $hash)
            ->first();

        if ($matched === null) {
            $disk = config('furcw.file.default_disk', 'local');
            $path = $storeName;

            Storage::disk($disk)
                ->put($path, $raw);
        } else {
            if ($matched->user_id === $userId) {
                return $matched;
            }

            $disk = $matched->disk;
            $path = $matched->path;
        }

        return File::query()
            ->create([
                'user_id' => $userId,
                'disk' => $disk,
                'path' => $path,
                'original_filename' => '-',
                'mimetype' => $mimetype ?? 'application/octet-stream',
                'size' => $size,
                'ip' => Request::ip(),
                'type' => $type,
                'metadata' => $metadata,
                'hash' => $hash,
            ]);
    }
}
