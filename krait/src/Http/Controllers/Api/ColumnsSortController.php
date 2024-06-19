<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use MtrDesign\Krait\Http\Requests\ColumnsSortRequest;

class ColumnsSortController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ColumnsSortRequest $request, string $table): JsonResponse
    {
        $configuration = $this->getPreviewConfiguration($table);
        $configuration->update([
            'sort_column' => $request->get('name'),
            'sort_direction' => $request->get('direction'),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
