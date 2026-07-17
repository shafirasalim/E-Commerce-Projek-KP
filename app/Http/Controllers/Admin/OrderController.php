<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.product'])->latest();
        
        // Filter berdasarkan status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->paginate(15);
        
        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Transaction::with(['user', 'details.product', 'payment'])
            ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped,completed,cancelled',
        ]);

        $order = Transaction::findOrFail($id);
        $order->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}