<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierApplication;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = SupplierApplication::with('user')->latest();
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        $applications = $query->paginate(15);
        
        return view('admin.suppliers.index', compact('applications'));
    }

    public function show($id)
    {
        $application = SupplierApplication::with('user')->findOrFail($id);
        return view('admin.suppliers.show', compact('application'));
    }

    public function approve($id)
    {
        $application = SupplierApplication::with('user')->findOrFail($id);
        
        // Update status aplikasi
        $application->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);

        // UBAH ROLE USER DARI CUSTOMER JADI SUPPLIER
        $supplierRole = \App\Models\Role::where('nama_role', 'supplier')->first();
        
        if ($supplierRole && $application->user) {
            $application->user->update([
                'role_id' => $supplierRole->id,
            ]);
        }

        return back()->with('success', 'Aplikasi supplier disetujui! Role user telah diubah menjadi Supplier.');
    }

    public function reject($id)
    {
        $application = SupplierApplication::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Aplikasi supplier ditolak.');
    }
}