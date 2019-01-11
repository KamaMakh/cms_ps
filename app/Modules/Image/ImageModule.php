<?php
namespace App\Modules\Image;

use App\Models\Variable;
use App\Modules\Image\Models\Image;
use App\Modules\Module;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImageModule extends Module
{
    protected $model = Image::class;

    public $title = 'Image';

    public $page = null;

    ///*
    public function varGetOne(Variable $variable)
    {
        $link = DB::table('item_variable')
            ->where('variable_id', $variable->id)
            ->where('page_id', !$variable->shared ? $this->page->id : null);
        $link = $link->first();
        if ($link) {
            return call_user_func($this->model . '::find', $link->item_id);
        }
        return null;
    }

    public function selectVarGetOne(Variable $variable)
    {
        return $this->varGetOne($variable);
    }

    public function setVarGetOne(Variable $variable, $data)
    {
        $item = $this->varGetOne($variable);
        if (!$item) {
            $item = new $this->model;
            $item->fill($data);

            $item->save();

            DB::table('item_variable')->insert([
                'variable_id' => $variable->id,
                'page_id' => !$variable->shared ? $this->page->id : null,
                'item_type' => crc32($this->model),
                'item_id' => $item->id,
            ]);
        } else {
            $item->update($data);
        }
        return $item;
    }
    //*/
}
