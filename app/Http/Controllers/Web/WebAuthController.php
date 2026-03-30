<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class WebAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function showDashboard()
    {
        return view('dashboard.admin');
    }
    public function showPimpinanDashboard()
{
    return view('dashboard.pimpinan');
}
}