<?php

namespace App\Http\Controllers\API;

use App\Models\Design;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesignController extends Controller
{
    public function index()
    {
        return Design::all();
    }

    public function show(Design $design)
    {
        $design->load([
            'variables',
        ]);
        return $design;
    }

    public function store(Request $request)
    {
        $design = Design::create($request->all());

        return response()->json($design, 201);
    }

    public function update(Request $request, Design $design)
    {
        Log::error($request->all());
        $design->update($request->all());

        return response()->json($design);
    }

    public function delete(Design $design)
    {
        $design->delete();

        return response()->json(null, 204);
    }
}
