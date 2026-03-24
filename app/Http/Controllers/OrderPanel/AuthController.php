<?php

namespace App\Http\Controllers\OrderPanel;

use App\Http\Controllers\Controller;
use App\Models\OrderToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Wyświetl formularz do wpisania tokena (lub linku)
     */
    public function showTokenForm()
    {
        return view('order-panel.token-form');
    }
    
    /**
     * Wyświetl formularz wpisania kodu na podstawie tokena z URL
     */
    public function showCodeForm($token)
    {
        $orderToken = OrderToken::where('token', $token)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$orderToken) {
            return redirect()->route('order.token.form')
                ->with('error', 'Link wygasł lub jest nieprawidłowy.');
        }
        
        // Zapisz tymczasowo ID tokena w sesji
        Session::put('order_token_temp', $orderToken->id);
        
        return view('order-panel.code-form', [
            'token' => $token,
            'ward' => $orderToken->ward
        ]);
    }
    
    /**
     * Weryfikacja kodu 6-cyfrowego
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'token_id' => 'required|exists:order_tokens,id',
        ]);
        
        $orderToken = OrderToken::find($request->token_id);
        
        if (!$orderToken->isValid()) {
            return back()->with('error', 'Token wygasł lub został już użyty.');
        }
        
        if ($orderToken->code !== $request->code) {
            return back()->with('error', 'Nieprawidłowy kod.');
        }
        
        // Oznacz token jako użyty
        $orderToken->markAsUsed();
        
        // Zapisz w sesji
        Session::put('order_ward_id', $orderToken->ward_id);
        Session::put('order_token_id', $orderToken->id);
        
        return redirect()->route('order.form')
            ->with('success', 'Zalogowano pomyślnie! Możesz złożyć zamówienie.');
    }
    
    /**
     * Wylogowanie (opcjonalne)
     */
    public function logout()
    {
        Session::forget(['order_ward_id', 'order_token_id', 'order_token_temp']);
        return redirect()->route('order.token.form')
            ->with('success', 'Wylogowano pomyślnie.');
    }
}
