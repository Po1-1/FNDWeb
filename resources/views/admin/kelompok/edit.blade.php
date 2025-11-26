<x-app-layout>
    <div class="d-flex justify-content-between mb-4">
        <h1>Atur Kelompok: {{ $kelompok->nama }}</h1>
        <a href="{{ route('admin.kelompok.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <form action="{{ route('admin.kelompok.update', $kelompok) }}" method="POST">
        @csrf @method('PUT')

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Kelompok</label>
                    <input type="text" class="form-control" name="nama" value="{{ $kelompok->nama }}">
                </div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">Anggota & Kebutuhan Khusus</div>
            <div class="card-body table-responsive">
                <p class="text-muted small">
                    Anda dapat mengatur vendor khusus untuk mahasiswa tertentu (misal: alergi parah). 
                    Jika dibiarkan "Ikut Jadwal Kelompok", mahasiswa akan mengikuti vendor yang diatur di tabel jadwal di bawah.
                </p>
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Info Diet</th>
                            <th>Alergi</th>
                            <th>Override Vendor (Khusus Anak Ini)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelompok->mahasiswas as $mhs)
                        <tr>
                            <td>{{ $mhs->nama }}</td>
                            <td>
                                @if($mhs->is_vegan) <span class="badge bg-success">Vegan</span> @else - @endif
                            </td>
                            <td>
                                @forelse($mhs->alergi as $a) 
                                    <span class="badge bg-danger">{{ $a->nama }}</span> 
                                @empty 
                                    <span class="text-muted">-</span> 
                                @endforelse
                            </td>
                            <td>
                                <select name="custom_vendor[{{ $mhs->id }}]" class="form-select form-select-sm">
                                    <option value="">-- Ikut Jadwal Kelompok --</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}" {{ $mhs->custom_vendor_id == $v->id ? 'selected' : '' }}>
                                            {{ $v->nama_vendor }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-info text-white">Jadwal Vendor Kelompok (Default)</div>
            <div class="card-body table-responsive">
                <p class="text-muted small">Tentukan vendor untuk kelompok ini berdasarkan hari dan waktu makan.</p>
                <table class="table table-striped text-center align-middle">
                    <thead>
                        <tr>
                            <th>Hari Ke-</th>
                            <th>Pagi</th>
                            <th>Siang</th>
                            <th>Sore</th>
                            <th>Malam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($h = 1; $h <= $totalHari; $h++)
                        <tr>
                            <td class="fw-bold">{{ $h }}</td>
                            @foreach(['pagi', 'siang', 'sore', 'malam'] as $waktu)
                            <td>
                                @php
                                    // Cari data existing
                                    $existing = $kelompok->jadwal->where('hari_ke', $h)->where('waktu_makan', $waktu)->first();
                                    $val = $existing ? $existing->vendor_id : '';
                                @endphp
                                <select name="jadwal[{{ $h }}][{{ $waktu }}]" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}" {{ $val == $v->id ? 'selected' : '' }}>
                                            {{ $v->nama_vendor }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            @endforeach
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-lg">Simpan Perubahan</button>
    </form>
</x-app-layout>