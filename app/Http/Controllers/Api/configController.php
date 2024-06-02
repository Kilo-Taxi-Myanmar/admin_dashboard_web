<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use Illuminate\Http\Request;

class configController extends Controller
{
    public function config(){
       
        $config = Config::query()->select('mapbox_api_key')->first();


        return response()->json($config);
    }

    public function configstore(Request $request){
        $config = new Config();

        $config->mapbox_api_key = $request->configKey;
        $config->save();


        return response()->json($config);
    }
}
