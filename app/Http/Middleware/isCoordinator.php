<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;
class isCoordinator
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
        if ($request->user() && $request->user()->type == 'Coordinator') {
            return $next($request);
        }
        return Response::json([
            'unauthorized' => 'Acesso não autorizado'
        ], 401);
    }
}
