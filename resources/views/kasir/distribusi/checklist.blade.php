<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">{{ $kelompok->nama }}</h2>
            <p class="text-muted mb-0">Hari ke-{{ $hariKe }} &bull; Makan {{ ucfirst($waktuMakan) }}</p>
        </div>
        <a href="{{ route('kasir.dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <div class="alert alert-info d-flex align-items-center">
        <i class="bi bi-info-circle me-2"></i>
        <div>
            <strong>Vendor Default Kelompok Hari Ini:</strong> 
            {{ $vendorKelompok->nama_vendor ?? 'BELUM DIATUR (Cek Jadwal)' }}
        </div>
    </div>

    <form action="{{ route('kasir.distribusi.storeChecklist') }}" method="POST">
        @csrf
        <input type="hidden" name="kelompok_id" value="{{ $kelompok->id }}">
        <input type="hidden" name="hari_ke" value="{{ $hariKe }}">
        <input type="hidden" name="waktu_makan" value="{{ $waktuMakan }}">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span class="fw-bold">Daftar Anggota</span>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="checkAll" checked>
                    <label class="form-check-label" for="checkAll">Pilih Semua</label>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">Hadir</th>
                                <th>Nama Mahasiswa</th>
                                <th>Info Khusus</th>
                                <th>Vendor (Otomatis)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelompok->mahasiswas as $mhs)
                            @php
                                // ambil vendor anak ini untuk hari/waktu ini
                                $vendorAnak = $mhs->getVendorFor($hariKe, $waktuMakan);
                                $isCustom = $mhs->custom_vendor_id ? true : false;
                            @endphp
                            <tr class="{{ $isCustom ? 'table-warning' : '' }}">
                                <td class="text-center">
                                    <input type="checkbox" name="hadir[]" value="{{ $mhs->id }}" class="form-check-input chk-mhs" checked style="transform: scale(1.3);">
                                </td>
                                <td class="fw-bold">{{ $mhs->nama }}</td>
                                <td>
                                    @if($mhs->is_vegan) <span class="badge bg-success">Vegan</span> @endif
                                    @foreach($mhs->alergi as $a) <span class="badge bg-danger">{{ $a->nama }}</span> @endforeach
                                </td>
                                <td>
                                    <span class="badge {{ $isCustom ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                        {{ $vendorAnak->nama_vendor ?? 'TIDAK ADA DATA' }}
                                    </span>
                                    @if($isCustom) <small class="text-muted ms-1">(Khusus)</small> @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Catatan Tambahan (Opsional)</label>
            <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: 2 anak sakit tidak makan, 1 porsi tumpah."></textarea>
        </div>

        <button type="submit" class="btn btn-success w-100 btn-lg py-3 shadow">
            <i class="bi bi-save"></i> Simpan Data Distribusi
        </button>
    </form>

    <script>
        //  untuk Check All / Uncheck All
        document.getElementById('checkAll').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('.chk-mhs');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>
</x-app-layout>