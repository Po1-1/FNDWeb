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
                Rule::unique('alergis')->where('tenant_id', Auth::user()->tenant_id)
            ],
            'deskripsi' => 'nullable|string',
        ]);

        Alergi::create($request->all());

        return redirect()->route('admin.alergi.index')->with('success', 'Alergi berhasil ditambahkan.');
    }

    public function edit(Alergi $alergi)
    {
        return view('admin.alergi.edit', compact('alergi'));
    }

    public function update(Request $request, Alergi $alergi)
    {
        $request->validate([
            'nama' => [
                'required', 'string', 'max:255',
                Rule::unique('alergis')->where('tenant_id', Auth::user()->tenant_id)->ignore($alergi->id)
            ],
            'deskripsi' => 'nullable|string',
        ]);

        $alergi->update($request->all());

        return redirect()->route('admin.alergi.index')->with('success', 'Alergi berhasil diperbarui.');
    }

    public function destroy(Alergi $alergi)
    {
        $alergi->delete();
        return redirect()->route('admin.alergi.index')->with('success', 'Alergi berhasil dihapus.');
    }
}