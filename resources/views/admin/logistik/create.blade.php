<x-app-layout>
    <h1>Tambah Item Logistik Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.logistik.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama_item" class="form-label">Nama Item</label>
                    <input type="text" class="form-control @error('nama_item') is-invalid @enderror" id="nama_item"
                        name="nama_item" value="{{ old('nama_item') }}" placeholder="Cth: Galon Air, Plastik Sampah"
                        required>
                    @error('nama_item')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="stok_awal" class="form-label">Stok Awal</label>
                    <input type="number" class="form-control @error('stok_awal') is-invalid @enderror" id="stok_awal"
                        name="stok_awal" value="{{ old('stok_awal', 0) }}" required>
                    @error('stok_awal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control @error('satuan') is-invalid @enderror" id="satuan"
                        name="satuan" value="{{ old('satuan') }}" placeholder="Cth: pcs, galon, roll" required>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.logistik.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
