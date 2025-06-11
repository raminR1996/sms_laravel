<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectController extends Controller
{
    public function redirectToOtpLogin()
    {
        return Redirect::to('/otp-login', 301);
    }
}