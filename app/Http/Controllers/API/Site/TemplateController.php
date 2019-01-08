<?php

namespace App\Http\Controllers\API\Site;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Site;
use App\Models\Template;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    public function pages(Site $site)
    {
        $templates = Template::where('is_page', 1)
            ->select([
                'id',
                'name'
            ])
            ->get();
        return response()->json($templates);
    }
}
