<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Rekapitulasi Event (Summary)</h1>
        <a href="{{ route('admin.summary.generate.form') }}" class="btn btn-success">
            + Generate Laporan Harian
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.summaries.index') }}">
                <div class="row g-2">
                    <div class="col-md-4">
                        <label for="event_id" class="form-label">Pilih Event:</label>
                        <select name="event_id" id="event_id" class="form-select">
                            @if($events->isEmpty())
                                <option>Tidak ada event</option>
                            @else
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ $selectedEventId == $event->id ? 'selected' : '' }}>
                                        {{ $event->nama_event }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-body">
            <p>Menampilkan rekap harian sisa logistik dan makanan.</p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summaries as $summary)
                            <tr>
                                <td>{{ $summary->tanggal_summary->format('d M Y') }}</td>
                                <td>{{ Str::limit($summary->catatan_harian, 50) ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.summaries.show', $summary) }}" class="btn btn-sm btn-info">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    Tidak ada data summary untuk event ini.
                                    <a href="{{ route('admin.summary.generate.form') }}" class="d-block mt-2">Generate Laporan Sekarang</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $summaries->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</x-app-layout>