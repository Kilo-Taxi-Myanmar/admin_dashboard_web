<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\System;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    public function getFee()
    {
        $system = System::findOrFail(1);

        return response()->json($system);
    }
    public function getNormalFee()
    {
        $system = System::find(1);
        return response()->json([
            'normal_fee' => $system->normal_fee,
            

        ]);
    }
    public function getInitialFee()
    {
        $system = System::find(1);
        return response()->json([
            'initial_fee' => $system->initial_fee,
        ]);
    }

    public function getCommissionFee()
    {
        $system = System::find(1);
        return response()->json([
            'commission_fee' => $system->commission_fee,
        ]);
    }

    public function getWaitingFee()
    {
        $system = System::find(1);
        return response()->json([
            'waiting_fee' => $system->waiting_fee,
        ]);
    }
}
