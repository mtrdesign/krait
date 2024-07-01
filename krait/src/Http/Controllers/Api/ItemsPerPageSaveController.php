<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use MtrDesign\Krait\Http\Requests\ItemsPerPageSaveRequest;

class ItemsPerPageSaveController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ItemsPerPageSaveRequest $request, string $table): JsonResponse
    {
        $configuration = $this->getPreviewConfiguration($table);
        $configuration->update([
            'items_per_page' => $request->get('items_per_page'),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }
}
