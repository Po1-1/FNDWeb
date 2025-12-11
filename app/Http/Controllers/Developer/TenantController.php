<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class TenantController extends Controller
{
    /**
     * Menampilkan daftar semua tenant.
     */
    public function index()
    {
        $tenants = Tenant::with('users')->latest()->paginate(10);
        return view('developer.tenants.index', compact('tenants'));
    }

    /**
     * Menampilkan form untuk membuat tenant baru.
     */
    public function create()
    {
        return view('developer.tenants.create');
    }

    /**
     * Menyimpan tenant baru dan admin pertamanya.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tenant_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'admin_password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Buat Tenant
                $tenant = Tenant::create([
                    'name' => $request->tenant_name,
                ]);

                // 2. Buat User Admin untuk tenant tersebut
                User::create([
                    'tenant_id' => $tenant->id,
                    'name' => $request->admin_name,
                    'email' => $request->admin_email,
                    'password' => Hash::make($request->admin_password),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal membuat tenant. Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }

        return redirect()->route('developer.tenants.index')
            ->with('success', 'Tenant "' . $request->tenant_name . '" dan Admin berhasil dibuat.');
    }
}
