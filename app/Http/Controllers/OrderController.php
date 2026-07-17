<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Halaman "Pesanan Saya" untuk customer
     */
    public function index(Request $request)
    {
        // WAJIB: Filter hanya pesanan milik user yang login
        $query = Transaction::where('user_id', Auth::id())
            ->with(['details.product'])
            ->latest();
        
        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    /**
     * Detail pesanan customer
     */
    public function show($id)
    {
        // WAJIB: Pastikan customer cuma bisa lihat pesanan sendiri
        $order = Transaction::where('id', $id)
            ->where('user_id', Auth::id()) // ← INI PENTING! Security!
            ->with(['details.product'])
            ->firstOrFail();
        
        return view('orders.show', compact('order'));
    }
}