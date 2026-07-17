<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Transaction::where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Transaction::count();
        $pendingOrders = Transaction::where('status', 'pending')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::whereHas('role', function($q) {
            $q->where('nama_role', 'customer');
        })->count();

        $recentOrders = Transaction::with(['user', 'details.product'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'totalProducts',
            'totalCustomers',
            'recentOrders'
        ));
    }
}