<x-app-layout>
    <div class="row g-4">
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fs-5">Catat Pengambilan Makanan</div>
                <div class="card-body">
                    <form action="{{ route('kasir.distribusi.makanan.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="kelompok_id" class="form-label">Kelompok</label>
                            <select class="form-select @error('kelompok_id') is-invalid @enderror" 
                                    id="kelompok_id" name="kelompok_id" required>
                                <option value="">Pilih Kelompok...</option>
                                @foreach ($kelompoks as $kelompok)
                                    <option value="{{ $kelompok->id }}">{{ $kelompok->nama }}</option>
                                @endforeach
                            </select>
                            @error('kelompok_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_pengambilan" class="form-label">Jumlah Pengambilan</label>
                            <input type="number" 
                                   class="form-control @error('jumlah_pengambilan') is-invalid @enderror" 
                                   id="jumlah_pengambilan" 
                                   name="jumlah_pengambilan" 
                                   placeholder="Cth: 48" 
                                   required 
                                   min="0">
                            @error('jumlah_pengambilan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="catatan" 
                                      name="catatan" 
                                      rows="2" 
                                      placeholder="Cth: 2 mahasiswa tidak hadir, diambil oleh ketua kelompok.">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Catat Makanan</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header fs-5">Catat Pengambilan Logistik</div>
                <div class="card-body">
                    <form action="{{ route('kasir.distribusi.logistik.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="inventaris_logistik_id" class="form-label">Item Logistik</label>
                            <select class="form-select @error('inventaris_logistik_id') is-invalid @enderror" 
                                    id="inventaris_logistik_id" name="inventaris_logistik_id" required>
                                <option value="">Pilih Item...</option>
                                @foreach ($logistiks as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_item }}</option>
                                @endforeach
                            </select>
                            @error('inventaris_logistik_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="jumlah_digunakan" class="form-label">Jumlah</label>
                            <input type="number" 
                                   class="form-control @error('jumlah_digunakan') is-invalid @enderror" 
                                   id="jumlah_digunakan" 
                                   name="jumlah_digunakan" 
                                   min="1" 
                                   required>
                            @error('jumlah_digunakan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logistik_catatan" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('catatan') is-invalid @enderror" 
                                      id="logistik_catatan" 
                                      name="catatan" 
                                      rows="2" 
                                      placeholder="Cth: 2 galon pecah, 1 roll plastik sobek.">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-info w-100">Catat Logistik</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>