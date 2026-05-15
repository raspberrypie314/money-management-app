<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        $mode = $request->get('mode', 'outcome'); // outcome atau income
        $sort = $request->get('sort', 'nama_asc'); // nama_asc, nama_desc, nilai_asc, nilai_desc
        
        // Hitung saldo efektif
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('type', 'income')->sum('amount');
        $totalOutcome = Transaction::where('user_id', $userId)
            ->where('type', 'outcome')->sum('amount');
        $saldoEfektif = $totalIncome - $totalOutcome;
        
        // Query kategori berdasarkan mode
        $query = Category::where('user_id', $userId)
            ->where('category_type', $mode);
            
        // Sorting
        if ($sort == 'nama_asc') $query->orderBy('name', 'asc');
        elseif ($sort == 'nama_desc') $query->orderBy('name', 'desc');
        elseif ($sort == 'nilai_asc') $query->orderBy('value', 'asc');
        elseif ($sort == 'nilai_desc') $query->orderBy('value', 'desc');
        
        $kategori = $query->get();
        
        return view('dashboard.index', compact('saldoEfektif', 'totalIncome', 'totalOutcome', 'kategori', 'mode', 'sort'));
    }
}