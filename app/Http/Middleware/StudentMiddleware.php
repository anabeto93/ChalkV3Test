<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Validator;

class StudentMiddleware
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
        if ($request->hasHeader('Authorization') && $request->header('Authorization') <> null){
            if (count(explode('~', $request->bearerToken())) <> 2){
                return response()->json([
                    "error" => true,
                    "code"  => 401,
                    "reason" => "token not well formatted"
                ]);
            }
            $token = getTokenFromBearer($request->bearerToken());
            $user = User::where('token', $token)->first();
            if ($user){
                $user->setTokenLastUsed(getTokenLastUsedFromBearer($request->bearerToken()))->save();
                return $next($request);
            }
            session()->flash('error', 'User does not exist!');
            return response()->json([
                "error" => true,
                "code"  => 401,
                "reason" => "User does not exist!"
            ]);
        }
        session()->flash('error', 'bearer not set!');
        return response()->json([
        "error" => true,
        "code"  => 403,
        "reason" => "bearer not set!"
    ]);

    }
}
