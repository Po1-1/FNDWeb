<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Import Data Mahasiswa</h1>
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    @if(session('validation_errors'))
    <div class="alert alert-danger">
        <h5 class="alert-heading">Error Validasi pada File Excel:</h5>
        <ul>
            @foreach(session('validation_errors') as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <p>Upload file XLSX/XLS untuk mengimpor data mahasiswa secara massal.</p>
            <p><strong>Penting:</strong> Pastikan file Anda memiliki kolom (heading): <strong>nim, nama, prodi, kelompok, no_urut, is_vegan (1 atau 0)</strong>. Logika "Panitia = Mentor" dan "Pembuatan Kelompok" akan dijalankan saat proses import.</p>
            
            <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">Pilih File (XLSX, XLS)</label>
                    <input class="form-control @error('file') is-invalid @enderror" type="file" id="file" name="file" required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="btn btn-primary">Import Sekarang</button>
            </form>
        </div>
    </div>
</x-app-layout>