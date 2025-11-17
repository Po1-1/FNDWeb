<x-guest-layout>
    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="mb-4">Hasil Pencarian untuk: "{{ $query }}"</h4>

            @if ($results->isEmpty())
                <div class="alert alert-warning">
                    Data tidak ditemukan.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Kelompok</th>
                                <th>Vendor Ditetapkan</th>
                                <th>Vegan</th>
                                <th>Alergi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $mahasiswa)
                                <tr>
                                    <td>{{ $mahasiswa->nim }}</td>
                                    <td>{{ $mahasiswa->nama }}</td>
                                    <td>
                                        {{ $mahasiswa->kelompok->nama ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $mahasiswa->kelompok->vendor->nama_vendor ?? 'Belum Diatur' }}
                                    </td>
                                    <td>
                                        @if ($mahasiswa->is_vegan)
                                            <span class="badge bg-success">Ya</span>
                                        @else
                                            <span class="badge bg-secondary">Tidak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @forelse ($mahasiswa->alergi as $alergi)
                                            <span class="badge bg-danger">{{ $alergi->nama }}</span>
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $results->links() }}
                </div>
            @endif
        </div>
    </div>
</x-guest-layout>