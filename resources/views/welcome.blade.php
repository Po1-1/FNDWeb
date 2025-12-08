<x-guest-layout>
    <!-- Hero Section -->
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold text-dark mb-3">Sistem Distribusi FND</h1>
                <p class="lead text-muted mb-4">
                    Platform terpusat untuk distribusi konsumsi, pemetaan vendor, dan pemantauan diet peserta.
                </p>
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5 bg-white">
                    <div class="card-body p-5">
                        <h5 class="fw-bold mb-3">Akses Terbatas</h5>
                        <p class="text-muted mb-4">
                            Detail mahasiswa dan kelompok hanya untuk pengguna terdaftar. Silakan login.
                        </p>
                        @guest
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary px-5">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary px-4">Register</a>
                            </div>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-primary px-5">Ke Dashboard</a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>

        <!-- Fitur Unggulan (Tetap ditampilkan sebagai info) -->
        <div class="row g-4 py-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-white shadow-sm p-3 text-center hover-effect">
                    <div class="card-body">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-shield-check fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Aman untuk Alergi</h5>
                        <p class="text-muted">Kami menjaga datamu. Jika kamu punya alergi, sistem akan memberi tanda agar kamu tetap aman.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-white shadow-sm p-3 text-center hover-effect">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-leaf fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Ramah Vegan</h5>
                        <p class="text-muted">Menu nabati tersedia dan terdata dengan jelas. Tidak perlu khawatir salah ambil menu.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 bg-white shadow-sm p-3 text-center hover-effect">
                    <div class="card-body">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-inline-flex p-3 mb-3">
                            <i class="bi bi-layout-text-sidebar-reverse fs-2"></i>
                        </div>
                        <h5 class="fw-bold">Terorganisir Rapi</h5>
                        <p class="text-muted">Setiap kelompok punya vendor khusus. Antrian lebih cepat, perut lebih cepat kenyang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
