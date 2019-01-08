<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'design_id',
        'module_id',
        'is_page',
        'body',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function pages()
    {
        return $this->hasMany(Page::class);
    }

    public function variables()
    {
        return $this->belongsToMany(Variable::class);
    }
}
