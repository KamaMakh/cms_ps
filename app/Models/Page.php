<?php

namespace App\Models;

use App\Modules\Menu\Models\Menu;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'menu_id',
        'name',
        'template_id',
        'design_id',
        'url',
        'is_main',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
