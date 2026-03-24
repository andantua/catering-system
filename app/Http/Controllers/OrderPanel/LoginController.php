<?php

namespace App\Http\Controllers\OrderPanel;

use App\Http\Controllers\Controller;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('order-panel.login');
    }

   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $ward = Ward::where('email', $request->email)->first();

    if (!$ward || !Hash::check($request->password, $ward->password)) {
        return back()->with('error', 'Nieprawidłowy email lub hasło.');
    }

    Session::put('order_ward_id', $ward->id);
    Session::put('order_ward_name', $ward->name);

    // Przekieruj na DASHBOARD zamiast od razu do formularza
    return redirect()->route('order.dashboard')->with('success', 'Witaj, ' . $ward->name);
}

    public function logout()
    {
        Session::forget(['order_ward_id', 'order_ward_name']);
        return redirect()->route('order.login')->with('success', 'Wylogowano.');
    }
}