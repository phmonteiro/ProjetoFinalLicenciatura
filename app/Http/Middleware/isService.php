<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;
class isService
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user() && ($request->user()->type == 'Services' || $request->user()->type == 'SAPE' || $request->user()->type == 'SAS' || $request->user()->type == 'CRID' || $request->user()->type == 'UED' || $request->user()->type == 'DST' || $request->user()->type == 'SA')) {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Acesso não autorizado'
        ], 401);
    }
}
