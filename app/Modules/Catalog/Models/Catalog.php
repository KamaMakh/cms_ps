<?php

namespace App\Modules\Catalog\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $table = 'm_catalogs';

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function sources()
    {
        return $this->hasMany(Source::class);
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
