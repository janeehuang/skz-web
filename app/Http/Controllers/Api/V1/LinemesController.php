<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use App\Services\Api\V1\LinemesService;
use Illuminate\Http\Request;

class LinemesController extends Controller
{
    //
    public function webhook(Request $request, LinemesService $linemesService){
        return $linemesService->webhook($request);
    }
}

  