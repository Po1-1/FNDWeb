<x-app-layout>
    <h1>Buat Kelompok Baru</h1>
    <p>Gunakan formulir ini untuk membuat data kelompok baru secara manual.</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.kelompok.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Kelompok</label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                        name="nama" value="{{ old('nama') }}" placeholder="Cth: Kelompok 10" required>
                    @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Set Vendor (Opsional)</label>
                    <select class="form-select @error('vendor_id') is-invalid @enderror" id="vendor_id"
                        name="vendor_id">

                        <option value="">-- Belum Diatur --</option>

                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.kelompok.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
