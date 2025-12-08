<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
    /**
     * Menampilkan halaman utama (welcome/home).
     */
    public function index()
    {
        // View 'welcome.blade.php' adalah halaman utama publik Anda
        return view('welcome'); 
    }

    /**
     * Menampilkan halaman "Apa itu FND".
     */
    public function about()
    {
        // Anda perlu membuat file view ini
        return view('public.about');
    }
}