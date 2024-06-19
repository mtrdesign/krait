<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use MtrDesign\Krait\Http\Requests\ColumnsHideRequest;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;

class ColumnsHideController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ColumnsHideRequest $request, KraitPreviewConfiguration $record)
    {
        $record->update([
            "visible_columns" => $request->get('visible_columns')
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
