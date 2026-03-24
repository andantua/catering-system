<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ward;
use App\Models\OrderToken;
use App\Mail\OrderInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReminderController extends Controller
{
    /**
     * Wyślij przypomnienia do oddziałów które nie złożyły zamówienia
     */
    public function sendReminders(Request $request)
    {
        $today = today();
        
        // Znajdź oddziały które już złożyły zamówienie
        $submittedWardIds = Order::whereDate('order_date', $today)
            ->whereNotNull('submitted_at')
            ->pluck('ward_id')
            ->unique();
            
        // Oddziały do przypomnienia
        $wardsToRemind = Ward::whereNotIn('id', $submittedWardIds)->get();
        
        if ($wardsToRemind->isEmpty()) {
            return back()->with('info', 'Wszystkie oddziały złożyły już zamówienie.');
        }
        
        $count = 0;
        $errors = [];
        
        foreach ($wardsToRemind as $ward) {
            try {
                // Wygeneruj nowy token
                $token = OrderToken::generateForWard($ward);
                
                // Wyślij email
                Mail::to($ward->email)->send(new OrderInvitation($ward, $token->token, $token->code));
                $count++;
            } catch (\Exception $e) {
                $errors[] = "{$ward->name}: {$e->getMessage()}";
            }
        }
        
        $message = "Wysłano przypomnienia do {$count} oddziałów.";
        if (!empty($errors)) {
            $message .= " Błędy: " . implode(', ', $errors);
        }
        
        return back()->with('success', $message);
    }
    
    /**
     * Wyślij przypomnienie do konkretnego oddziału
     */
    public function sendSingleReminder($wardId)
    {
        $ward = Ward::findOrFail($wardId);
        
        // Sprawdź czy oddział już złożył zamówienie
        $hasSubmitted = Order::where('ward_id', $wardId)
            ->whereDate('order_date', today())
            ->whereNotNull('submitted_at')
            ->exists();
            
        if ($hasSubmitted) {
            return back()->with('warning', "Oddział {$ward->name} już złożył zamówienie.");
        }
        
        // Wygeneruj token i wyślij
        $token = OrderToken::generateForWard($ward);
        Mail::to($ward->email)->send(new OrderInvitation($ward, $token->token, $token->code));
        
        return back()->with('success', "Wysłano przypomnienie do {$ward->name}.");
    }
}