<?php

namespace Modules\Item\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;

class Material extends ModelTenant
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'description'
    ];

    public function items()
    {
        return $this->hasMany(Material::class);
    }


}
