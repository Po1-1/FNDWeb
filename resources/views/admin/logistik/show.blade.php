<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Detail Item Logistik</h1>
        <a href="{{ route('admin.logistik.index') }}" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">{{ $logistik->nama_item }}</h5>
            
            <ul class="list-group list-group-flush mt-3">
                <li class="list-group-item"><strong>ID:</strong> {{ $logistik->id }}</li>
                <li class="list-group-item"><strong>Stok Awal:</strong> {{ $logistik->stok_awal }}</li>
                <li class="list-group-item"><strong>Satuan:</strong> {{ $logistik->satuan }}</li>
                <li class="list-group-item"><strong>Dibuat Pada:</strong> {{ $logistik->created_at->format('d M Y, H:i') }}</li>
                <li class="list-group-item"><strong>Diperbarui Pada:</strong> {{ $logistik->updated_at->format('d M Y, H:i') }}</li>
            </ul>

            <a href="{{ route('admin.logistik.edit', $logistik) }}" class="btn btn-warning mt-3">Edit Item Ini</a>
        </div>
    </div>
</x-app-layout>