<x-guest-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark mb-0">
            <i class="bi bi-search me-2 text-muted"></i>Hasil Pencarian: "{{ $query }}"

        </h4>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            @if ($results->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3 text-muted opacity-50">
                        <i class="bi bi-emoji-frown fs-1"></i>
                    </div>
                    <h5 class="fw-bold text-muted">Data tidak ditemukan</h5>
                    <p class="text-muted small">Coba kata kunci lain seperti NIM atau Nama Kelompok.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">Mahasiswa</th>
                                <th class="py-3">Kelompok</th>
                                <th class="py-3">Vendor</th>
                                <th class="py-3">Status Diet</th>
                                <th class="py-3">Alergi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $mahasiswa)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $mahasiswa->nama }}</div>
                                        <div class="small text-muted">{{ $mahasiswa->nim }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            {{ $mahasiswa->kelompok->nama ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-1 me-2 d-flex justify-content-center align-items-center"
                                                style="width: 24px; height: 24px;">
                                                <i class="bi bi-shop" style="font-size: 12px;"></i>
                                            </div>
                                            <span class="fw-medium">
                                                {{ $mahasiswa->kelompok->vendor->nama_vendor ?? 'Belum Diatur' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($mahasiswa->is_vegan)
                                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">
                                                <i class="bi bi-leaf me-1"></i> Vegan
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse ($mahasiswa->alergi as $alergi)
                                            <span
                                                class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill">
                                                {{ $alergi->nama }}
                                            </span>
                                        @empty
                                            <span class="text-muted small">-</span>
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($results->hasPages())
                    <div class="p-3 border-top bg-light">
                        {{ $results->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-guest-layout>