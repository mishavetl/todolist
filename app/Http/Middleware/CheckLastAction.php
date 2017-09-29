<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class CheckLastAction
{
    private function compareDouble(float $n1, float $n2)
    {
        return strval($n1) == strval($n2);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        $last_action = (float) request('last_action');
        if ($user->last_action != null && ($last_action == null || !$this->compareDouble($user->last_action, $last_action))) {
            return response('Conflict', 409);
        }

        $user->last_action = microtime(true);
        $user->save();

        $response = $next($request);
        $response->header('last_action', $user->last_action);

        return $response;
    }
}
