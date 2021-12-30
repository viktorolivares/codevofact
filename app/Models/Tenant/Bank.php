<?php

namespace App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Bank extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = [
        'description',
    ];
}
