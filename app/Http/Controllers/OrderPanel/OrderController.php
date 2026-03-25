<?php

namespace App\Http\Controllers\OrderPanel;

use App\Http\Controllers\Controller;
use App\Models\Diet;
use App\Models\Order;
use App\Models\Ward;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class OrderController extends Controller
{
    private $deadline = '18:00';
    
    /**
     * Dashboard oddziału - widok główny po zalogowaniu
     */
    public function dashboard()
    {
        $wardId = Session::get('order_ward_id');
        $ward = Ward::findOrFail($wardId);
        
        // Sprawdź czy dzisiaj zostało złożone zamówienie
        $todayOrder = Order::with('diet')
            ->where('ward_id', $wardId)
            ->whereDate('order_date', today())
            ->whereNotNull('submitted_at')
            ->get();
        
        $hasSubmittedToday = $todayOrder->isNotEmpty();
        $todayTotal = $todayOrder->sum('quantity');
        
        // Ostatnie zamówienia (ostatnie 7 dni)
        $recentOrders = Order::with('diet')
            ->where('ward_id', $wardId)
            ->whereNotNull('submitted_at')
            ->orderBy('order_date', 'desc')
            ->limit(10)
            ->get()
            ->groupBy('order_date');
        
        // Statystyki
        $stats = [
            'total_orders' => Order::where('ward_id', $wardId)->whereNotNull('submitted_at')->sum('quantity'),
            'total_days' => Order::where('ward_id', $wardId)->whereNotNull('submitted_at')->distinct('order_date')->count('order_date'),
            'avg_per_day' => 0,
        ];
        
        if ($stats['total_days'] > 0) {
            $stats['avg_per_day'] = round($stats['total_orders'] / $stats['total_days']);
        }
        
        // Czy można złożyć zamówienie? (przed 18:00)
        $canOrder = Carbon::now()->format('H:i') <= '18:00';
        
        return view('order-panel.dashboard', compact(
            'ward', 'todayOrder', 'hasSubmittedToday', 'todayTotal',
            'recentOrders', 'stats', 'canOrder'
        ));
    }

    /**
     * Historia zamówień
     */
    public function history()
    {
        $wardId = Session::get('order_ward_id');
        $ward = Ward::findOrFail($wardId);
        
        $orders = Order::with('diet')
            ->where('ward_id', $wardId)
            ->whereNotNull('submitted_at')
            ->orderBy('order_date', 'desc')
            ->paginate(20);
        
        return view('order-panel.history', compact('ward', 'orders'));
    }
    
    /**
     * Wyświetl formularz zamówienia z licznikami diet
     */
    public function showForm(Request $request)
    {
        $wardId = Session::get('order_ward_id');
        $ward = Ward::findOrFail($wardId);
        
        // Data zamówienia (domyślnie następna dostępna data)
        $orderDate = $request->get('date', $this->getNextOrderDate());
        
        // Sprawdź czy można złożyć zamówienie na wybraną datę
        $canOrderForDate = $this->canOrderForDate($orderDate);
        
        // Sprawdź czy już złożono zamówienie na wybraną datę
        $existingOrder = Order::where('ward_id', $wardId)
            ->whereDate('order_date', $orderDate)
            ->whereNotNull('submitted_at')
            ->first();
            
        if ($existingOrder) {
            return redirect()->route('order.dashboard')
                ->with('info', 'Zamówienie na dzień ' . Carbon::parse($orderDate)->format('d.m.Y') . ' zostało już złożone.');
        }
        
        // Pobierz wszystkie diety
        $diets = Diet::orderBy('sort_order')->get();
        
        // Pobierz istniejące zamówienie (wersja robocza)
        $currentOrder = Order::where('ward_id', $wardId)
            ->whereDate('order_date', $orderDate)
            ->whereNull('submitted_at')
            ->get();
            
        $quantities = [];
        foreach ($currentOrder as $item) {
            $quantities[$item->diet_id] = $item->quantity;
        }
        
        return view('order-panel.order-form', [
            'ward' => $ward,
            'diets' => $diets,
            'quantities' => $quantities,
            'orderDate' => $orderDate,
            'deadline' => $this->deadline,
            'canOrder' => $canOrderForDate,
            'maxOrderDate' => Carbon::now()->addDays(7)->format('Y-m-d'),
        ]);
    }
    
    /**
     * Pobiera następną datę, na którą można złożyć zamówienie
     */
    private function getNextOrderDate()
    {
        $now = Carbon::now();
        $deadlineTime = Carbon::today()->setTimeFromTimeString($this->deadline);
        
        // Jeśli przed 18:00 – można zamówić na jutro
        if ($now->lt($deadlineTime)) {
            return Carbon::tomorrow()->format('Y-m-d');
        }
        // Jeśli po 18:00 – można zamówić na pojutrze
        else {
            return Carbon::tomorrow()->addDay()->format('Y-m-d');
        }
    }
    
    /**
     * Sprawdza czy można złożyć zamówienie na wybraną datę
     */
    private function canOrderForDate($date)
    {
        $orderDate = Carbon::parse($date);
        $now = Carbon::now();
        $deadlineTime = Carbon::today()->setTimeFromTimeString($this->deadline);
        
        // Nie można zamawiać na dni wstecz
        if ($orderDate->lt(Carbon::today())) {
            return false;
        }
        
        // Zamówienie na dziś – tylko przed 18:00
        if ($orderDate->isToday()) {
            return $now->lt($deadlineTime);
        }
        
        // Zamówienie na jutro i dalej – zawsze można (max 7 dni do przodu)
        return $orderDate->lte(Carbon::now()->addDays(7));
    }
    
    /**
     * Zapisz zamówienie (wersja robocza – AJAX)
     */
    public function saveDraft(Request $request)
    {
        $wardId = Session::get('order_ward_id');
        $orderDate = $request->get('order_date', $this->getNextOrderDate());
        
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);
        
        // Usuń istniejącą wersję roboczą
        Order::where('ward_id', $wardId)
            ->whereDate('order_date', $orderDate)
            ->whereNull('submitted_at')
            ->delete();
        
        // Zapisz nową wersję roboczą
        foreach ($request->quantities as $dietId => $quantity) {
            if ($quantity > 0) {
                Order::create([
                    'ward_id' => $wardId,
                    'diet_id' => $dietId,
                    'quantity' => $quantity,
                    'order_date' => $orderDate,
                    'submitted_at' => null,
                ]);
            }
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Zatwierdź zamówienie (ostateczne)
     */
    public function submit(Request $request)
    {
        $wardId = Session::get('order_ward_id');
        $ward = Ward::find($wardId);
        
        // Pobierz datę zamówienia
        $orderDate = $request->get('order_date', $this->getNextOrderDate());
        
        // Sprawdź czy można złożyć zamówienie na wybraną datę
        if (!$this->canOrderForDate($orderDate)) {
            return back()->with('error', 'Nie można złożyć zamówienia na wybraną datę.');
        }
        
        // Sprawdź czy już nie złożono
        $existing = Order::where('ward_id', $wardId)
            ->whereDate('order_date', $orderDate)
            ->whereNotNull('submitted_at')
            ->first();
            
        if ($existing) {
            return redirect()->route('order.dashboard')
                ->with('info', 'Zamówienie na dzień ' . Carbon::parse($orderDate)->format('d.m.Y') . ' zostało już złożone.');
        }
        
        $request->validate([
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);
        
        // Usuń istniejącą wersję roboczą dla tej daty
        Order::where('ward_id', $wardId)
            ->whereDate('order_date', $orderDate)
            ->delete();
        
        // Zapisz zamówienie
        foreach ($request->quantities as $dietId => $quantity) {
            if ($quantity > 0) {
                Order::create([
                    'ward_id' => $wardId,
                    'diet_id' => $dietId,
                    'quantity' => $quantity,
                    'order_date' => $orderDate,
                    'submitted_at' => now(),
                ]);
            }
        }
        
        // Wyślij potwierdzenie e-mail
        $orders = Order::with('diet')
            ->where('ward_id', $wardId)
            ->whereDate('order_date', $orderDate)
            ->whereNotNull('submitted_at')
            ->get();
            
        // Wyślij potwierdzenie e-mail (opcjonalnie – nie blokuj zamówienia jeśli mail nie działa)
        try {
            Mail::to($ward->email)->send(new OrderConfirmation($ward, $orders, now()));
        } catch (\Exception $e) {
            // Log błędu, ale nie przerywaj – zamówienie zostało zapisane
            \Log::warning('Nie udało się wysłać emaila potwierdzającego dla ' . $ward->name . ': ' . $e->getMessage());
        }
        
        return redirect()->route('order.dashboard')
            ->with('success', 'Zamówienie na dzień ' . Carbon::parse($orderDate)->format('d.m.Y') . ' zostało złożone!');
    }
    
    /**
     * Strona potwierdzenia (opcjonalna)
     */
    public function confirmation()
    {
        $wardId = Session::get('order_ward_id');
        $ward = Ward::findOrFail($wardId);
        
        $orders = Order::with('diet')
            ->where('ward_id', $wardId)
            ->whereDate('order_date', today())
            ->whereNotNull('submitted_at')
            ->get();
            
        if ($orders->isEmpty()) {
            return redirect()->route('order.dashboard')
                ->with('warning', 'Nie znaleziono złożonego zamówienia.');
        }
            
        return view('order-panel.confirmation', [
            'ward' => $ward,
            'orders' => $orders,
            'submittedAt' => $orders->first()->submitted_at,
        ]);
    }
    
    /**
     * Sprawdza czy jest po deadline
     */
    private function isAfterDeadline(): bool
    {
        return Carbon::now()->format('H:i') > $this->deadline;
    }
    
    /**
     * Pobiera pozostały czas w minutach
     */
    private function getTimeRemaining(): int
    {
        $deadline = Carbon::today()->setTimeFromTimeString($this->deadline);
        $now = Carbon::now();
        
        if ($now->greaterThan($deadline)) {
            return 0;
        }
        
        return $deadline->diffInMinutes($now);
    }
    
    /**
     * Pobiera klasę CSS dla timera
     */
    private function getTimerClass(): string
    {
        $minutesRemaining = $this->getTimeRemaining();
        
        if ($this->isAfterDeadline()) {
            return 'blocked';
        } elseif ($minutesRemaining < 30) {
            return 'danger-pulse';
        } elseif ($minutesRemaining < 120) {
            return 'warning';
        } else {
            return 'success';
        }
    }
}