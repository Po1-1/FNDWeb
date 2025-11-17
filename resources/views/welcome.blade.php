<x-guest-layout>
    <div class="p-5 mb-4 bg-white rounded-3 shadow-sm">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Selamat Datang di FND</h1>
            <p class="col-md-8 fs-4">Sistem Distribusi Makanan Event</p>
            <hr>
            <p>Gunakan fitur pencarian di bawah untuk menemukan data mahasiswa atau kelompok.</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Pencarian Mahasiswa / Kelompok</h5>
            <form action="{{ route('guest.search') }}" method="GET">
                <div class="input-group mb-3">
                    <input type="text" 
                           class="form-control" 
                           name="query" 
                           placeholder="Masukkan NIM, Nama, atau Nama Kelompok..." 
                           value="{{ request('query') }}">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>