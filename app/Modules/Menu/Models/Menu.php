<?php

namespace App\Modules\Menu\Models;

use App\Models\Page;
use App\Models\Site;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }
}
