<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Total pesanan
        $totalOrders = Transaction::where('user_id', $userId)->count();

        // Pesanan aktif (pending, paid, shipped)
        $activeOrders = Transaction::where('user_id', $userId)
            ->whereIn('status', ['pending', 'paid', 'shipped'])
            ->count();

        // Pesanan selesai
        $completedOrders = Transaction::where('user_id', $userId)
            ->where('status', 'completed')
            ->count();

        // 5 pesanan terbaru
        $recentOrders = Transaction::where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalOrders',
            'activeOrders',
            'completedOrders',
            'recentOrders'
        ));

        
    }
}