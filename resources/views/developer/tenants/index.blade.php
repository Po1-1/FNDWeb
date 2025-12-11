<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Tenant</h1>
        <a href="{{ route('developer.tenants.create') }}" class="btn btn-primary">
            + Buat Tenant Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama Tenant</th>
                            <th>Admin Utama</th>
                            <th>Email Admin</th>
                            <th>Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                            <tr>
                                <td>{{ $tenant->name }}</td>
                                <td>{{ $tenant->users->where('role', 'admin')->first()->name ?? 'N/A' }}</td>
                                <td>{{ $tenant->users->where('role', 'admin')->first()->email ?? 'N/A' }}</td>
                                <td>{{ $tenant->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada tenant yang dibuat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $tenants->links() }}
            </div>
        </div>
    </div>
</x-app-layout>