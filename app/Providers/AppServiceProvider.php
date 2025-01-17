<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\Middleware\AuthenticateSession;
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
            setlocale(LC_ALL, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");
        }

        if (env("APP_ENV") == "production") {
            $domain = "https://$domain";
            URL::forceScheme('https');
            setlocale(LC_TIME, 'pt_BR.utf8');
        }

        Config::set("APP_URL", $domain);

        Config::set("ASSET_URL", $domain);

        Authenticate::redirectUsing(fn (): string => Filament::getLoginUrl());

        AuthenticateSession::redirectUsing(fn (): string => Filament::getLoginUrl());

        AuthenticationException::redirectUsing(fn (): string => Filament::getLoginUrl());
    }
}
