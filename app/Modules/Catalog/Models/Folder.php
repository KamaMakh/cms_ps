<?php

namespace App\Modules\Catalog\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $table = 'm_catalog_folders';

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
        return $this->belongsToMany(Product::class, 'm_catalog_folder_product');
    }

    public function params()
    {
        return $this->belongsToMany(Param::class, 'm_catalog_folder_param');
    }
}
