<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role === 'developer') {
            return redirect()->route('developer.tenants.index');
        }

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } 
        
        if ($role === 'mentor') {
            return redirect()->route('mentor.dashboard');
        }
        
        if ($role === 'kasir') {
            return redirect()->route('kasir.dashboard');
        }

        // Default fallback jika role tidak dikenali
        return view('dashboard');
    }
}