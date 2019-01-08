<?php

namespace App\Http\Controllers\API\Design;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Variable;
use App\Modules\Kernel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VariableController extends Controller
{
    public function index(Design $design)
    {
        return response()->json($design->variables);
    }

    public function show(Design $design, Variable $variable)
    {
        if (!$variable->designs()->where('design_id', '=', $design->id)->first()) {
            throw new ModelNotFoundException;
        }
        return response()->json($variable);
    }

    public function store(Design $design, Request $request)
    {
        $data = $request->all();
        $variable = Variable::create($data);

        $design->variables()->save($variable);

        return response()->json($variable, 201);
    }

    public function update(Design $design, Variable $variable, Request $request)
    {
        if (!$variable->designs()->where('design_id', '=', $design->id)->first()) {
            throw new ModelNotFoundException;
        }
        $variable->update($request->all());

        return response()->json($variable);
    }

    public function delete(Design $design, Variable $variable)
    {
        if (!$variable->designs()->where('design_id', '=', $design->id)->first()) {
            throw new ModelNotFoundException;
        }
        $variable->designs()->detach($design->id);
        if (!$variable->designs->count()) {
            $variable->delete();
        }

        return response()->json(null, 204);
    }

    public function types(Design $design)
    {
        $kernel = new Kernel;
        $value = [];
        foreach ($kernel->modules as $module) {
            $class = new \ReflectionClass($module);
            $methods = $class->getMethods(\ReflectionMethod::IS_PUBLIC);
            $type_parts = explode('\\', $module);
            $type = $type_parts[count($type_parts) - 1];
            $type = preg_replace("/Module$/", "", $type);
            $m = [
                "id" => $type,
                "text" => $type,
                "value" => $type,
                "methods" => [],
            ];
            foreach ($methods as $method) {
                if (strpos($method->name, "var") === 0) {
                    $name = preg_replace("/^var/", "", $method->name);
                    $m["methods"][] = [
                        "id" => $type . "." .$name,
                        "text" => $name,
                        "value" => $name,
                    ];
                }
            }
            $value[] = $m;
        }
        return $value;
    }
}
