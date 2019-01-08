<?php
namespace App\Modules;

use App\Modules\Catalog\CatalogModule;
use App\Modules\Menu\MenuModule;
use App\Modules\Text\TextModule;

class Kernel
{
    protected $modules = [
        TextModule::class,
        MenuModule::class,
        CatalogModule::class
    ];

    /**
     * @param $name
     * @return array
     * @throws \Exception
     */
    public function __get($name)
    {
        switch ($name) {
            case "modules":
                return $this->modules;
                break;
        }
        throw new \Exception('Undefined property ' . $name . ' referenced.');
    }

}
