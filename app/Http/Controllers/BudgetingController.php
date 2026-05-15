<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;  // 🔥 TAMBAHKAN INI
use Illuminate\Http\Request;

class BudgetingController extends Controller
{
    public function index(Request $request)
    {
        $userId = session('user_id');
        $mode = $request->input('mode', 'outcome'); // outcome atau income
        $sort = $request->input('sort', 'nama_asc');
        
        $query = Category::where('user_id', $userId)
            ->where('category_type', $mode);
            
        if ($sort == 'nama_asc') $query->orderBy('name', 'asc');
        elseif ($sort == 'nama_desc') $query->orderBy('name', 'desc');
        elseif ($sort == 'nilai_asc') $query->orderBy('value', 'asc');
        elseif ($sort == 'nilai_desc') $query->orderBy('value', 'desc');
        
        $kategori = $query->get();
        
        return view('budgeting.index', compact('kategori', 'mode', 'sort'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:auto,manual',
            'category_type' => 'required|in:income,outcome',
            'value' => 'required|numeric',
            'period' => 'nullable|in:weekly,monthly,yearly',
            'icon' => 'nullable|url',
            'is_primary_income' => 'sometimes|boolean'
        ]);
        
        Category::create([
            'name' => $request->name,
            'type' => $request->type,
            'category_type' => $request->category_type,
            'is_primary_income' => $request->is_primary_income ?? false,
            'value' => $request->value,
            'period' => $request->period,
            'icon' => $request->icon,
            'user_id' => session('user_id')
        ]);
        
        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', session('user_id'))
            ->firstOrFail();
            
        $request->validate([
            'name' => 'required',
            'type' => 'required|in:auto,manual',
            'value' => 'required|numeric',
            'period' => 'nullable|in:weekly,monthly,yearly',
            'icon' => 'nullable|url'
        ]);
        
        $category->update([
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'period' => $request->period,
            'icon' => $request->icon
        ]);
        
        return redirect()->back()->with('success', 'Kategori berhasil diupdate');
    }
    
    public function destroy($id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', session('user_id'))
            ->firstOrFail();
            
        $category->delete();
        
        return redirect()->back()->with('success', 'Kategori berhasil dihapus');
    }
    
    // 🔥 TAMBAHKAN METHOD INI
    public function applyToMonth()
    {
        $userId = session('user_id');
        $now = now();
        $month = $now->month;
        $year = $now->year;
        
        $categories = Category::where('user_id', $userId)->get();
        $count = 0;
        
        foreach ($categories as $category) {
            // Cek apakah sudah ada transaksi untuk kategori ini bulan ini
            $exists = Transaction::where('user_id', $userId)
                ->where('category_id', $category->id)
                ->where('month', $month)
                ->where('year', $year)
                ->exists();
            
            if (!$exists) {
                $nilaiBersih = $category->monthly_value;
                
                Transaction::create([
                    'user_id' => $userId,
                    'category_id' => $category->id,
                    'amount' => $nilaiBersih,
                    'transaction_date' => $now->format('Y-m-d'),
                    'month' => $month,
                    'year' => $year,
                    'description' => 'Diterapkan dari kategori: ' . $category->name,
                    'type' => $category->category_type
                ]);
                $count++;
            }
        }
        
        return redirect()->back()->with('success', "$count transaksi berhasil diterapkan ke bulan $month/$year");
    }
}