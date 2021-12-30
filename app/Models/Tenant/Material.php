<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;

class Material extends ModelTenant
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'description'
    ];

}
