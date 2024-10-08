<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        //halaman yang akan dituju ketika user login
        return view('auth.login');
    }
    public function create()
    {
        //halaman yang akan ditujuu ketika user sign-up
        return view('auth.register');
    }

    public function login(Request $request)
    {
        //untuk vaalidasi form submit login
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        $infologin = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        //cek untuk menentukan halaman berdasarkan role
        if (Auth::attempt($infologin)) {
            if (Auth::user()) {
                $userRole = Auth::user()->role;

                if ($userRole === 'admin') {
                    return redirect()->route('admin')->with('success', 'Selamat datang Admin');
                } else {
                    return redirect()->route('user')->with('success', 'Selamat datang di Recielence.co');
                }
            }
        }
        //jika login gagal
        return redirect()->route('login')->withErrors(['error' => 'Email atau Password salah']);
    }

    public function register(Request $request)
    {
        //untuk validasi form submit pada auth.register
        $request->validate([
            'fullname' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'no_hp' => 'required',
        ], [
            'fullname.required' => 'Nama Wajib Diisi',
            'email.required' => 'Email Harus Diisi',
            'email.unique' => 'Email Telah Terdaftar',
            'password.required' => 'Password Wajib Diisi',
        ]);

        $user = new User();
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->no_hp = $request->no_hp;
        $user->role = 'user';
        $user->save();

        return redirect()->route('login')->with('success', 'Silahkan Login');

    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }
}
