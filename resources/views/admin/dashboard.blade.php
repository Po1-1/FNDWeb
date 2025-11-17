<x-app-layout>
    <h1>Dashboard Admin</h1>
    <p class="fs-5 text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
    
    <div class="row g-4 mt-3">
        <div class="col-md-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <div class="fs-3">{{ $totalMahasiswa ?? 0 }}</div>
                    <div class="fs-5">Total Mahasiswa</div>
                </div>
                <a href="{{ route('admin.mahasiswa.index') }}" class="card-footer text-white text-decoration-none">
                    Lihat Detail
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <div class="fs-3">{{ $totalVendor ?? 0 }}</div>
                    <div class="fs-5">Total Vendor</div>
                </div>
                <a href="{{ route('admin.vendors.index') }}" class="card-footer text-white text-decoration-none">
                    Lihat Detail
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-dark bg-warning shadow-sm">
                <div class="card-body">
                    <div class="fs-3">{{ $summary->sisa_galon ?? 0 }}</div>
                    <div class="fs-5">Sisa Galon (Terbaru)</div>
                </div>
                <a href="{{ route('admin.summaries.index') }}" class="card-footer text-dark text-decoration-none">
                    Lihat Rekap
                </a>
            </div>
        </div>
    </div>

    </x-app-layout>