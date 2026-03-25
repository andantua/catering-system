<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $deadline = '18:00';
    
    public function index(Request $request)
    {
        $selectedDate = $request->get('date') ? Carbon::parse($request->get('date')) : today();
        $today = today();
        $deadline = Carbon::today()->setTimeFromTimeString($this->deadline);
        $now = Carbon::now();
        
        // Zamówienia na wybraną datę (zatwierdzone)
        $orders = Order::with(['ward', 'diet'])
            ->whereDate('order_date', $selectedDate)
            ->whereNotNull('submitted_at')
            ->get();
        
        // Zestawienie według diet
        $summaryByDiet = $orders->groupBy('diet.name')
            ->map(function ($group) {
                return $group->sum('quantity');
            })
            ->sortDesc();
        
        // Zestawienie według oddziałów
        $summaryByWard = $orders->groupBy('ward.name')
            ->map(function ($group) {
                return [
                    'total' => $group->sum('quantity'),
                    'details' => $group->groupBy('diet.name')->map->sum('quantity'),
                ];
            });
        
        // Oddziały które nie złożyły zamówienia
        $submittedWardIds = $orders->pluck('ward_id')->unique();
        $wardsNotSubmitted = Ward::whereNotIn('id', $submittedWardIds)->get();
        
        // Statystyki
        $totalOrders = $orders->sum('quantity');
        $totalWards = Ward::count();
        $submittedCount = $submittedWardIds->count();
        
        // Status timera
        $isAfterDeadline = $now->greaterThan($deadline);
        $minutesRemaining = $isAfterDeadline ? 0 : $deadline->diffInMinutes($now);
        
        return view('admin-panel.dashboard', [
            'orders' => $orders,
            'summaryByDiet' => $summaryByDiet,
            'summaryByWard' => $summaryByWard,
            'wardsNotSubmitted' => $wardsNotSubmitted,
            'deadline' => $this->deadline,
            'isAfterDeadline' => $isAfterDeadline,
            'minutesRemaining' => $minutesRemaining,
            'timerClass' => $this->getTimerClass($minutesRemaining),
            'totalOrders' => $totalOrders,
            'totalWards' => $totalWards,
            'submittedCount' => $submittedCount,
            'selectedDate' => $selectedDate,
        ]);
    }
    
    /**
     * Wydruk zamówień dla wybranego oddziału
     */
    public function printWardOrders($wardId, $date = null)
    {
        $ward = Ward::findOrFail($wardId);
        $orderDate = $date ? Carbon::parse($date) : today();
        
        $orders = Order::with('diet')
            ->where('ward_id', $wardId)
            ->whereDate('order_date', $orderDate)
            ->whereNotNull('submitted_at')
            ->get();
        
        $total = $orders->sum('quantity');
        
        return view('admin-panel.print.ward-orders', compact('ward', 'orders', 'total', 'orderDate'));
    }
    
    /**
     * Zbiorczy wydruk dla kuchni – wszystkie zamówienia
     */
    public function printKitchen($date = null)
    {
        $orderDate = $date ? Carbon::parse($date) : today();
        
        // Pobierz wszystkie zamówienia na wybraną datę
        $orders = Order::with(['ward', 'diet'])
            ->whereDate('order_date', $orderDate)
            ->whereNotNull('submitted_at')
            ->get();
        
        // Grupowanie według oddziałów i diet
        $wardsSummary = $orders->groupBy('ward.name')->map(function($wardOrders) {
            return [
                'total' => $wardOrders->sum('quantity'),
                'details' => $wardOrders->groupBy('diet.name')->map->sum('quantity')
            ];
        });
        
        // Zestawienie zbiorcze według diet (dla kuchni)
        $summaryByDiet = $orders->groupBy('diet.name')
            ->map(function($group) {
                return $group->sum('quantity');
            })
            ->sortDesc();
        
        $totalAll = $orders->sum('quantity');
        $wardsCount = $wardsSummary->count();
        $wardsSubmitted = $orders->pluck('ward_id')->unique()->count();
        $totalWards = Ward::count();
        
        return view('admin-panel.print.kitchen', compact(
            'orders', 'wardsSummary', 'summaryByDiet', 'totalAll', 
            'orderDate', 'wardsCount', 'wardsSubmitted', 'totalWards'
        ));
    }
    
    /**
     * Pobiera klasę CSS dla timera
     */
    private function getTimerClass($minutesRemaining): string
    {
        if ($minutesRemaining < 30 && $minutesRemaining > 0) {
            return 'danger-pulse';
        } elseif ($minutesRemaining < 120 && $minutesRemaining > 0) {
            return 'warning';
        } elseif ($minutesRemaining > 0) {
            return 'success';
        } else {
            return 'blocked';
        }
    }
}