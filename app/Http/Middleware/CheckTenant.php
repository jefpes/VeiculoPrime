<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTenant
{
    public function __construct(
        private readonly Tenant $tenant,
    ) {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $hasAccess = session()->get('has_access');

        if ($hasAccess) {
            return $next($request);
        }

        $tenant = $this->validTenancyExists($request);

        // Se não há subdomain, permite acesso direto.
        if ($tenant === true) {
            session()->put('is_master', true); // Define que está no modo master.

            return $next($request);
        }

        // Verifica se o tenant está ativo
        if (!$tenant?->active && config('app.url') !== env('APP_URL')) {
            abort(403, 'Tenant is inactive. Access forbidden.');
        }

        session()->put('tenant', $tenant);

        if (is_null($request->user())) {
            return $next($request);
        }

        // Permitir usuários sem tenant (exceção)
        if (is_null($request->user()->tenant)) {
            session()->put('is_master', true); // Usuário sem tenant tratado como master.

            return $next($request);
        }

        // Verifica se o tenant do usuário logado corresponde ao tenant da sessão
        if (auth_user()->tenant_id !== $tenant->id) {
            Auth::logout();

            return redirect('/login')->with('no_access', true);
        }

        $subdomainUrl = subdomain_url($tenant->domain, '');

        config(['app.url' => $subdomainUrl]);

        $request->session()->put('has_access', true);

        return $next($request);
    }

    protected function validTenancyExists(Request $request): mixed
    {
        list($subdomain) = explode('.', $request->getHost(), 2);

        // Se não há subdomain, retorna true para indicar acesso master
        if (empty($subdomain)) {
            return true;
        }

        $tenant = $this->tenant->where('domain', $subdomain)->first(); //@phpstan-ignore-line

        if ($tenant === null && config('app.url') !== env('APP_URL')) {
            abort(404);
        }

        return $tenant;
    }
}
