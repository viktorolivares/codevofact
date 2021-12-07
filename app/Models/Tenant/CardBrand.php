<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Builder;


class CardBrand extends ModelTenant
{
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'description',
        'id',
    ];

}
