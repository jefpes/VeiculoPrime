<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FilamentSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        FilamentColor::register([
            'danger'  => (Auth::user()->settings->tertiary_color ?? Color::Rose),
            'gray'    => (Auth::user()->settings->primary_color ?? Color::Gray),
            'info'    => (Auth::user()->settings->quaternary_color ?? Color::Blue),
            'primary' => (Auth::user()->settings->secondary_color ?? Color::Indigo),
            'success' => (Auth::user()->settings->quinary_color ?? Color::Green),
            'warning' => (Auth::user()->settings->senary_color ?? Color::Yellow),
        ]);

        Filament::getPanel()
            ->topNavigation(Auth::user()->settings->navigation_mode ?? true)
            ->sidebarFullyCollapsibleOnDesktop()
            ->font(Auth::user()->settings->font ?? 'Inter');

        return $next($request);
    }
}
