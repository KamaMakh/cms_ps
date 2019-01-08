<?php

namespace App\Models;

use App\Modules\Menu\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use SoftDeletes;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }
}
