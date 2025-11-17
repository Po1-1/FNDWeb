<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Arahkan user berdasarkan role
    public function index()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'kasir':
                return redirect()->route('kasir.dashboard');
            case 'mentor':
                return redirect()->route('mentor.dashboard');
            default:
                // Untuk 'guest' atau role tidak dikenal
                return redirect()->route('home');
        }
    }
}