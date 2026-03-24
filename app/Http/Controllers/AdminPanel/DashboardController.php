<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ward;
use Carbon\Carbon;

class DashboardController extends Controller
{
    private $deadline = '18:00';
    
    public function index()
    {
        $today = today();
        $deadline = Carbon::today()->setTimeFromTimeString($this->deadline);
        $now = Carbon::now();
        
        // Zamówienia na dziś (zatwierdzone)
        $orders = Order::with(['ward', 'diet'])
            ->whereDate('order_date', $today)
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
        ]);
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