<?php
namespace App;

use App\Models\Design;
use App\Models\Page;
use App\Models\Site;
use \Illuminate\Support\Facades\Request;
use \Illuminate\Support\Facades\Log;
use \Twig_Loader_Array;
use \Twig_Environment;

class SiteInfo
{
    public static $site;
    public static $design;
    public static $page;
    public static $templateEngine;

    public static function init()
    {
        $domain = Request::getHost();

        $site = Site::where('domain', $domain)->first();

        if (!$site) {
            abort(404);
        }

        $url = '/' . ltrim(Request::path(), '/');

        $page = Page::with([
            'template'
        ])->whereHas('menu', function($query) use ($site) {
            $query->where('site_id', $site->id);
        });

        if (empty($url) || $url === "/") {
            $page->where('is_main', 1);
        } else {
            $page->where('url', $url);
        }
        $page = $page->first();

        if ($page->is_main && $url !== "/") {
            abort(302, '', ['Location' => '/']);
        }

        if (empty($page->template)) {
            abort(404);
        }

        $design = Design::find(!empty($page->design_id) ? $page->design_id : $site->design_id);

        $templates = [
            'design' => $design->body,
            'index' => $page->template->body
        ];

        $loader = new Twig_Loader_Array($templates);

        $twig = new Twig_Environment($loader);

        self::$site = $site;
        self::$design = $design;
        self::$page = $page;
        self::$templateEngine = $twig;
    }
}
