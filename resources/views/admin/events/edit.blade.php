<x-app-layout>
    <h1>Edit Event: {{ $event->nama_event }}</h1>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.events.update', $event) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_event" class="form-label">Nama Event</label>
                    <input type="text" class="form-control @error('nama_event') is-invalid @enderror" id="nama_event" name="nama_event" value="{{ old('nama_event', $event->nama_event) }}" required>
                    @error('nama_event') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $event->tanggal_mulai->format('Y-m-d')) }}" required>
                        @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $event->tanggal_selesai->format('Y-m-d')) }}" required>
                        @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $event->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Aktifkan Event Ini (Akan menonaktifkan event lain)</label>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>