<x-app-layout>
    <h1>Dashboard Mentor</h1>
    <p class="fs-5 text-muted">Selamat datang, {{ $user->name }}!</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Informasi Anda</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nama:</strong> {{ $mentorData->nama }}</li>
                <li class="list-group-item"><strong>Prodi:</strong> {{ $mentorData->prodi }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                <li class="list-group-item"><strong>Kelompok:</strong> {{ $mentorData->kelompok }}</li>
            </ul>

            <a href="{{ route('mentor.kelompok.show') }}" class="btn btn-primary mt-3">Lihat Anggota Kelompok</a>
        </div>
    </div>
</x-app-layout>