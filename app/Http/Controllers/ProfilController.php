<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::find(session('user_id'));
        return view('profil.index', compact('user'));
    }
    
    public function updateName(Request $request)
    {
        $request->validate(['name' => 'required']);
        
        User::where('id', session('user_id'))->update(['name' => $request->name]);
        
        session(['user_name' => $request->name]);
        return redirect()->back()->with('success', 'Nama berhasil diupdate');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password'
        ]);
        
        $user = User::find(session('user_id'));
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah');
        }
        
        $user->update(['password' => Hash::make($request->new_password)]);
        
        return redirect()->back()->with('success', 'Password berhasil diupdate');
    }
    
    public function destroyAccount(Request $request)
    {
        $request->validate(['confirm_delete' => 'required|in:DELETE']);
        
        $user = User::find(session('user_id'));
        $user->delete(); // Cascade akan hapus semua kategori, transaksi, dll
        
        session()->flush();
        
        return redirect()->route('login')->with('success', 'Akun berhasil dihapus');
    }
}