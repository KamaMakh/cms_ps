<?php

namespace App\Modules\Catalog\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;

class Param extends Model
{
    protected $table = 'm_catalog_product_params';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    public function source()
    {
        return $this->belongsTo(Source::class);
    }

    public function products()
    {
        return $this
            ->belongsToMany(Product::class, 'm_catalog_param_product')
            ->withPivot(['param_id', 'product_id', 'value']);
    }
}
