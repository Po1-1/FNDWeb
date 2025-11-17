<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Rekap Harian</h1>
        <a href="{{ route('admin.summaries.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Event: {{ $summary->event->nama_event ?? 'N/A' }}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Tanggal: {{ $summary->tanggal_summary->format('d F Y') }}</h6>
            
            <ul class="list-group list-group-flush mt-3">
                
                <li class="list-group-item">
                    <strong>Rekap Penggunaan Logistik (Snapshot):</strong>
                    @if(empty($summary->rekap_penggunaan_logistik))
                        <p class="mb-0 text-muted">Tidak ada data penggunaan logistik.</p>
                    @else
                        <ul class="list-group mt-2">
                        @foreach ($summary->rekap_penggunaan_logistik as $rekap)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $rekap['nama_item'] }}
                                <span class="badge bg-primary rounded-pill">{{ $rekap['total_digunakan'] }} {{ $rekap['satuan'] }}</span>
                            </li>
                        @endforeach
                        </ul>
                    @endif
                </li>

                 <li class="list-group-item d-flex justify-content-between align-items-center">
                    Sisa Galon (Input Admin)
                    <span class="badge bg-secondary rounded-pill">{{ $summary->sisa_galon }}</span>
                </li>

                <li class="list-group-item">
                    <strong>Rekap Penggunaan Makanan (Snapshot):</strong>
                    @if(empty($summary->rekap_penggunaan_makanan))
                        <p class="mb-0 text-muted">Tidak ada data penggunaan makanan.</p>
                    @else
                        <ul class="list-group mt-2">
                        @foreach ($summary->rekap_penggunaan_makanan as $rekap)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $rekap['vendor_nama'] ?? 'Vendor Tidak Dikenal' }}
                                <span class="badge bg-success rounded-pill">{{ $rekap['total_diambil'] }} porsi</span>
                            </li>
                        @endforeach
                        </ul>
                    @endif
                </li>

                <li class="list-group-item">
                    <strong>Vendor Bertugas (Snapshot):</strong>
                    <div>
                        @forelse ($summary->vendor_bertugas_hari_ini as $vendorName)
                            <span class="badge bg-info">{{ $vendorName }}</span>
                        @empty
                            <span class="text-muted">Tidak ada data vendor.</span>
                        @endforelse
                    </div>
                </li>
                
                <li class="list-group-item">
                    <strong>Catatan Summary (dari Admin):</strong>
                    <p class="mb-0">{{ $summary->catatan_harian ?? 'Tidak ada catatan.' }}</p>
                </li>
            </ul>
        </div>
    </div>
</x-app-layout>