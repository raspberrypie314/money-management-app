<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        $viewType = $request->get('view_type', 'monthly'); // monthly atau yearly
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        if ($viewType == 'monthly') {
            // Query transaksi per bulan
            $transactions = Transaction::where('user_id', $userId)
                ->where('month', $month)
                ->where('year', $year)
                ->with('category')
                ->orderBy('transaction_date', 'desc')
                ->get();
                
            $totalMasuk = Transaction::where('user_id', $userId)
                ->where('month', $month)->where('year', $year)
                ->where('type', 'income')->sum('amount');
                
            $totalKeluar = Transaction::where('user_id', $userId)
                ->where('month', $month)->where('year', $year)
                ->where('type', 'outcome')->sum('amount');
        } else {
            // Query transaksi per tahun
            $transactions = Transaction::where('user_id', $userId)
                ->where('year', $year)
                ->with('category')
                ->orderBy('transaction_date', 'desc')
                ->get();
                
            $totalMasuk = Transaction::where('user_id', $userId)
                ->where('year', $year)
                ->where('type', 'income')->sum('amount');
                
            $totalKeluar = Transaction::where('user_id', $userId)
                ->where('year', $year)
                ->where('type', 'outcome')->sum('amount');
        }
        
        // Saldo tersedia
        $totalIncome = Transaction::where('user_id', $userId)->where('type', 'income')->sum('amount');
        $totalOutcome = Transaction::where('user_id', $userId)->where('type', 'outcome')->sum('amount');
        $saldoTersedia = $totalIncome - $totalOutcome;
        
        return view('riwayat.index', compact(
            'transactions', 'totalMasuk', 'totalKeluar', 'saldoTersedia',
            'viewType', 'month', 'year'
        ));
    }
    
    public function destroy($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', session('user_id'))
            ->firstOrFail();
            
        $transaction->delete();
        
        return redirect()->back()->with('success', 'Data transaksi berhasil dihapus');
    }
}