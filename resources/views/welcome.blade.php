<x-guest-layout>
    <!-- Hero Section -->
    <div class="text-center py-5 mb-4 rounded-4 bg-white shadow-sm border"
        style="background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);">
        <div class="container py-4">
            <h1 class="display-4 fw-bold text-primary mb-3">Selamat Datang di FND</h1>
            <p class="lead text-muted mb-4 mx-auto" style="max-width: 600px;">
                Sistem Distribusi Makanan Event yang Cepat, Tepat, dan Aman.
                Cek status kelompok dan menu makanan Anda di sini.
            </p>

            <!-- Search Box yang Menonjol -->
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body p-2">
                            <form action="{{ route('guest.search') }}" method="GET">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-white border-0 text-muted ps-3">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control border-0 shadow-none" name="query"
                                        placeholder="Cari NIM, Nama, atau Kelompok..." value="{{ request('query') }}"
                                        style="font-size: 1rem;">
                                    <button class="btn btn-primary px-4 rounded-3 fw-bold" type="submit">Cari</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-info-circle me-1"></i> Contoh: "Kelompok 1", "Budi", atau "12345"
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features / Info Section (Opsional, pemanis) -->
    <div class="row g-4 py-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-transparent">
                <div class="card-body text-center">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-shield-check fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Aman untuk Alergi</h5>
                    <p class="text-muted small">Sistem kami memantau data alergi setiap peserta untuk mencegah kesalahan
                        distribusi.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-transparent">
                <div class="card-body text-center">
                    <div class="mb-3 text-success">
                        <i class="bi bi-leaf fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Ramah Vegan</h5>
                    <p class="text-muted small">Menu vegan tersedia dan ditandai khusus untuk memudahkan identifikasi.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 bg-transparent">
                <div class="card-body text-center">
                    <div class="mb-3 text-info">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                    <h5 class="fw-bold">Terorganisir</h5>
                    <p class="text-muted small">Setiap kelompok memiliki vendor spesifik untuk mengurangi antrian
                        panjang.</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
