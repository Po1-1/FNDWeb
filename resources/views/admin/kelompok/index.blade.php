<x-app-layout>
     <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Kelompok & Vendor</h1>
        <a href="{{ route('admin.kelompok.create') }}" class="btn btn-primary">
            + Buat Kelompok Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Kelompok</th>
                            <th class="text-center">Jumlah Anggota</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelompoks as $kelompok)
                            <tr>
                                <td>{{ $kelompok->nama }}</td>
                                
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $kelompok->mahasiswas_count }} Mahasiswa</span>
                                </td>
                                
                                <td class="text-end">
                                    <a href="{{ route('admin.kelompok.edit', $kelompok) }}" class="btn btn-sm btn-warning">
                                        Atur Jadwal & Anggota
                                    </a>
                                    
                                    <form action="{{ route('admin.kelompok.destroy', $kelompok) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus kelompok ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger ms-1">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-3 text-muted">
                                    Belum ada data kelompok. Silakan buat baru atau import data mahasiswa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $kelompoks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>