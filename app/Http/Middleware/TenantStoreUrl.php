<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class TenantStoreUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        config([
            'filesystems.disks.public.url' => sprintf(
                '%s://%s/storage',
                $request->isSecure() ? 'https' : 'http',
                $request->getHost()
            ),
        ]);

        // TODO: This is not working
        // config([
        //     'livewire.temporary_file_upload.directory' => sprintf(
        //         '%s://%s/storage/app/livewire-tmp',
        //         $request->isSecure() ? 'https' : 'http',
        //         $request->getHost(),
        //     )
        // ]);

        return $next($request);
    }
}
