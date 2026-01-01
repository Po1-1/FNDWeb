<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Vendor</h1>
        <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary">
            + Tambah Vendor
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Vendor</th>
                            <th>Kontak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vendors as $vendor)
                            <tr>
                                <td>{{ $vendors->firstItem() + $loop->index }}</td>
                                <td>{{ $vendor->nama_vendor }}</td>
                                <td>{{ $vendor->kontak ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.vendors.allocation.show', $vendor) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-grid-3x3-gap-fill me-1"></i> Alokasi
                                    </a>
                                    <a href="{{ route('admin.vendors.edit', $vendor) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.vendors.destroy', $vendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus vendor ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data vendor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
</x-app-layout>