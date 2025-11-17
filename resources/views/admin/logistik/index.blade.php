<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Inventaris Logistik</h1>
        <a href="{{ route('admin.logistik.create') }}" class="btn btn-primary">
            + Tambah Item Logistik
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Item</th>
                            <th>Stok Awal</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logistiks as $logistik)
                            <tr>
                                <td>{{ $logistik->id }}</td>
                                <td>{{ $logistik->nama_item }}</td>
                                <td>{{ $logistik->stok_awal }}</td>
                                <td>{{ $logistik->satuan }}</td>
                                <td>
                                    <a href="{{ route('admin.logistik.edit', $logistik) }}" class="btn btn-sm btn-warning">Edit</a>
                                    
                                    <form action="{{ route('admin.logistik.destroy', $logistik) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                    
                                    <a href="{{ route('admin.logistik.show', $logistik) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data logistik.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $logistiks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>