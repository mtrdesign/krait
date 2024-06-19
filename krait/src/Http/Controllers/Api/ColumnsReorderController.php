<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use MtrDesign\Krait\Http\Requests\ColumnsReorderRequest;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;

class ColumnsReorderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ColumnsReorderRequest $request, KraitPreviewConfiguration $record): JsonResponse
    {
        $record->update([
            "columns_order" => $request->get('columns')
        ]);

        return response()->json([
            "success" => True,
        ]);
    }
}
