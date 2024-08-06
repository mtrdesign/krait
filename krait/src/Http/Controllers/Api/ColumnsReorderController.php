<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use MtrDesign\Krait\Http\Requests\ColumnsReorderRequest;

class ColumnsReorderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ColumnsReorderRequest $request, string $table): JsonResponse
    {
        $table = urldecode($table);
        $configuration = $this->getPreviewConfiguration($table);
        $configuration->update([
            'columns_order' => $request->get('columns'),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
