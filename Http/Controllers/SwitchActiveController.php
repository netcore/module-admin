<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

class SwitchActiveController extends Controller
{
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchActive()
    {
        $model = request('model');
        $id = request('id');
        $attribute = request('attribute', 'is_active');

        if (!$model || !$id || !$attribute) {
            return response()->json([
                'state' => 'error'
            ], 400);
        }

        $instance = $model::findOrFail($id);

        $instance->update([
            $attribute => ! $instance->$attribute
        ]);

        return response()->json([
            'state' => 'success'
        ]);
    }
}
