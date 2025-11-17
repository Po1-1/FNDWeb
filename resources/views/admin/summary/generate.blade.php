<x-app-layout>
    <h1>Generate Laporan Harian</h1>
    <p>Event: <strong>{{ $event->nama_event }}</strong></p>

    <form action="{{ route('admin.summary.generate.store') }}" method="POST">
        @csrf
        
        <div class="row g-4">
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header fs-5">Input Rekapitulasi (Admin)</div>
                    <div class="card-body">
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        
                        <div class="mb-3">
                            <label for="tanggal_summary" class="form-label">Tanggal Laporan</label>
                            <input type="date" class="form-control" id="tanggal_summary" name="tanggal_summary" value="{{ $tanggal->format('Y-m-d') }}">
                        </div>

                        <div class="mb-3">
                            <label for="sisa_galon" class="form-label">Sisa Galon (Fisik)</label>
                            <input type="number" class="form-control @error('sisa_galon') is-invalid @enderror" 
                                   id="sisa_galon" name="sisa_galon" 
                                   value="{{ old('sisa_galon', $summary->sisa_galon ?? 0) }}" required min="0">
                            @error('sisa_galon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="catatan_harian" class="form-label">Catatan Summary (Admin)</label>
                            <textarea class="form-control @error('catatan_harian') is-invalid @enderror" 
                                      id="catatan_harian" name="catatan_harian" rows="4" 
                                      placeholder="Tulis ringkasan kejadian hari ini di sini.">{{ old('catatan_harian', $summary->catatan_harian ?? '') }}</textarea>
                            @error('catatan_harian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <p class="text-muted small mt-4">
                            Data kalkulasi (penggunaan galon, plastik, makanan, vendor) akan diambil otomatis dari log kasir saat Anda menekan tombol "Generate".
                        </p>

                        <div class="mt-4">
                            <a href="{{ route('admin.summaries.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Generate / Update Laporan</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                
                <div class="card shadow-sm bg-light mb-4">
                    <div class="card-header fs-5">Rekap Otomatis (Preview)</div>
                    <div class="card-body">
                        <p class="text-muted small">Data ini dihitung dari log kasir per {{ $tanggal->format('d/m/Y') }}.</p>
                        
                        <strong>Penggunaan Logistik:</strong>
                        <ul class="list-group list-group-flush">
                            @forelse ($rekapLogistik as $log)
                                <li class="list-group-item bg-light d-flex justify-content-between align-items-center">
                                    {{ $log['nama_item'] }}
                                    <span class="badge bg-primary rounded-pill">{{ $log['total_digunakan'] }} {{ $log['satuan'] }}</span>
                                </li>
                            @empty
                                <li class="list-group-item bg-light text-muted small">Belum ada logistik tercatat.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="card shadow-sm">
                    <div class="card-header fs-5">Log Catatan Harian Kasir</div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <h6 class="text-muted">Catatan Distribusi Makanan</h6>
                        @forelse ($makananNotes as $note)
                            <div class="alert alert-info">
                                <strong>{{ $note->kelompok->nama ?? 'N/A' }}</strong> ({{ $note->created_at->format('H:i') }}):
                                <p class="mb-0">{{ $note->catatan }}</p>
                            </div>
                        @empty
                            <p class="small text-muted">Tidak ada catatan distribusi makanan.</p>
                        @endforelse

                        <hr>
                        <h6 class="text-muted">Catatan Pengambilan Logistik</h6>
                        @forelse ($logistikNotes as $note)
                            <div class="alert alert-warning">
                                <strong>Logistik</strong> ({{ $note->created_at->format('H:i') }}):
                                <p class="mb-0">{{ $note->catatan }}</p>
                            </div>
                        @empty
                            <p class="small text-muted">Tidak ada catatan pengambilan logistik.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </form>
</x-app-layout>