<?php

namespace App\Modules\Catalog\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $table = 'm_catalog_sources';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }
}
