<x-app-layout>
    <h1>Tambah Menu Makanan Baru</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.makanan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Vendor</label>
                    <select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id" name="vendor_id" required>
                        <option value="">Pilih Vendor...</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="nama_menu" class="form-label">Nama Menu</label>
                    <input type="text" class="form-control @error('nama_menu') is-invalid @enderror" id="nama_menu" name="nama_menu" value="{{ old('nama_menu') }}" required>
                    @error('nama_menu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="bahan" class="form-label">Bahan-bahan (pisahkan dengan koma)</label>
                    <textarea class="form-control @error('bahan') is-invalid @enderror" id="bahan" name="bahan" rows="3" placeholder="Cth: Ayam, Nasi, Kacang, Udang">{{ old('bahan') }}</textarea>
                    @error('bahan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_vegan" name="is_vegan" value="1" {{ old('is_vegan') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_vegan">
                            Menu Vegan?
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Gambar Makanan (Opsional)</label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.makanan.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>