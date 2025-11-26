<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Detail Rekap Harian</h1>
            <p class="text-muted mb-0">
                Event: <strong>{{ $summary->event->nama_event ?? 'N/A' }}</strong> &bull; 
                Tanggal: {{ $summary->tanggal_summary->format('d F Y') }}
            </p>
        </div>
        <a href="{{ route('admin.summaries.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0 fs-6">📦 Rekap Logistik (Snapshot)</h5>
                </div>
                <div class="card-body">
                    @if(empty($summary->rekap_penggunaan_logistik))
                        <div class="alert alert-light text-center text-muted">
                            Tidak ada data penggunaan logistik tercatat.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Item</th>
                                        <th class="text-end">Total Digunakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- PERBAIKAN: Gunakan syntax Array ['key'], bukan Object ->key --}}
                                    @foreach ($summary->rekap_penggunaan_logistik as $rekap)
                                        <tr>
                                            <td>{{ $rekap['nama_item'] ?? '-' }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $rekap['total_digunakan'] ?? 0 }} {{ $rekap['satuan'] ?? '' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <hr>
                    
                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded border">
                        <strong>Sisa Galon (Fisik):</strong>
                        <span class="badge bg-secondary fs-6">{{ $summary->sisa_galon }} Galon</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0 fs-6">Utensils & Makanan (Snapshot)</h5>
                </div>
                <div class="card-body">
                    @if(empty($summary->rekap_penggunaan_makanan))
                        <div class="alert alert-light text-center text-muted">
                            Tidak ada data penggunaan makanan tercatat.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vendor Penyedia</th>
                                        <th class="text-end">Total Porsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- PERBAIKAN: Gunakan syntax Array ['key'] --}}
                                    @foreach ($summary->rekap_penggunaan_makanan as $rekap)
                                        <tr>
                                            <td>{{ $rekap['vendor_nama'] ?? 'Vendor Tidak Dikenal' }}</td>
                                            <td class="text-end">
                                                <span class="badge bg-success rounded-pill">
                                                    {{ $rekap['total_diambil'] ?? 0 }} Porsi
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mt-3">
                        <small class="text-muted fw-bold">Daftar Vendor Bertugas Hari Ini:</small>
                        <div class="mt-1">
                            @forelse ($summary->vendor_bertugas_hari_ini as $vendorName)
                                <span class="badge bg-outline-secondary border text-dark">{{ $vendorName }}</span>
                            @empty
                                <span class="text-muted small">- Tidak ada data -</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0 fs-6">📝 Catatan Summary (Admin)</h5>
                </div>
                <div class="card-body bg-light">
                    @if($summary->catatan_harian)
                        <p class="mb-0" style="white-space: pre-line;">{{ $summary->catatan_harian }}</p>
                    @else
                        <p class="text-muted mb-0">Tidak ada catatan tambahan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>