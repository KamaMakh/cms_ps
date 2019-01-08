<?php

namespace App\Http\Controllers\API\Site;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Page;
use App\Models\Site;
use App\Models\Variable;
use App\Modules\Menu\Models\Menu;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MenuController extends Controller
{
    public function index(Site $site)
    {
        return response()->json($site->menus);
    }

    public function show(Site $site, Menu $menu)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        $menu->load([
            'pages',
        ]);
        return response()->json($menu);
    }

    public function store(Site $site, Request $request)
    {
        $data = $request->all();
        $data['site_id'] = $site->id;
        $menu = Site::create($data);

        return response()->json($menu, 201);
    }

    public function update(Site $site, Menu $menu, Request $request)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        $menu->update($request->all());

        return response()->json($menu);
    }

    public function delete(Site $site, Menu $menu)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        $menu->delete();

        return response()->json(null, 204);
    }

    public function pageStore(Site $site, Menu $menu, Request $request)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        $data = $request->all();
        $data['url'] = "/" . ltrim($data['url'], '/');
        $data['menu_id'] = $menu->id;
        $page = Page::create($data);

        return response()->json($page, 201);
    }

    public function pageShow(Site $site, Menu $menu, Page $page)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        if ($page->menu_id != $menu->id) {
            throw new ModelNotFoundException;
        }
        return response()->json($page);
    }

    public function pageUpdate(Request $request, Site $site, Menu $menu, Page $page)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        if ($page->menu_id != $menu->id) {
            throw new ModelNotFoundException;
        }
        $data = $request->all();
        $data['url'] = "/" . ltrim($data['url'], '/');
        $page->update($data);

        return response()->json($page);
    }

    public function pageDelete(Site $site, Menu $menu, Page $page)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        if ($page->menu_id != $menu->id) {
            throw new ModelNotFoundException;
        }
        $page->delete();

        return response()->json(null, 204);
    }

    public function pageVariables(Request $request, Site $site, Menu $menu, Page $page)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        if ($page->menu_id != $menu->id) {
            throw new ModelNotFoundException;
        }
        $design = Design::find(!empty($page->design_id) ? $page->design_id : $site->design_id);

        return response()->json(array_merge($design->variables->toArray(), $page->template->variables->toArray()));
    }


    public function pageVariableShow(Site $site, Menu $menu, Page $page, Variable $variable)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        if ($page->menu_id != $menu->id) {
            throw new ModelNotFoundException;
        }
        $variable->page = $page;
        return response()->json($variable->selector);
    }

    public function pageVariableUpdate(Request $request, Site $site, Menu $menu, Page $page, Variable $variable)
    {
        if ($menu->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        if ($page->menu_id != $menu->id) {
            throw new ModelNotFoundException;
        }
        $variable->page = $page;
        $variable->data = $request->all();

        return response()->json($variable->data);
    }
}
