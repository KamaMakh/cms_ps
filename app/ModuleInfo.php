<?php
namespace App;

use App\Models\Design;
use App\Models\Page;
use App\Models\Site;
use App\Modules\Kernel;
use \Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Log;
use \Twig_Loader_Array;
use \Twig_Environment;

class ModuleInfo
{
    protected static $inited = false;
    public static $kernel;

    public static function init()
    {
        if (self::$inited) return;
        self::$kernel = new Kernel();
    }

    public static function getVariables()
    {
        self::init();
        $vars = [];
        foreach (self::$kernel->modules as $module) {
            $moduleInstance = new $module();
            $methods = get_class_methods($moduleInstance);

            $moduleName = explode("\\", $module);
            $moduleName = $moduleName[count($moduleName) - 1];

            $moduleName = strtolower(preg_replace("/Module$/", "", $moduleName));

            foreach ($methods as $method) {
                $snake_case_variable_name = strtolower(preg_replace("/([A-Z])/", '_${1}', $method));
                $parts = explode("_", $snake_case_variable_name);
                if ($parts[0] == "var") {
                    if (empty($vars[$moduleName])) {
                        $vars[$moduleName] = [];
                    }
                    $vars[$moduleName][] = strtolower(preg_replace("/^var/", "", $method));
                }
            }
        }
        return $vars;
    }
}
