<?php

namespace App\Http\Controllers;

use App\Models\SupplierApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierApplicationController extends Controller
{
    public function create()
    {
        // Cek apakah user sudah pernah apply
        $existingApplication = SupplierApplication::where('user_id', Auth::id())
            ->latest()
            ->first();

        return view('supplier.apply', compact('existingApplication'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        SupplierApplication::create([
            'user_id' => Auth::id(),
            'company_name' => $validated['company_name'],
            'description' => $validated['description'],
            'phone' => $validated['phone'] ?? null,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Aplikasi supplier berhasil dikirim! Tim kami akan segera menghubungi Anda.');
    }
}