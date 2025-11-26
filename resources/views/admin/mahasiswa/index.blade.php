<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Mahasiswa</h1>
        <div>
            <a href="{{ route('admin.mahasiswa.import.form') }}" class="btn btn-success me-2">
                Import XLSX
            </a>
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">
                + Tambah Mahasiswa
            </a>
            <form action="{{ route('admin.mahasiswa.destroyAll') }}" method="POST" 
                  onsubmit="return confirm('⚠️ PERINGATAN TAHAP 1 ⚠️\n\nApakah Anda yakin ingin menghapus SEMUA data mahasiswa?\n\n(Data Panitia/Mentor TIDAK akan dihapus)') && confirm('⛔ PERINGATAN TERAKHIR (TAHAP 2) ⛔\n\nTindakan ini TIDAK DAPAT DIBATALKAN!\nSemua data riwayat makan dan alergi mahasiswa juga akan dihapus permanen.\n\nApakah Anda benar-benar yakin ingin melanjutkan?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Reset Data (Hapus Semua)
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.mahasiswa.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari Nama, NIM, atau Kelompok..." value="{{ request('search') }}">
                    <button class="btn btn-secondary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Prodi</th>
                            <th>Kelompok</th>
                            <th>Vendor</th>
                            <th>Vegan</th>
                            <th>Alergi</th> <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswas as $mahasiswa)
                            <tr>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ $mahasiswa->nama }}</td>
                                <td>{{ $mahasiswa->prodi }}</td>
                                <td>{{ $mahasiswa->kelompok->nama ?? 'N/A' }}</td>
                                <td>
                                    @if ($mahasiswa->kelompok && $mahasiswa->kelompok->vendor)
                                        <span class="badge bg-info">{{ $mahasiswa->kelompok->vendor->nama_vendor }}</span>
                                    @else
                                        <span class="badge bg-secondary">Belum Diatur</span>
                                    @endif
                                <td>
                                    @if ($mahasiswa->is_vegan)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                
                                <td>
                                    @forelse ($mahasiswa->alergi as $alergi)
                                        <span class="badge bg-danger">{{ $alergi->nama }}</span>
                                    @empty
                                        -
                                    @endforelse
                                </td>

                                <td>
                                    <a href="{{ route('admin.mahasiswa.show', $mahasiswa) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $mahasiswas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>