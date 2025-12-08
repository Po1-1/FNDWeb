<x-app-layout>
    <h1>Tambah Mahasiswa Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.mahasiswa.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim"
                            name="nim" value="{{ old('nim') }}" required>
                        @error('nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                            name="nama" value="{{ old('nama') }}" required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="prodi" class="form-label">Prodi</label>
                        <input type="text" class="form-control @error('prodi') is-invalid @enderror" id="prodi"
                            name="prodi" value="{{ old('prodi') }}" required>
                        @error('prodi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="kelompok_id" class="form-label">Kelompok</label>
                        <select class="form-select @error('kelompok_id') is-invalid @enderror" id="kelompok_id"
                            name="kelompok_id" required>
                            <option value="">Pilih Kelompok...</option>
                            @foreach ($kelompoks as $kelompok)
                                <option value="{{ $kelompok->id }}"
                                    {{ old('kelompok_id') == $kelompok->id ? 'selected' : '' }}>
                                    {{ $kelompok->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelompok_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="no_urut" class="form-label">Nomor Urut</label>
                        <input type="number" class="form-control @error('no_urut') is-invalid @enderror" id="no_urut"
                            name="no_urut" value="{{ old('no_urut') }}" required>
                        @error('no_urut')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 align-self-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_vegan" name="is_vegan"
                                value="1" {{ old('is_vegan') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_vegan">
                                Apakah Vegan?
                            </label>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <h5>Alergi yang Dimiliki</h5>
                @error('alergis')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="row">
                    @forelse ($alergis as $alergi)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="alergis[]"
                                    value="{{ $alergi->id }}" id="alergi-{{ $alergi->id }}"
                                    {{ is_array(old('alergis')) && in_array($alergi->id, old('alergis')) ? ' checked' : '' }}>
                                <label class="form-check-label" for="alergi-{{ $alergi->id }}">
                                    {{ $alergi->nama }}
                                </label>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">Tidak ada data alergi di database. Harap tambahkan di menu "Manajemen
                                Alergi".</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
