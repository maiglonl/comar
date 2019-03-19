<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;

class PasswordExpired {

    public function handle($request, Closure $next){
        $user = $request->user();

        /*if ($user->password_changed_at == null) {
            return redirect()->route('password.expired');
        }*/

        return $next($request);
    }
}
