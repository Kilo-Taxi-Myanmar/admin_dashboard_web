<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\System;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // get the authenticated user
        $transactions = Transaction::where('user_id', $user->id)->where('income_outcome', 'income')->get(); 
        
        $transactions = $transactions->transform(function ($transaction) {
            $transaction->staff_name = User::where('id', $transaction->staff_id)->value('name');
            return $transaction;
        });
        return response()->json($transactions);
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);
        return response()->json($transaction);
    }
}
