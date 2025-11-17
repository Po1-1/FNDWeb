<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Makanan</h1>
        <a href="{{ route('admin.makanan.create') }}" class="btn btn-primary">
            + Tambah Menu
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Menu</th>
                            <th>Vendor</th>
                            <th>Bahan</th>
                            <th>Vegan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($makanans as $makanan)
                            <tr>
                                <td>{{ $makanan->nama_menu }}</td>
                                <td>{{ $makanan->vendor->nama_vendor ?? 'N/A' }}</td>
                                <td>{{ Str::limit($makanan->bahan, 50) }}</td>
                                <td>{{ $makanan->is_vegan ? 'Ya' : 'Tidak' }}</td>
                                <td>
                                    <a href="{{ route('admin.makanan.edit', $makanan) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.makanan.destroy', $makanan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data makanan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $makanans->links() }}
            </div>
        </div>
    </div>
</x-app-layout>