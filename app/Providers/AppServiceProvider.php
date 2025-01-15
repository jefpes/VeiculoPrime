<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Support\Facades\{Schema, URL};
use Illuminate\Support\{Pluralizer, ServiceProvider};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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

        date_default_timezone_set("America/Sao_Paulo");

        if (env("APP_ENV") == "local") {
            setlocale(LC_ALL, "pt_BR", "pt_BR.utf-8", "pt_BR.utf-8", "portuguese");
        }

        if (env("APP_ENV") == "production") {
            setlocale(LC_TIME, 'pt_BR.utf8');
        }
        \Carbon\Carbon::setLocale('pt_BR');

        Schema::defaultStringLength(191);

        if (env('APP_AMBIENT') == 'production') {
            URL::forceScheme('https');
        }

        Authenticate::redirectUsing(fn (): string => Filament::getLoginUrl());

        AuthenticateSession::redirectUsing(
            fn (): string => Filament::getLoginUrl()
        );

        AuthenticationException::redirectUsing(
            fn (): string => Filament::getLoginUrl()
        );

        Pluralizer::useLanguage('portuguese');
    }
}
