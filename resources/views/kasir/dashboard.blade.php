<x-app-layout>
    <h1 class="mb-4">Dashboard Kasir</h1>
    
    <div class="row g-4">
        <div class="col-12">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white fs-5">
                    <i class="bi bi-check2-square"></i> Catat Pembagian Makanan
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">Pilih detail sesi makan untuk memuat daftar checklist mahasiswa.</p>
                    
                    <form action="{{ route('kasir.distribusi.checklist') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Hari Ke-</label>
                                <input type="number" name="hari_ke" class="form-control" value="1" min="1" required>
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Waktu Makan</label>
                                <select name="waktu_makan" class="form-select">
                                    <option value="pagi">Pagi</option>
                                    <option value="siang">Siang</option>
                                    <option value="sore">Sore</option>
                                    <option value="malam">Malam</option>
                                </select>
                            </div>
                            
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Pilih Kelompok</label>
                                <select name="kelompok_id" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($kelompoks as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Buka Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header fs-5 bg-light">Catat Pengambilan Logistik</div>
                <div class="card-body">
                    <form action="{{ route('kasir.distribusi.logistik.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Item Logistik</label>
                                <select class="form-select" name="inventaris_logistik_id" required>
                                    @foreach ($logistiks as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Jumlah</label>
                                <input type="number" class="form-control" name="jumlah_digunakan" min="1" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Catatan (Opsional)</label>
                                <input type="text" class="form-control" name="catatan" placeholder="Cth: 1 pecah">
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button type="submit" class="btn btn-info w-100 text-white">Catat</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>