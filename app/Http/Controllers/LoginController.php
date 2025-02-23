<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\IpLogin;
use App\Models\PresensiHarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login.index', [
            'title' => 'Login',
        ]);
    }

    public function authenticate(LoginRequest $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $ipAddress = $request->ip();

            if ($this->isAllowedIp($ipAddress)) {
                $ipId = IpLogin::where('alamat_ip', $ipAddress)->first()->id;

                PresensiHarian::create([
                    'pegawai_id' => $user->id,
                    'ip_login_id' => $ipId,
                    'waktu_kehadiran' => now(),
                ]);

                $request->session()->regenerate();

                return redirect()->intended('/');
            } else {
                Auth::logout();
                return back()->withErrors(['ip' => 'Login not allowed from this IP address.']);
            }
        }

        return back()->with('loginError', 'Your email or password is incorrect, please try again.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function isAllowedIp($ipAddress)
    {
        return IpLogin::where('alamat_ip', $ipAddress)->exists();
    }
}
