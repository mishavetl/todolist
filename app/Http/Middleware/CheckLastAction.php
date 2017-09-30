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
    public function handle($request, Closure $next, $skipTimestamp = false)
    {
        $user = auth()->user();
        $last_action = (float) request('last_action');
        
        if ($user->block) {
            return response('Locked', 423);
        }
        
        if (!$skipTimestamp && $user->last_action != null
            && ($last_action == null || !$this->compareDouble($user->last_action, $last_action))
        ) {
            return response('Conflict', 409);
        }

        $user->block = true;
        $user->last_action = microtime(true);
        $user->save();
        
        $response = $next($request);

        $user = auth()->user();
        $user->block = false;
        $user->last_action = microtime(true);
        $user->save();

        $response->header('last_action', $user->last_action);
        
        return $response;
    }
}
