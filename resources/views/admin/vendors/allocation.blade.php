<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Alokasi Kelompok</h1>
            <p class="text-muted mb-0">Atur kelompok mana saja yang akan dilayani oleh: <strong class="text-primary">{{ $vendor->nama_vendor }}</strong></p>
        </div>
        <a href="{{ route('admin.vendors.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Vendor
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <form method="GET" action="{{ route('admin.vendors.allocation.show', $vendor) }}" id="filterForm" class="d-flex align-items-center gap-3">
                <label for="hari_ke" class="form-label fw-bold mb-0">Tampilkan Jadwal Untuk Hari Ke-</label>
                <select name="hari_ke" id="hari_ke" class="form-select w-auto" onchange="this.form.submit()">
                    @for ($i = 1; $i <= $totalHari; $i++)
                        <option value="{{ $i }}" {{ $hariKe == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <form action="{{ route('admin.vendors.allocation.store', $vendor) }}" method="POST">
            @csrf
            <input type="hidden" name="hari_ke" value="{{ $hariKe }}">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light text-center">
                            <tr>
                                <th class="align-middle">Nama Kelompok</th>
                                @foreach (['pagi', 'siang', 'sore', 'malam'] as $waktu)
                                    <th class="text-capitalize">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="check-all-{{ $waktu }}">
                                            <label class="form-check-label" for="check-all-{{ $waktu }}">{{ $waktu }}</label>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelompoks as $kelompok)
                                <tr>
                                    <td class="fw-medium">{{ $kelompok->nama }}</td>
                                    @foreach (['pagi', 'siang', 'sore', 'malam'] as $waktu)
                                        @php
                                            $jadwal = $jadwalMap[$kelompok->id][$waktu] ?? null;
                                            $isThisVendor = $jadwal && $jadwal->vendor_id == $vendor->id;
                                            $isOtherVendor = $jadwal && $jadwal->vendor_id != $vendor->id;
                                        @endphp
                                        <td class="text-center">
                                            <div class="form-check d-flex justify-content-center">
                                                <input class="form-check-input check-{{ $waktu }}" type="checkbox"
                                                    name="jadwal[{{ $kelompok->id }}][{{ $waktu }}]"
                                                    value="1"
                                                    id="check-{{ $kelompok->id }}-{{ $waktu }}"
                                                    {{ $isThisVendor ? 'checked' : '' }}
                                                    {{ $isOtherVendor ? 'disabled' : '' }}>
                                            </div>
                                            @if ($isOtherVendor)
                                                <small class="text-muted d-block" style="font-size: 0.7rem;" title="Diatur oleh {{ $jadwal->vendor->nama_vendor }}">
                                                    {{ Str::limit($jadwal->vendor->nama_vendor, 10) }}
                                                </small>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted p-4">Belum ada data kelompok di event ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary px-5">
                    <i class="bi bi-save me-1"></i> Simpan Alokasi
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            ['pagi', 'siang', 'sore', 'malam'].forEach(waktu => {
                const checkAll = document.getElementById(`check-all-${waktu}`);
                if (checkAll) {
                    checkAll.addEventListener('click', function() {
                        document.querySelectorAll(`.check-${waktu}:not(:disabled)`).forEach(checkbox => {
                            checkbox.checked = this.checked;
                        });
                    });
                }
            });
        });
    </script>
</x-app-layout>