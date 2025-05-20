<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Redirect;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if (session()->has('selected_tool')) {
            $toolSlug = session('selected_tool');
            session()->forget('selected_tool');
            return redirect()->route('tools.show', $toolSlug);
        }

        return redirect()->intended(route('dashboard'));
    }
}
