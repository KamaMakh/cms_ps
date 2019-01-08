<?php

namespace App\Http\Controllers\API;

use App\Models\Design;
use App\Http\Controllers\Controller;
use App\Models\Template;
use App\ModuleInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VariableController extends Controller
{
    public function index()
    {
        $variables = ModuleInfo::getVariables();
        return $variables;
    }

    public function show(Template $template)
    {
        return $template;
    }

}
