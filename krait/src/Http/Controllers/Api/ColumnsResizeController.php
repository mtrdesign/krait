<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use MtrDesign\Krait\Http\Requests\ColumnsResizeRequest;

class ColumnsResizeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ColumnsResizeRequest $request, string $table): JsonResponse
    {
        $configuration = $this->getPreviewConfiguration($table);

        $config = $record->columns_width ?? [];
        $config[$request->get('name')] = $request->get('width');
        $configuration->update([
            'columns_width' => $config,
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
