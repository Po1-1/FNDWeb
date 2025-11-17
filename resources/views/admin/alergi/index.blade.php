<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Alergi</h1>
        <a href="{{ route('admin.alergi.create') }}" class="btn btn-primary">
            + Tambah Alergi
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Alergi</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($alergis as $alergi)
                            <tr>
                                <td>{{ $alergi->nama }}</td>
                                <td>{{ $alergi->deskripsi }}</td>
                                <td>
                                    <a href="{{ route('admin.alergi.edit', $alergi) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('admin.alergi.destroy', $alergi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus? Ini bisa mempengaruhi data mahasiswa.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data alergi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $alergis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>