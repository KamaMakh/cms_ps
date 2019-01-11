<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 10.01.19
 * Time: 15:37
 */

namespace App\Http\Controllers\API\Site;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Page;
use App\Models\Site;
use App\Models\Variable;
use App\Modules\Menu\Models\Menu;
use App\Modules\Image\Models\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Site $site)
    {
        return response()->json($site->m_images);
    }

    public function show(Site $site, Image $image)
    {
        if ($image->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
    }

    public function store(Site $site,Request $request)
    {
//        $data = $request->all();
//        $data['site_id'] = $site->id;
//        $image = Image::create($data);
//
//        return response()->json($image, 201);

        $directories = Storage::allDirectories("/");
        info($directories);
    }

    public function update(Site $site, Menu $menu, Request $request)
    {

    }

    public function delete(Site $site, Image $image)
    {
        if ($image->site_id != $site->id) {
            throw new ModelNotFoundException;
        }
        if ($image->image_id != $image->id) {
            throw new ModelNotFoundException;
        }
        $image->delete();

        return response()->json(null, 204);
    }

    public function pageStore(Site $site, Menu $menu, Request $request)
    {

    }

    public function pageShow(Site $site, Menu $menu, Page $page)
    {

    }

    public function pageUpdate(Request $request, Site $site, Menu $menu, Page $page)
    {

    }

    public function pageDelete(Site $site, Menu $menu, Page $page)
    {

    }

    public function pageVariables(Request $request, Site $site, Menu $menu, Page $page)
    {

    }


    public function pageVariableShow(Site $site, Menu $menu, Page $page, Variable $variable)
    {

    }

    public function pageVariableUpdate(Request $request, Site $site, Menu $menu, Page $page, Variable $variable)
    {

    }

}