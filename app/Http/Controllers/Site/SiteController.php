<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Modules\Module;
use App\SiteInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use TwigBridge\Facade\Twig;

class SiteController extends Controller
{
    public function index()
    {
        SiteInfo::init();
        $vars = [];
        $designVars = SiteInfo::$design->variables;

        foreach ($designVars as $var) {
            $var->page = SiteInfo::$page;
            $vars[$var->name] = $var->data;
        }

        return SiteInfo::$templateEngine->render('index', $vars);
    }
}
