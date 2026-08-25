<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$transactions, $from, $to] = $this->getData($request);
        $summary = $this->getSummary($transactions);

        return view('admin.reports.index', compact('transactions', 'from', 'to', 'summary'));
    }

    public function download(Request $request)
    {
        [$transactions, $from, $to] = $this->getData($request);
        $summary = $this->getSummary($transactions);
        $format = $request->input('format', 'pdf');
        $filename = 'laporan-penjualan_' . $from . '_' . $to;

        if ($format === 'csv') {
            return $this->downloadCsv($transactions, $summary, $from, $to, $filename . '.csv');
        }

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'transactions' => $transactions,
            'summary' => $summary,
            'from' => $from,
            'to' => $to,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename . '.pdf');
    }

    private function getData(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('transaction_date', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->latest('transaction_date')
            ->get();

        return [$transactions, $from, $to];
    }

    private function getSummary($transactions): array
    {
        $paid = $transactions->where('status', 'paid');

        return [
            'total_transaksi' => $transactions->count(),
            'total_paid'      => $paid->count(),
            'total_pending'   => $transactions->where('status', 'pending')->count(),
            'omzet'           => $paid->sum('total_amount'),
            'produk_terjual'  => $paid->sum(fn ($t) => $t->details->sum('quantity')),
        ];
    }

    private function downloadCsv($transactions, $summary, $from, $to, $filename)
    {
        $callback = function () use ($transactions, $summary, $from, $to) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // BOM biar Excel baca UTF-8

            fputcsv($file, ['LAPORAN PENJUALAN - CIANJUR FRESH']);
            fputcsv($file, ['Periode', $from . ' s/d ' . $to]);
            fputcsv($file, ['Omzet (Paid)', $summary['omzet']]);
            fputcsv($file, ['Total Transaksi', $summary['total_transaksi']]);
            fputcsv($file, ['Transaksi Paid', $summary['total_paid']]);
            fputcsv($file, ['Produk Terjual', $summary['produk_terjual']]);
            fputcsv($file, []);
            fputcsv($file, ['No. Invoice', 'Tanggal', 'Pelanggan', 'Jumlah Item', 'Status', 'Total (Rp)']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    'INV-' . $t->id,
                    \Carbon\Carbon::parse($t->transaction_date)->format('d/m/Y H:i'),
                    $t->user->name ?? '-',
                    $t->details->sum('quantity'),
                    strtoupper($t->status),
                    number_format($t->total_amount, 0, ',', '.'),
                ]);
            }

            fclose($file);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}