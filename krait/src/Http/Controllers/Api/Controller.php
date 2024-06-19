<?php

namespace MtrDesign\Krait\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;
use MtrDesign\Krait\Http\Middlewares\KraitApi;

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
}
