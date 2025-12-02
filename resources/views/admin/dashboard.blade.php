<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1">Dashboard Admin</h1>
            <p class="text-muted mb-0">Ringkasan aktivitas dan data sistem.</p>
        </div>
        <div class="text-end">
            <span class="badge bg-white text-dark border shadow-sm p-2">
                <i class="bi bi-calendar-event me-1"></i> {{ now()->format('d M Y') }}
            </span>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Card Mahasiswa -->
        <div class="col-md-4">
            <div class="card h-100 hover-effect border-start border-4 border-primary">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Total Mahasiswa</h6>
                        <h2 class="mb-0 fw-bold text-dark">{{ $totalMahasiswa ?? 0 }}</h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="text-decoration-none small fw-bold text-primary">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Vendor -->
        <div class="col-md-4">
            <div class="card h-100 hover-effect border-start border-4 border-success">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Total Vendor</h6>
                        <h2 class="mb-0 fw-bold text-dark">{{ $totalVendor ?? 0 }}</h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="bi bi-shop fs-3"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.vendors.index') }}" class="text-decoration-none small fw-bold text-success">
                        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Card Sisa Galon -->
        <div class="col-md-4">
            <div class="card h-100 hover-effect border-start border-4 border-warning">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.75rem;">Sisa Galon (Terbaru)</h6>
                        <h2 class="mb-0 fw-bold text-dark">{{ $summary->sisa_galon ?? 0 }}</h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="bi bi-droplet-half fs-3"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.summaries.index') }}" class="text-decoration-none small fw-bold text-warning">
                        Lihat Rekap <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <h5 class="mt-5 mb-3 fw-bold text-dark">Aksi Cepat</h5>
    <div class="row g-3">
        <div class="col-md-3">
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-white w-100 p-3 shadow-sm text-start d-flex align-items-center hover-effect bg-white">
                <i class="bi bi-person-plus-fill text-primary fs-4 me-3"></i>
                <span class="fw-bold text-dark">Tambah Mahasiswa</span>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('admin.summary.generate.form') }}" class="btn btn-white w-100 p-3 shadow-sm text-start d-flex align-items-center hover-effect bg-white">
                <i class="bi bi-file-earmark-text-fill text-info fs-4 me-3"></i>
                <span class="fw-bold text-dark">Generate Laporan</span>
            </a>
        </div>
    </div>
</x-app-layout>