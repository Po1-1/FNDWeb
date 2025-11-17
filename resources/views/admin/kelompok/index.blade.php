<x-app-layout>
     <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Kelompok & Vendor</h1>
        <a href="{{ route('admin.kelompok.create') }}" class="btn btn-primary">
            + Buat Kelompok Baru
        </a>
    </div>
    <div classKA="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Kelompok</th>
                        <th>Vendor Saat Ini</th>
                        <th>Jumlah Anggota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelompoks as $kelompok)
                        <tr>
                            <td>{{ $kelompok->nama }}</td>
                            <td>
                                @if ($kelompok->vendor)
                                    <span class="badge bg-info">{{ $kelompok->vendor->nama_vendor }}</span>
                                @else
                                    <span class="badge bg-secondary">Belum Diatur</span>
                                @endif
                            </td>
                            <td>{{ $kelompok->mahasiswas_count }}</td>
                            <td>
                                <a href="{{ route('admin.kelompok.edit', $kelompok) }}"
                                    class="btn btn-sm btn-warning">Atur Vendor</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
