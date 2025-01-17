<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controllers\{HasMiddleware, Middleware};
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportFileUploads\{FileUploadConfiguration, TemporaryUploadedFile};

class FileUploadController implements HasMiddleware
{
    public static function middleware()
    {
        $middleware = (array) FileUploadConfiguration::middleware();

        if (!in_array('web', $middleware)) {
            $middleware = array_merge(['web'], $middleware);
        }

        return array_map(fn ($middleware) => new Middleware($middleware), $middleware);
    }

    public function handle()//@phpstan-ignore-line
    {
        // DANGER AJUDAR OS OUTROS QUE DEREM PROBLEMA EM UPLOAD DE ARQUIVOS
        // abort_unless(request()->hasValidSignature(), 401);

        $disk = FileUploadConfiguration::disk();

        $filePaths = $this->validateAndStore(request('files'), $disk);

        return ['paths' => $filePaths];
    }

    public function validateAndStore($files, $disk)//@phpstan-ignore-line
    {
        Validator::make(['files' => $files], [
            'files.*' => FileUploadConfiguration::rules(),
        ])->validate();

        $fileHashPaths = collect($files)->map(function ($file) use ($disk) {//@phpstan-ignore-line
            $filename = TemporaryUploadedFile::generateHashNameWithOriginalNameEmbedded($file);

            return $file->storeAs('/' . FileUploadConfiguration::path(), $filename, [
                'disk' => $disk,
            ]);
        });

        // Strip out the temporary upload directory from the paths.
        return $fileHashPaths->map(function ($path) { return str_replace(FileUploadConfiguration::path('/'), '', $path); });
    }
}
