<?php

namespace App\Modules\Image\Models;

use App\Models\Site;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'm_images';
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
