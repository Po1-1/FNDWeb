<x-app-layout>
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark mb-1">Dashboard Mentor</h1>
            <p class="text-muted mb-0">Selamat datang, {{ $user->name }}!</p>
        </div>
    </div>

    {{-- BAGIAN BARU: KELOMPOK SAYA --}}
    <div class="card shadow-sm border-primary mb-5">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Kelompok Binaan Saya</h5>
        </div>
        <div class="card-body p-4">
            @if ($mentorData && $mentorData->kelompok)
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-3 mb-md-0">
                        <h4 class="fw-bold text-dark">{{ $mentorData->kelompok->nama }}</h4>
                        <p class="text-muted mb-0">Gunakan tombol di samping untuk melihat anggota atau mengelola bukti distribusi.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('mentor.kelompok.show') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-person-lines-fill me-1"></i> Lihat Anggota
                        </a>
                        <a href="{{ route('mentor.kelompok.manage') }}" class="btn btn-primary">
                            <i class="bi bi-camera-fill me-1"></i> Kelola Bukti
                        </a>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    Akun Anda belum terhubung dengan kelompok manapun. Silakan hubungi Admin.
                </div>
            @endif
        </div>
    </div>


    {{-- BAGIAN LAMA: PENCARIAN MAHASISWA --}}
    <h4 class="fw-bold text-dark mb-3">Pencarian Global</h4>
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <form action="{{ route('mentor.dashboard') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control border-end-0 bg-light" name="query"
                        placeholder="Filter berdasarkan NIM, Nama, atau Kelompok..." value="{{ $query ?? '' }}">
                    <button class="btn btn-primary px-5" type="submit">
                        <i class="bi bi-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- HASIL PENCARIAN (SEKARANG MENJADI DAFTAR UTAMA) -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            @if ($results->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3 text-muted opacity-50"><i class="bi bi-emoji-frown fs-1"></i></div>
                    <h5 class="fw-bold text-muted">Data tidak ditemukan</h5>
                    @if ($query)
                        <p class="text-muted small">Tidak ada hasil untuk pencarian "{{ $query }}".</p>
                    @else
                        <p class="text-muted small">Belum ada data mahasiswa di dalam sistem.</p>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Mahasiswa</th>
                                <th class="py-3">Kelompok</th>
                                <th class="py-3">Vendor</th>
                                <th class="py-3">Status Diet & Alergi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $mahasiswa)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $mahasiswa->nama }}</div>
                                        <div class="small text-muted">{{ $mahasiswa->nim }}</div>
                                    </td>
                                    <td><span
                                            class="badge bg-light text-dark border">{{ $mahasiswa->kelompok->nama ?? 'N/A' }}</span>
                                    </td>
                                    <td>{{ $mahasiswa->kelompok->vendor->nama_vendor ?? 'Belum Diatur' }}</td>
                                    <td>
                                        @if ($mahasiswa->is_vegan)
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success rounded-pill">Vegan</span>
                                        @endif
                                        @foreach ($mahasiswa->alergi as $alergi)
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger rounded-pill">{{ $alergi->nama }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if ($results->hasPages())
                    <div class="p-3 border-top bg-light">{{ $results->links() }}</div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
