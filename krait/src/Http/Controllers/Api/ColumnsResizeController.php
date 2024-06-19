<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use MtrDesign\Krait\Http\Requests\ColumnsResizeRequest;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;

class ColumnsResizeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ColumnsResizeRequest $request, KraitPreviewConfiguration $record)
    {
        $config = $record->columns_width ?? [];
        $config[$request->get('name')] = $request->get('width');

        $record->columns_width = $config;
        return response()->json([
            'success' => true,
        ]);
    }
}
