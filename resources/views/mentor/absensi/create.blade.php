<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Absensi Kelompok</h1>
        {{-- Tombol Kembali dihapus --}}
    </div>
    
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i>
        Data yang Anda isi di sini akan <strong>langsung terhubung ke Distributor</strong>.
    </div>

    {{-- Ubah teks kondisi --}}
    @if(!$assignedKelompokId)
        <form action="{{ route('mentor.absensi.create') }}" method="GET" class="mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <label class="form-label fw-bold">Pilih Kelompok Binaan Anda:</label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <select name="kelompok_id" class="form-select" onchange="this.form.submit()">
                                <option value="">-- Pilih Kelompok --</option>
                                @foreach($kelompoks as $k)
                                    <option value="{{ $k->id }}" {{ request('kelompok_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif

    @if($selectedKelompok)
        <form action="{{ route('mentor.absensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kelompok_id" value="{{ $selectedKelompok->id }}">
            <input type="hidden" name="hari_ke" value="{{ $hariKe }}">
            <input type="hidden" name="waktu_makan" value="{{ $waktuMakan }}">

            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold d-block fs-5">{{ $selectedKelompok->nama }}</span>
                        <span class="small opacity-75">
                            <i class="bi bi-calendar-event me-1"></i>
                            Hari ke-{{ $hariKe }} ({{ ucfirst($waktuMakan) }})
                        </span>
                    </div>
                    <span class="badge bg-white text-primary">{{ $selectedKelompok->mahasiswas->count() }} Anggota</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 100px;">Hadir?</th>
                                    <th>Nama Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($selectedKelompok->mahasiswas as $mhs)
                                    <tr>
                                        <td class="text-center">
                                            {{-- Default Checked = Hadir --}}
                                            <input type="checkbox" name="kehadiran[{{ $mhs->id }}]" value="1" checked 
                                                class="form-check-input" style="transform: scale(1.3); cursor: pointer;">
                                        </td>
                                        <td class="fw-bold">{{ $mhs->nama }}</td>
                                        <td>{{ $mhs->nim }}</td>
                                        <td>
                                            @if($mhs->is_vegan)
                                                <span class="badge bg-success">Vegan</span>
                                            @endif
                                            @if($mhs->alergi->isNotEmpty())
                                                <span class="badge bg-danger">Alergi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light p-3 text-end">
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                        <i class="bi bi-save me-2"></i> Simpan Absensi
                    </button>
                </div>
            </div>
        </form>
    @else
        {{-- Jika assignedKelompokId ada tapi selectedKelompok null (kasus jarang, error handling) --}}
        @if($assignedKelompokId)
            <div class="alert alert-warning">Data kelompok Anda tidak ditemukan.</div>
        @endif
    @endif
</x-app-layout>
