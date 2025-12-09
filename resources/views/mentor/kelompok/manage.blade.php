<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Kelola Bukti Distribusi: {{ $kelompok->nama }}</h2>
            <p class="text-muted mb-0">Upload, lihat, atau hapus bukti foto untuk setiap sesi makan.</p>
        </div>
        <a href="{{ route('mentor.kelompok.show') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Anggota
        </a>
    </div>

    {{-- DAFTAR RIWAYAT DISTRIBUSI & BUKTI --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-light fw-bold">
            Riwayat Distribusi & Upload Bukti
        </div>
        <div class="card-body">
            @forelse ($kelompok->distribusi as $distribusi)
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Sesi: {{ ucfirst($distribusi->waktu_makan) }} - Hari ke-{{ $distribusi->hari_ke }}</strong>
                            <small class="text-muted d-block">{{ $distribusi->created_at->translatedFormat('l, d F Y H:i') }}</small>
                        </div>
                        <span class="badge bg-info">{{ $distribusi->details->count() }} / {{ $kelompok->mahasiswas->count() }} Peserta Makan</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Kolom untuk menampilkan bukti yang sudah ada --}}
                            <div class="col-md-8">
                                <h6 class="fw-bold">Bukti Ter-upload:</h6>
                                @if($distribusi->buktis->isEmpty())
                                    <p class="text-muted small">Belum ada bukti untuk sesi ini.</p>
                                @else
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach ($distribusi->buktis as $bukti)
                                            <div class="position-relative">
                                                <a href="{{ Storage::url($bukti->image_path) }}" target="_blank">
                                                    <img src="{{ Storage::url($bukti->image_path) }}" alt="Bukti" class="img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                                </a>
                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('mentor.kelompok.bukti.destroy', $bukti) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus bukti ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle" style="width: 28px; height: 28px;">
                                                        <i class="bi bi-x"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Kolom untuk form upload --}}
                            <div class="col-md-4 border-start">
                                <h6 class="fw-bold">Upload Bukti Baru</h6>
                                <form action="{{ route('mentor.kelompok.bukti.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="distribusi_id" value="{{ $distribusi->id }}">
                                    <div class="mb-2">
                                        <input type="file" name="bukti_gambar" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan (opsional)">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Upload</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center">Belum ada riwayat distribusi makanan yang dicatat oleh Kasir untuk kelompok ini.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>