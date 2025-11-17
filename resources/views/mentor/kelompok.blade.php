<x-app-layout>
    <h1>Anggota Kelompok: {{ $kelompok }}</h1>
    <p>Berikut adalah daftar anggota kelompok Anda, termasuk data vegan dan alergi.</p>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama</th>
                            <th>NIM</th>
                            <th>Prodi</th>
                            <th>Vegan</th>
                            <th>Alergi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotas as $anggota)
                            <tr>
                                <td>
                                    {{ $anggota->nama }}
                                    @if ($anggota->user_id == Auth::id())
                                        <span class="badge bg-info">Anda</span>
                                    @endif
                                </td>
                                <td>{{ $anggota->nim }}</td>
                                <td>{{ $anggota->prodi }}</td>
                                <td>
                                    @if ($anggota->is_vegan)
                                        <span class="badge bg-success">Ya</span>
                                    @else
                                        <span class="badge bg-secondary">Tidak</span>
                                    @endif
                                </td>
                                <td>
                                    @forelse ($anggota->alergi as $alergi)
                                        <span class="badge bg-danger">{{ $alergi->nama }}</span>
                                    @empty
                                        -
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada anggota di kelompok ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>