<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Event</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            + Buat Event Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Status</th>
                            <th>Nama Event</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                            <tr>
                                <td>
                                    @if($event->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td>{{ $event->nama_event }}</td>
                                <td>{{ $event->tanggal_mulai->format('d M Y') }}</td>
                                <td>{{ $event->tanggal_selesai->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data event.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>