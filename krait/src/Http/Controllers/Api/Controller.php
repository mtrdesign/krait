<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Routing\Controller as BaseController;
use MtrDesign\Krait\Http\Middlewares\KraitApi;
use MtrDesign\Krait\Models\KraitPreviewConfiguration;

abstract class Controller extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(KraitApi::class);
    }

    protected function getPreviewConfiguration(string $tableName): KraitPreviewConfiguration
    {
        $user = request()->user();

        return KraitPreviewConfiguration::firstOrCreate([
            'table_name' => $tableName,
            'user_id' => $user->id,
        ]);
    }
}
