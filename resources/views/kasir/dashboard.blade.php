<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Ubah Judul --}}
        <h1 class="mb-0">Dashboard Distributor</h1>
        <div class="text-end">
            <span class="badge bg-primary fs-6">{{ $activeEvent->nama_event }}</span>
            <div class="text-muted small mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }}</div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Form Pencatatan -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-primary mb-4">
                <div class="card-header bg-primary text-white fs-5">
                    <i class="bi bi-check2-square"></i> Catat Pembagian Makanan
                </div>
                <div class="card-body">
                    <p class="card-text text-muted">Pilih detail sesi makan untuk memuat daftar checklist mahasiswa.</p>
                    
                    {{-- Tambahkan ID pada form agar mudah diakses JS --}}
                    <form id="form-cek-status" action="{{ route('kasir.dashboard') }}" method="GET">
                        {{-- Kita gunakan route dashboard (diri sendiri) untuk refresh status, 
                             nanti tombol submit baru diarahkan ke checklist --}}
                    </form>

                    {{-- Form Asli untuk Submit ke Checklist --}}
                    <form action="{{ route('kasir.distribusi.checklist') }}" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-2">
                                <label class="form-label fw-bold">Hari Ke-</label>
                                {{-- Tambahkan onchange --}}
                                <input type="number" name="hari_ke" id="input_hari_ke" class="form-control" 
                                       value="{{ $hariKe }}" min="1" required onchange="refreshStatus()">
                            </div>
                            
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Waktu Makan</label>
                                {{-- Tambahkan onchange --}}
                                <select name="waktu_makan" id="input_waktu_makan" class="form-select" onchange="refreshStatus()">
                                    <option value="pagi" {{ $waktuMakan == 'pagi' ? 'selected' : '' }}>Pagi</option>
                                    <option value="siang" {{ $waktuMakan == 'siang' ? 'selected' : '' }}>Siang</option>
                                    <option value="sore" {{ $waktuMakan == 'sore' ? 'selected' : '' }}>Sore</option>
                                    <option value="malam" {{ $waktuMakan == 'malam' ? 'selected' : '' }}>Malam</option>
                                </select>
                            </div>
                            
                            <div class="col-md-5">
                                <label class="form-label fw-bold">Pilih Kelompok</label>
                                <select name="kelompok_id" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    @foreach($kelompoks as $k)
                                        @php
                                            // Cek apakah kelompok ini ada di list $sudahDiambil
                                            $isDone = $sudahDiambil->contains('kelompok_id', $k->id);
                                        @endphp
                                        {{-- LOGIKA TAMPILAN: Hanya munculkan teks jika sudah ambil --}}
                                        <option value="{{ $k->id }}" class="{{ $isDone ? 'text-success fw-bold' : '' }}">
                                            {{ $k->nama }} {{ $isDone ? '(Sudah Ambil)' : '' }}
                                        </option>
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

            <!-- TABEL LAPORAN YANG SUDAH DIBAGIKAN (FITUR BARU) -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-2"></i> Riwayat Sesi Ini (Hari {{ $hariKe }} - {{ ucfirst($waktuMakan) }})</span>
                    <span class="badge bg-white text-success">{{ $sudahDiambil->count() }} Kelompok</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th class="ps-3">Kelompok</th>
                                    <th>Jam Ambil</th>
                                    <th>Jumlah</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sudahDiambil as $data)
                                    <tr>
                                        <td class="ps-3 fw-bold">{{ $data->kelompok->nama ?? 'N/A' }}</td>
                                        <td>{{ $data->created_at->format('H:i') }}</td>
                                        <td><span class="badge bg-secondary rounded-pill">{{ $data->jumlah_pengambilan }} Porsi</span></td>
                                        <td class="small text-muted">{{ Str::limit($data->catatan, 30) ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Belum ada kelompok yang mengambil makanan pada sesi ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan (Logistik) -->
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header fs-5 bg-light">Catat Pengambilan Logistik</div>
                <div class="card-body">
                    <form action="{{ route('kasir.distribusi.logistik.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Item Logistik</label>
                            <select class="form-select" name="inventaris_logistik_id" required>
                                @foreach ($logistiks as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_item }} (Sisa: {{ $item->stok_awal }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" class="form-control" name="jumlah_digunakan" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <input type="text" class="form-control" name="catatan" placeholder="Cth: 1 pecah">
                        </div>
                        <button type="submit" class="btn btn-info w-100 text-white">Catat Logistik</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tambahkan Script di bawah --}}
    <script>
        function refreshStatus() {
            // Ambil nilai hari dan waktu
            let hari = document.getElementById('input_hari_ke').value;
            let waktu = document.getElementById('input_waktu_makan').value;
            
            // Reload halaman dashboard dengan parameter baru agar status "Sudah Ambil" terupdate
            window.location.href = "{{ route('kasir.dashboard') }}?hari_ke=" + hari + "&waktu_makan=" + waktu;
        }
    </script>
</x-app-layout>