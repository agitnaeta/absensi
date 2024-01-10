<?php

namespace App\Repositories;

use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;

class LoanRepository
{
    public static function recap()
    {
        $recap  = User::select('id', 'name')
            ->selectSub(function ($query) {
                $query->selectRaw('SUM(amount)')
                    ->from('loans')
                    ->whereColumn('user_id', 'users.id');
            }, 'kasbon')
            ->selectSub(function ($query) {
                $query->selectRaw('SUM(amount)')
                    ->from('loan_payments')
                    ->whereColumn('user_id', 'users.id');
            }, 'terbayar')
            ->selectRaw('(SELECT SUM(amount) FROM loans WHERE user_id = users.id) - (SELECT SUM(amount) FROM loan_payments WHERE user_id = users.id) AS selisih')
            ->get();

        return $recap;
    }

    public static function detail(User $user){
        $loan = Loan::where('user_id',$user->id)->get();
        $loanPayment = LoanPayment::where('user_id',$user->id)->get();
        $total = $loan->sum('amount') - $loanPayment->sum('amount');
        return compact('loan','loanPayment','total');
    }
}
