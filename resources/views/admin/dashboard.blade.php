<x-app-layout>
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark mb-2">Selamat datang, {{ Auth::user()->name }}.</h1>
            <p class="text-muted mb-0">Ringkasan cepat operasional hari ini.</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-block bg-white px-4 py-2 rounded-pill shadow-sm border">
                <i class="bi bi-calendar-check text-primary me-2"></i>
                <span class="fw-bold text-dark">{{ $activeEvent ? $activeEvent->tanggal_mulai->translatedFormat('l, d F Y') : now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-primary bg-gradient text-white shadow-lg position-relative overflow-hidden">
                <div class="position-absolute top-0 end-0 opacity-25 translate-middle-y me-n3 mt-n3">
                    <i class="bi bi-people-fill" style="font-size: 8rem;"></i>
                </div>
                
                <div class="card-body position-relative z-1 p-4">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-3">Total Peserta</h6>
                    <div class="d-flex align-items-baseline">
                        <h2 class="display-4 fw-bold mb-0">{{ $totalMahasiswa ?? 0 }}</h2>
                        <span class="ms-2 fs-5 text-white-50">Mahasiswa</span>
                    </div>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light text-primary mt-4 px-4 border-0">
                        Kelola Data <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Vendor & Logistik (Grid Kecil) -->
        <div class="col-md-8">
            <div class="row g-4 h-100">
                <div class="col-md-6">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="text-muted text-uppercase fw-bold">Mitra Vendor</h6>
                                    <h3 class="fw-bold text-dark">{{ $totalVendor ?? 0 }} <small class="text-muted fs-6">Dapur</small></h3>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-4 text-success">
                                    <i class="bi bi-shop fs-3"></i>
                                </div>
                            </div>
                            <p class="text-muted small mb-0">Menyediakan makanan untuk berbagai kelompok.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4 d-flex flex-column justify-content-between">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="text-muted text-uppercase fw-bold">Stok Galon</h6>
                                    <h3 class="fw-bold {{ ($summary->sisa_galon ?? 0) < 5 ? 'text-danger' : 'text-dark' }}">
                                        {{ $summary->sisa_galon ?? 0 }} <small class="text-muted fs-6">Unit</small>
                                    </h3>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded-4 text-warning">
                                    <i class="bi bi-droplet-half fs-3"></i>
                                </div>
                            </div>
                            @if(($summary->sisa_galon ?? 0) < 5)
                                <div class="alert alert-danger py-1 px-2 small mb-0 rounded-3">
                                    <i class="bi bi-exclamation-circle me-1"></i> Stok menipis!
                                </div>
                            @else
                                <p class="text-muted small mb-0">Stok air minum aman terkendali.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Aksi Cepat (Quick Actions) -->
    <h5 class="fw-bold text-dark mb-3 ps-1">Mau melakukan apa sekarang?</h5>
    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('admin.mahasiswa.create') }}" class="card text-decoration-none hover-effect h-100">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3 text-primary">
                        <i class="bi bi-person-plus-fill fs-5"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-dark">Tambah Mahasiswa</span>
                        <small class="text-muted">Input manual peserta</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.summary.generate.form') }}" class="card text-decoration-none hover-effect h-100">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-info bg-opacity-10 p-2 rounded-circle me-3 text-info">
                        <i class="bi bi-file-earmark-text-fill fs-5"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-dark">Buat Laporan</span>
                        <small class="text-muted">Rekap harian otomatis</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.kelompok.index') }}" class="card text-decoration-none hover-effect h-100">
                <div class="card-body d-flex align-items-center p-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3 text-success">
                        <i class="bi bi-people-fill fs-5"></i>
                    </div>
                    <div>
                        <span class="d-block fw-bold text-dark">Atur Kelompok</span>
                        <small class="text-muted">Mapping vendor</small>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>