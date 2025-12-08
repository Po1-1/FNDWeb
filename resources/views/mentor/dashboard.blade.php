<x-app-layout>
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h3 fw-bold text-dark mb-1">Dashboard Mentor</h1>
            <p class="text-muted mb-0">Selamat datang, {{ $user->name }}!</p>
        </div>
    </div>

    <!-- KOLOM PENCARIAN BARU UNTUK MENTOR -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
        <div class="card-body p-4">
            <label class="form-label fw-bold text-primary mb-2">
                <i class="bi bi-search me-1"></i> Cari Mahasiswa / Kelompok Lain
            </label>
            <form action="{{ route('mentor.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control border-end-0 bg-light" name="query"
                        placeholder="Masukkan NIM, Nama, atau Nama Kelompok..." required>
                    <button class="btn btn-primary px-4" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Card Info Mentor -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-effect">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Profil Anda</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent px-0"><strong>Nama:</strong> {{ $mentorData->nama }}
                        </li>
                        <li class="list-group-item bg-transparent px-0"><strong>Prodi:</strong> {{ $mentorData->prodi }}
                        </li>
                        <li class="list-group-item bg-transparent px-0"><strong>Kelompok Binaan:</strong> <span
                                class="badge bg-primary">{{ $mentorData->kelompok->nama ?? 'N/A' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Card Shortcut -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm hover-effect bg-primary bg-opacity-10">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-5">
                    <div class="bg-white p-3 rounded-circle text-primary shadow-sm mb-3">
                        <i class="bi bi-people-fill fs-1"></i>
                    </div>
                    <h4 class="fw-bold">Kelompok Saya</h4>
                    <p class="text-muted small mb-4">Lihat detail anggota, status alergi, dan upload bukti distribusi.
                    </p>
                    <a href="{{ route('mentor.kelompok.show') }}" class="btn btn-primary rounded-pill px-4">
                        Lihat Anggota Kelompok
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
