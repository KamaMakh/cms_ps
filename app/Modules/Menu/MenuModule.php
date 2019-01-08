<?php
namespace App\Modules\Menu;

use App\Models\Page;
use App\Models\Variable;
use App\Modules\Menu\Models\Menu;
use App\Modules\Module;
use Illuminate\Support\Facades\DB;

class MenuModule extends Module
{
    protected $model = Menu::class;

    public $title = 'Menu';

    public $page = null;

    public function varGetOne(Variable $variable)
    {
        $link = DB::table('item_variable')
            ->where('variable_id', $variable->id)
            ->where('page_id', !$variable->shared ? $this->page->id : null)
            ->first();
        if ($link) {
            $menu = call_user_func($this->model . '::find', $link->item_id);
            if ($menu) {
                $menu->load(['pages']);
                return $menu;
            }
        }
        return null;
    }

    public function selectVarGetOne(Variable $variable)
    {
        if ($this->page) {
            $site = $this->page->menu->site;

            return Menu::where('site_id', '=', $site->id)->get();
        }
        return null;
    }

    public function setVarGetOne(Variable $variable, $data)
    {
        $item = Menu::find($data['id']);
        DB::table('item_variable')->updateOrInsert([
            'variable_id' => $variable->id,
            'page_id' => !$variable->shared ? $this->page->id : null,
            'item_type' => crc32($this->model),
        ], [
            'item_id' => $item->id,
        ]);
        return $item;
    }
}
