<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Anggota Kelompok: {{ $kelompok->nama }}</h2>
            <p class="text-muted mb-0">Berikut adalah daftar anggota kelompok binaan Anda.</p>
        </div>
        <a href="{{ route('mentor.kelompok.manage') }}" class="btn btn-primary">
            <i class="bi bi-camera-fill me-1"></i> Kelola Bukti Distribusi
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-light fw-bold">
            Daftar Anggota
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Nama Mahasiswa</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Status Diet & Alergi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelompok->mahasiswas as $mahasiswa)
                            <tr>
                                <td class="ps-4 fw-bold">{{ $mahasiswa->nama }}</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ $mahasiswa->prodi }}</td>
                                <td>
                                    @if ($mahasiswa->is_vegan)
                                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill">Vegan</span>
                                    @endif
                                    @foreach ($mahasiswa->alergi as $alergi)
                                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill">{{ $alergi->nama }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted p-4">
                                    Tidak ada anggota di kelompok ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>