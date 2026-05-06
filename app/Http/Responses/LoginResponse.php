<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Http\Request;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Check if there's an intended URL in the session
        if ($request->session()->has('url.intended')) {
            $intended = $request->session()->pull('url.intended');
            return redirect($intended);
        }

        // Fall back to dashboard
        return redirect()->intended(route('dashboard'));
    }
}
