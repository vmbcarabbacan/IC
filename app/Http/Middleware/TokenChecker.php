<?php

namespace App\Http\Middleware;

use App\Events\RefreshTokenEvent;
use App\Models\PassportToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ResponseTrait;
use App\Repositories\UserRepository;
use Laravel\Passport\Bridge\RefreshToken;

class TokenChecker extends UserRepository
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if(Auth::check()) {
            // add conditions
            $detail = PassportToken::where('user_id', auth()->user()->id)->first();
            $detail->active_time = $this->systemTimestamp();
            $detail->save();

            return $next($request);

        } else {
            if($token) {
                $detail = PassportToken::where('access_token', $token)->first();
                if(is_null($detail->active_time) || $detail->is_active == 0)
                    broadcast(new RefreshTokenEvent($detail->user_id, [], false));

                $remaining = ($detail->refresh_expires - $this->systemTimestamp()) / 60;
                $token_check = $this->getSetting('token_check');

                if($remaining <= (int) $token_check->value) {
                    $refresh = $this->refresh($detail->user_id);

                    broadcast(new RefreshToken($detail->user_id, $refresh));

                    return $this->resSuccess('Token refresh from middleware', $refresh);
                } 

                broadcast(new RefreshToken($detail->user_id, [], false));

                $detail->active_time = null;
                $detail->is_active = 0;
                $detail->save();
                
                return $this->resUnauthrized('Unauthorized access', ['token_checker' => false]);
            }
            return $this->resUnauthrized('Unauthorized access', ['token_checker' => false]);
        }
    }
}
