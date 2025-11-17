<x-app-layout>
    <h1>Edit Item: {{ $logistik->nama_item }}</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.logistik.update', $logistik) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-3">
                    <label for="nama_item" class="form-label">Nama Item</label>
                    <input type="text" class="form-control @error('nama_item') is-invalid @enderror" 
                           id="nama_item" name="nama_item" 
                           value="{{ old('nama_item', $logistik->nama_item) }}" required>
                    @error('nama_item')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="stok_awal" class="form-label">Stok Awal</label>
                    <input type="number" class="form-control @error('stok_awal') is-invalid @enderror" 
                           id="stok_awal" name="stok_awal" 
                           value="{{ old('stok_awal', $logistik->stok_awal) }}" required>
                    @error('stok_awal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="satuan" class="form-label">Satuan</label>
                    <input type="text" class="form-control @error('satuan') is-invalid @enderror" 
                           id="satuan" name="satuan" 
                           value="{{ old('satuan', $logistik->satuan) }}" required>
                    @error('satuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.logistik.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>