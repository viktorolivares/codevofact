<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Model;

class ModelTenant extends Model
{
    use UsesTenantConnection;

    public function scopeWhereActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeOrderByDescription($query)
    {
        return $query->orderBy('description');
    }

    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }
}
