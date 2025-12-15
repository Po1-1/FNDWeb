// File: App\Http\Controllers\GuestController.php

// ... (methods index dan about tetap sama)

public function search(Request $request)
{
    $query = $request->input('query');
    
    // Deteksi subdomain (e.g., tenantA.fnd.com -> 'tenantA')
    $subdomain = explode('.', request()->getHost())[0];
    $tenant = Tenant::where('domain', $subdomain)->first();
    
    if (!$tenant) {
        // Fallback ke tenant default jika subdomain tidak ditemukan
        $tenant = Tenant::where('name', 'FND Default')->first();
    }
    
    if (!$tenant) {
        $results = collect();
        return view('public.search-results', compact('results', 'query'));
    }
    
    // Ambil event aktif dari tenant yang sesuai
    $activeEvent = Event::where('tenant_id', $tenant->id)
        ->where('is_active', true)
        ->first();
    
    if (!$activeEvent) {
        $results = collect();
        return view('public.search-results', compact('results', 'query'));
    }
    
    $results = Mahasiswa::query()
        ->with(['alergi', 'kelompok.vendor'])
        
        // 💡 FIX 1: PENCEGAHAN DUPLIKASI QUERY (Wajib ditambahkan)
        // Paksa hanya memilih kolom dari Mahasiswa dan hasil unik
        ->select('mahasiswas.*') 
        ->distinct('mahasiswas.id') 

        ->where('event_id', $activeEvent->id)
        ->when($query, function ($q, $query) use ($activeEvent) {
            $q->where(function ($subQ) use ($query, $activeEvent) {
                $subQ->where('nama', 'LIKE', "%{$query}%")
                    ->orWhere('nim', 'LIKE', "%{$query}%")
                    ->orWhereHas('kelompok', function ($kelompokQuery) use ($query, $activeEvent) {
                        $kelompokQuery->where('nama', 'LIKE', "%{$query}%")
                            // Menjamin Kelompok dari Event Aktif (Sudah benar)
                            ->where('event_id', $activeEvent->id); 
                    });
            });
        })
        ->paginate(15);
    
    return view('public.search-results', compact('results', 'query'));
}