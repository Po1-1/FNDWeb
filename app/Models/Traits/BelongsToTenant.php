<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait BelongsToTenant
{
    /**
     * The "booting" method of the trait.
     *
     * @return void
     */
    protected static function bootBelongsToTenant(): void
    {
        // Terapkan global scope yang sudah kita buat.
        static::addGlobalScope(new TenantScope);

        // Gunakan model event 'creating' untuk otomatis mengisi tenant_id.
        // Ini akan berjalan setiap kali kita memanggil Model::create().
        static::creating(function ($model) {
            // Hanya isi jika tenant_id kosong, ada user login, dan user itu bukan developer.
            if (is_null($model->tenant_id) && Auth::check() && Auth::user()->role !== 'developer') {
                $model->tenant_id = Auth::user()->tenant_id;
            }
        });
    }
}