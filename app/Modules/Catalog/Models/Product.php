<?php

namespace App\Modules\Catalog\Models;

use App\Models\Site;
use App\Modules\Image\Models\Image;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'm_catalog_products';

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

    public function folders()
    {
        return $this->belongsToMany(Folder::class, 'm_catalog_folder_product');
    }

    public function images()
    {
        return $this
            ->belongsToMany(Image::class, 'm_catalog_image_product')
            ->withPivot(['order'])
            ->orderBy('pivot_order');
    }

    public function params()
    {
        return $this
            ->belongsToMany(Param::class, 'm_catalog_param_product')
            ->withPivot(['param_id', 'product_id', 'value']);
    }
}
