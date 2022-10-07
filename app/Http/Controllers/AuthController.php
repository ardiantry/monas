<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
         
        return view('pages.login');
    }

    public function proses_login(Request $request)
    {
        request()->validate(
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );

        $kredensil = $request->only('email', 'password');

        if (Auth::attempt($kredensil)) {
            $user = Auth::user();
            if ($user->role == 'super-admin') {
                return redirect()->to('/dashboard');
            } elseif ($user->role == 'admin') {
                return redirect()->to('/dashboard');
            } elseif ($user->role == 'kader') {
                return redirect()->to('/dashboard');
            }
            return redirect()->to('login');
        }

        return redirect('login')
            ->withInput()
            ->withErrors(['login_gagal' => 'These credentials do not match our records.']);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        Auth::logout();
        return Redirect()->to('login')->with('sukses', 'Logout Berhasil');
    }
}
