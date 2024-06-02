<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\System;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemController extends Controller
{
    // Getters
    public function getBalance()
    {
        $system = System::find(1);
        $balance = $system->balance;
        return view('system.balance', ['balance' => $balance]);
    }

    public function getNormalFee()
    {
        $system = System::find(1);
        $normalFee = $system->normal_fee;
        return view('backend.fee.normal_fee', ['normalFee' => $normalFee]);
    }

    public function getInitialFee()
    {
        $system = System::find(1);
        $initialFee = $system->initial_fee;
        return view('backend.fee.initial_fee', ['initialFee' => $initialFee]);
    }

    public function getWaitingFee()
    {
        $system = System::find(1);
        $waitingFee = $system->waiting_fee;
        return view('backend.fee.waiting_fee', ['waitingFee' => $waitingFee]);
    }

    public function getCommissionFee()
    {
        $system = System::find(1);
        $commissionFee = $system->commission_fee;
        return view('backend.fee.commission_fee', ['commissionFee' => $commissionFee]);
    }
    public function getOrderCommissionFee()
    {
        $system = System::find(1);
        $ordercommissionFee = $system->order_commission_fee;
        return view('backend.fee.commission_fee', ['commissionFee' => $ordercommissionFee]);
    }

    // Updates
    public function updateBalance(Request $request)
    {
        $system = System::find(1);
        $system->balance = $request->input('balance');
        $system->save();
        return redirect()->back()->with('success', 'Balance updated successfully!');
    }

    public function updateNormalFee(Request $request)
    {
        $system = System::find(1);
        $system->normal_fee = $request->input('normalFee');
        $system->save();
        return redirect()->back()->with('success', 'Normal fee updated successfully!');
    }

    public function updateInitialFee(Request $request)
    {
        $system = System::find(1);
        $system->initial_fee = $request->input('initialFee');
        $system->save();
        return redirect()->back()->with('success', 'Initial fee updated successfully!');
    }

    public function updateWaitingFee(Request $request)
    {
        $system = System::find(1);
        $system->waiting_fee = $request->input('waitingFee');
        $system->save();
        return redirect()->back()->with('success', 'Waiting fee updated successfully!');
    }

    public function updateCommissionFee(Request $request)
    {
        $system = System::find(1);
        $system->commission_fee = $request->input('commissionFee');
        $system->save();
        return redirect()->back()->with('success', 'Commission fee updated successfully!');
    }

    public function updateOrderCommissionFee(Request $request)
    {
        $system = System::find(1);
        $system->order_commission_fee = $request->input('commissionFee');
        $system->save();
        return redirect()->back()->with('success', 'Commission fee updated successfully!');
    }
}
