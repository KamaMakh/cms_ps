<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Variable extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'title',
        'name',
        'type',
        'method',
        'shared',
    ];

    public $page = null;

    public function designs()
    {
        return $this->belongsToMany(Design::class);
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class);
    }

    public function getDataAttribute()
    {
        $className = \App\Modules\Module::NAME_SPACE . $this->type . '\\' . $this->type . \App\Modules\Module::POSTFIX;
        $module = new $className;
        $method = 'var' . $this->method;
        $module->page = $this->page;
        return $module->{$method}($this);
    }

    public function setDataAttribute($data)
    {
        $className = \App\Modules\Module::NAME_SPACE . $this->type . '\\' . $this->type . \App\Modules\Module::POSTFIX;
        $module = new $className;
        $method = 'setVar' . $this->method;
        $module->page = $this->page;
        return $module->{$method}($this, $data);
    }

    public function getSelectorAttribute()
    {
        $className = \App\Modules\Module::NAME_SPACE . $this->type . '\\' . $this->type . \App\Modules\Module::POSTFIX;
        $module = new $className;
        $method = 'selectVar' . $this->method;
        $module->page = $this->page;
        return $module->{$method}($this);
    }
}
