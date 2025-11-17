<x-app-layout>
    <h1>Atur Vendor untuk: {{ $kelompok->nama }}</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.kelompok.update', $kelompok) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Kelompok</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $kelompok->nama }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="vendor_id" class="form-label">Set Vendor</label>
                    <select class="form-select" id="vendor_id" name="vendor_id">
                        <option value="">-- Belum Diatur / Kosongkan --</option>
                        
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" 
                                {{ $kelompok->vendor_id == $vendor->id ? 'selected' : '' }}>
                                {{ $vendor->nama_vendor }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.kelompok.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update Vendor</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>