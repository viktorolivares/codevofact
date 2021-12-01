<?php

namespace App\Http\Middleware;

use Closure;

class CheckModule
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
        $modules = $request->user()->modules()->pluck('value')->toArray();
        if(count($modules) > 0) {
            $path = explode('/', $request->path());
            if($path[0] !== '' && $path[0] !== 'dashboard') {
                $module = ($path[0] === 'persons')?$path[1]:$path[0];
                if(!in_array($module, $modules)) {
                    return response()->view('errors.403');
                }
            }
        }

        return $next($request);
    }
}
