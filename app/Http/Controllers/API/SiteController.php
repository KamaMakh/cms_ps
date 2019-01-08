<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        return Site::all();
    }

    public function show(Site $site)
    {
        $site->load([
            'menus'
        ]);
        return $site;
    }

    public function store(Request $request)
    {
        $site = Site::create($request->all());

        return response()->json($site, 201);
    }

    public function update(Request $request, Site $site)
    {
        $site->update($request->all());

        return response()->json($site);
    }

    public function delete(Site $site)
    {
        $site->delete();

        return response()->json(null, 204);
    }
}
