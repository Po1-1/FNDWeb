<x-guest-layout>
    <!-- Hero Section -->
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">
                        ✨ Sistem Distribusi FND 2025
                    </span>
                    <h1 class="display-4 fw-bold text-dark mb-3">Cek Makananmu,<br>Tanpa Ribet.</h1>
                    <p class="lead text-muted mb-5 mx-auto" style="max-width: 600px;">
                        Tidak perlu bingung lagi. Masukkan NIM atau nama kelompokmu untuk melihat vendor katering dan status dietmu hari ini.
                    </p>
                </div>

                <!-- Search Box yang "Floating" -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5" style="transform: scale(1.02);">
                    <div class="card-body p-2">
                        <form action="{{ route('guest.search') }}" method="GET">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-0 ps-4 text-muted">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control border-0 shadow-none ps-3" name="query"
                                    placeholder="Ketik NIM, Nama, atau Kelompok..." value="{{ request('query') }}"
                                    style="font-size: 1.1rem;">
                                <button class="btn btn-primary px-5 rounded-4 m-1 fw-bold" type="submit">Cari</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fitur Unggulan (Human Friendly) -->
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
