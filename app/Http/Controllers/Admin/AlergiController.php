<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alergi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AlergiController extends Controller
{
    public function index()
    {
        // Trait BelongsToTenant akan otomatis memfilter berdasarkan tenant_id user
        $alergis = Alergi::orderBy('nama')->paginate(10);
        return view('admin.alergi.index', compact('alergis'));
    }

    public function create()
    {
        return view('admin.alergi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                // Validasi unik berdasarkan tenant user yang login
                Rule::unique('alergis')->where('tenant_id', Auth::user()->tenant_id)
            ],
            'deskripsi' => 'nullable|string',
        ]);

        // Trait akan otomatis mengisi tenant_id
        Alergi::create($request->all());

        return redirect()->route('admin.alergi.index')->with('success', 'Alergi berhasil ditambahkan.');
    }

    public function edit(Alergi $alergi)
    {
        // Trait sudah melakukan scope, jadi ini aman
        return view('admin.alergi.edit', compact('alergi'));
    }

    public function update(Request $request, Alergi $alergi)
    {
        $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                // Menggunakan fasad Auth agar konsisten
                Rule::unique('alergis')->where('tenant_id', Auth::user()->tenant_id)->ignore($alergi->id)
            ],
            'deskripsi' => 'nullable|string',
        ]);

        $alergi->update($request->all());

        return redirect()->route('admin.alergi.index')->with('success', 'Alergi berhasil diperbarui.');
    }

    public function destroy(Alergi $alergi)
    {
        // Trait sudah melakukan scope, jadi ini aman
        $alergi->delete();
        return redirect()->route('admin.alergi.index')->with('success', 'Alergi berhasil dihapus.');
    }
}