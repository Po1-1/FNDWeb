<x-app-layout>
    <div class="row align-items-center mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-dark mb-1">Dashboard Mentor</h1>
            <p class="text-muted mb-0">Selamat datang, {{ $user->name }}!</p>
        </div>
        <div class="col-md-4 text-md-end">
            <div class="d-inline-block bg-white px-4 py-2 rounded-pill shadow-sm border">
                <i class="bi bi-calendar-event text-primary me-2"></i>
                <span class="fw-bold text-dark">Hari ke-{{ $hariKe }}</span>
            </div>
        </div>
    </div>

    @if(!$assignedKelompokId)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Akun Anda belum terhubung dengan data Mahasiswa/Kelompok di event ini. Silakan hubungi Admin.
        </div>
    @else
        <h5 class="fw-bold mb-3">Absensi Makan Hari Ini</h5>
        <div class="row g-3 mb-5">
            @foreach(['pagi', 'siang', 'sore', 'malam'] as $waktu)
                @php
                    $data = $summaryAbsensi[$waktu];
                    $isFilled = $data['filled'];
                    
                    $bgClass = $isFilled ? 'bg-success bg-opacity-10 border-success' : 'bg-white border-0 shadow-sm';
                    $textClass = $isFilled ? 'text-success' : 'text-dark';
                    $icon = match($waktu) {
                        'pagi' => 'bi-sunrise',
                        'siang' => 'bi-sun',
                        'sore' => 'bi-sunset',
                        'malam' => 'bi-moon-stars',
                    };
                @endphp
                <div class="col-md-3 col-6">
                    <a href="{{ route('mentor.absensi.create', ['hari_ke' => $hariKe, 'waktu_makan' => $waktu]) }}" 
                       class="card h-100 text-decoration-none {{ $bgClass }} hover-effect">
                        <div class="card-body text-center p-4">
                            <div class="fs-1 mb-2 {{ $textClass }}">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                            <h5 class="fw-bold text-capitalize {{ $textClass }} mb-3">{{ $waktu }}</h5>
                            
                            @if($isFilled)
                                <div class="mb-2">
                                    <span class="display-6 fw-bold text-success">{{ $data['hadir'] }}</span>
                                    <span class="text-muted small">/ {{ $data['total'] }} Hadir</span>
                                </div>
                                <span class="badge bg-success rounded-pill">
                                    <i class="bi bi-check-circle me-1"></i> Sudah Diisi
                                </span>
                            @else
                                <div class="mb-2 text-muted opacity-50">
                                    <span class="display-6 fw-bold">-</span>
                                </div>
                                <span class="badge bg-secondary bg-opacity-25 text-dark rounded-pill">
                                    Belum Diisi
                                </span>
                            @endif
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
