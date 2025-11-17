<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Detail Mahasiswa</h1>
            <span class="text-muted">{{ $mahasiswa->nama }}</span>
        </div>
        <div>
            <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}" class="btn btn-warning me-2">
                Edit Mahasiswa
            </a>
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
                Kembali ke Daftar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Akademik & Event</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>NIM</strong>
                            <span>{{ $mahasiswa->nim }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Nama Lengkap</strong>
                            <span>{{ $mahasiswa->nama }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Program Studi</strong>
                            <span>{{ $mahasiswa->prodi }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>No. Urut</strong>
                            <span>{{ $mahasiswa->no_urut }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Kelompok</strong>
                            <span>{{ $mahasiswa->kelompok->nama ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Vendor Ditugaskan</strong>
                            @if ($mahasiswa->kelompok && $mahasiswa->kelompok->vendor)
                                <span class="badge bg-info">{{ $mahasiswa->kelompok->vendor->nama_vendor }}</span>
                            @else
                                <span class="badge bg-secondary">Belum Diatur</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Informasi Kesehatan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Status Vegan:</strong>
                        @if ($mahasiswa->is_vegan)
                            <span class="badge bg-success fs-6">Ya, Vegan</span>
                        @else
                            <span class="badge bg-secondary fs-6">Tidak</span>
                        @endif
                    </div>
                    
                    <hr>

                    <div>
                        <strong>Daftar Alergi:</strong>
                        @if ($mahasiswa->alergi->isEmpty())
                            <p class="text-muted mb-0 mt-1">Tidak ada data alergi.</p>
                        @else
                            <ul class="list-group mt-2">
                                @foreach ($mahasiswa->alergi as $alergi)
                                    <li class="list-group-item list-group-item-danger">
                                        {{ $alergi->nama }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>