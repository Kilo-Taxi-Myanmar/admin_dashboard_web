<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class IncomeSummaryController extends Controller
{
    public function incomeSummary($type)
    {
        $incomes = Transaction::all();

        $totalAmount = $incomes->sum('amount');

        if ($type == 'month') {
            $collection = collect($incomes);
            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('Y-m');
            })->map(function ($group) {
                $comission = $group->reduce(function ($carry, $item) {
                    return $carry + $item->where('income_outcome', 'outcome')->first()->amount;
                }, 0);
                $topup = $group->reduce(function ($carry, $item) {
                    return $carry + $item->where('income_outcome', 'income')->first()->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('F, Y'),
                    'commission' => $comission,
                    'topup' => $topup
                ];
            });
        } elseif ($type == 'year') {
            $collection = collect($incomes);
            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('Y');
            })->map(function ($group) {
                $comission = $group->reduce(function ($carry, $item) {
                    return $carry + $item->where('income_outcome', 'outcome')->first()->amount;
                }, 0);
                $topup = $group->reduce(function ($carry, $item) {
                    return $carry + $item->where('income_outcome', 'income')->first()->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('Y'),
                    'commission' => $comission,
                    'topup' => $topup
                ];
            });
        } else {
            $collection = collect($incomes);
            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('Y-m-d');
            })->map(function ($group) {
                $comission = $group->reduce(function ($carry, $item) {
                    return $carry + $item->where('income_outcome', 'outcome')->first()->amount;
                }, 0);
                $topup = $group->reduce(function ($carry, $item) {
                    return $carry + $item->where('income_outcome', 'income')->first()->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('F j, Y'),
                    'commission' => $comission,
                    'topup' => $topup
                ];
            });
        }

        $perPage = 25;
        $currentPage = request()->query('page', 1);
        $pagedIncomes = new LengthAwarePaginator(
            $incomes->forPage($currentPage, $perPage),
            $incomes->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url()]
        );

        $totalUsers = User::role('user')->count();
        $totalTrips = Trip::count();
        return view('backend.incomeSummary', ['incomes' => $pagedIncomes, 'totalIncome' => $totalAmount, 'totalUsers' => $totalUsers, 'totalTrips' => $totalTrips]);
    }


}
