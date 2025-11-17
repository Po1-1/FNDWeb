<x-app-layout>
    <h1>Edit Vendor: {{ $vendor->nama_vendor }}</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.vendors.update', $vendor) }}" method="POST">
                @csrf
                @method('PUT') <div class="mb-3">
                    <label for="nama_vendor" class="form-label">Nama Vendor</label>
                    <input type="text" class="form-control @error('nama_vendor') is-invalid @enderror" 
                           id="nama_vendor" name="nama_vendor" 
                           value="{{ old('nama_vendor', $vendor->nama_vendor) }}" required>
                    @error('nama_vendor')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kontak" class="form-label">Kontak (No. HP/Email)</label>
                    <input type="text" class="form-control @error('kontak') is-invalid @enderror" 
                           id="kontak" name="kontak" 
                           value="{{ old('kontak', $vendor->kontak) }}">
                    @error('kontak')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>