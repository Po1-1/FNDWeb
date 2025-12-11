<x-app-layout>
    <h1>Buat Tenant & Admin Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('developer.tenants.store') }}" method="POST">
                @csrf
                
                <h5 class="mb-3">Detail Tenant</h5>
                <div class="mb-4 border p-3 rounded">
                    <label for="tenant_name" class="form-label">Nama Tenant</label>
                    <input type="text" class="form-control @error('tenant_name') is-invalid @enderror" id="tenant_name" name="tenant_name" value="{{ old('tenant_name') }}" required placeholder="Cth: FND 2025">
                    @error('tenant_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <h5 class="mb-3">Akun Admin untuk Tenant Ini</h5>
                <div class="border p-3 rounded">
                    <div class="mb-3">
                        <label for="admin_name" class="form-label">Nama Admin</label>
                        <input type="text" class="form-control @error('admin_name') is-invalid @enderror" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
                        @error('admin_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Email Admin</label>
                        <input type="email" class="form-control @error('admin_email') is-invalid @enderror" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                        @error('admin_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="admin_password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('admin_password') is-invalid @enderror" id="admin_password" name="admin_password" required>
                            @error('admin_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="admin_password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="admin_password_confirmation" name="admin_password_confirmation" required>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('developer.tenants.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Tenant & Admin</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>