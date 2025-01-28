<?php

namespace App\Providers;

use Illuminate\Support\Facades\{Config, URL};
use Illuminate\Support\{ServiceProvider};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('Livewire\Features\SupportFileUploads\FileUploadController', 'App\Http\Controllers\FileUploadController');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '-1');
        ini_set('max_input_vars', '-1');
        ini_set('upload_max_filesize', '256M');
        ini_set('client_max_body_size ', '256M');
        ini_set('upload_max_size', '256M');
        ini_set('post_max_size', '256M');
        ini_set('max_execution_time', '600');

        $domain = $_SERVER["HTTP_HOST"] ?? null;

        date_default_timezone_set("America/Sao_Paulo");

        if (env("APP_ENV") == "local") {
            $domain = "http://$domain";
        }

        if (env("APP_ENV") == "production") {
            $domain = "https://$domain";
            URL::forceScheme('https');
        }

        Config::set("APP_URL", $domain);

        Config::set("ASSET_URL", $domain);
    }
}
