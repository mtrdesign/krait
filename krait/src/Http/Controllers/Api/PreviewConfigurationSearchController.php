<?php

namespace MtrDesign\Krait\Http\Controllers\Api;


use Illuminate\Http\Request;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;

class PreviewConfigurationSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $table)
    {
        $user = $request->user();
        $previewConfiguration = $user
            ->previewConfigurations()
            ->where('table_name', $table)
            ->first();

        return response()->json([
            'success' => true,
        ]);
    }
}
