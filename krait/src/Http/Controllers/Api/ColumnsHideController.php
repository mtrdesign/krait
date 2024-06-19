<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use MtrDesign\Krait\Http\Requests\ColumnsHideRequest;

class ColumnsHideController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ColumnsHideRequest $request, string $table): JsonResponse
    {
        $configuration = $this->getPreviewConfiguration($table);
        $configuration->update([
            "visible_columns" => $request->get('visible_columns')
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
