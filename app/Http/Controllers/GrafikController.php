<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        $viewType = $request->get('view_type', 'monthly');
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));
        
        if ($viewType == 'monthly') {
            // Data per kategori untuk bulan tertentu
            $data = Transaction::where('user_id', $userId)
                ->where('month', $month)
                ->where('year', $year)
                ->selectRaw('category_id, type, SUM(amount) as total')
                ->groupBy('category_id', 'type')
                ->with('category')
                ->get();
        } else {
            // Data per kategori untuk tahun tertentu
            $data = Transaction::where('user_id', $userId)
                ->where('year', $year)
                ->selectRaw('category_id, type, SUM(amount) as total')
                ->groupBy('category_id', 'type')
                ->with('category')
                ->get();
        }
        
        // Hitung persentase penghematan
        $totalIncome = $data->where('type', 'income')->sum('total');
        $totalOutcome = $data->where('type', 'outcome')->sum('total');
        
        $hematPersen = 0;
        if ($totalIncome > 0) {
            $hematPersen = (1 - ($totalOutcome / $totalIncome)) * 100;
            $hematPersen = round($hematPersen, 2);
        }
        
        return view('grafik.index', compact('data', 'hematPersen', 'viewType', 'month', 'year'));
    }
}