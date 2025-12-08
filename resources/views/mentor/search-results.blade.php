<x-app-layout>
    <div class="container py-4">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-dark mb-0">
                Hasil Pencarian: "{{ $query }}"
            </h4>
            <a href="{{ route('mentor.dashboard') }}" class="btn btn-secondary btn-sm rounded-pill px-3">
                Kembali
            </a>
        </div>

        {{-- Content Card --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                
                @if ($results->isEmpty())
                    {{-- State Kosong --}}
                    <div class="text-center py-5">
                        <h5 class="text-muted">Data tidak ditemukan</h5>
                        <p class="text-muted small">Coba kata kunci lain.</p>
                    </div>
                @else
                    {{-- Tabel Data --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Mahasiswa</th>
                                    <th class="py-3">Kelompok</th>
                                    <th class="py-3">Vendor</th>
                                    <th class="py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $mahasiswa)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold">{{ $mahasiswa->nama }}</div>
                                            <div class="small text-muted">{{ $mahasiswa->nim }}</div>
                                        </td>
                                        <td>{{ $mahasiswa->kelompok->nama ?? '-' }}</td>
                                        <td>{{ $mahasiswa->kelompok->vendor->nama_vendor ?? '-' }}</td>
                                        <td>
                                            @if ($mahasiswa->is_vegan)
                                                <span class="badge bg-success">Vegan</span>
                                            @endif
                                            @foreach ($mahasiswa->alergi as $alergi)
                                                <span class="badge bg-danger">{{ $alergi->nama }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($results->hasPages())
                        <div class="p-3 border-top">
                            {{ $results->links() }}
                        </div>
                    @endif
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
