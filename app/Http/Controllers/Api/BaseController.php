<?php

namespace App\Http\Controllers\Api;

use App\Formatters\ApiOutput;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class BaseController extends Controller
{
    protected $apiOutput;

    public function __construct()
    {
        //App::setLocale('zh-tw');
        $this->apiOutput = app(ApiOutput::class);
    }
}
