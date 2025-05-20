<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Redirect;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        if (session()->has('selected_tool')) {
            $toolSlug = session('selected_tool');
            session()->forget('selected_tool');
            return redirect()->route('tools.show', $toolSlug);
        }

        return redirect()->route('dashboard');
    }
}
