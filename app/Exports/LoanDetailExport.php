<?php

namespace App\Exports;

use App\Models\Loan;
use App\Models\User;
use App\Repositories\LoanRepository;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanDetailExport implements FromArray, WithHeadings
{

    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }



    public function array(): array
    {
        $data =  LoanRepository::detail($this->user);
        $loan = $data['loan']->toArray();
        $payment = $data['loanPayment']->toArray();

        $compact = collect(array_merge($loan,$payment));
        $result =collect();
        $compact->map(function ($record) use($result) {
            $record = collect($record);
            if($record->has('salary_recap_id')){
                $record['amount'] = "-".$record['amount'];
                $merge = $record->merge(['type'=>'Bayar']);
            }else{
                $merge = $record->merge(['type'=>'Kasbon']);
            }
            $result->push($merge->only(['date','type','amount'])->all());
        });
        return $result->sortBy('date')->toArray();
    }


    public function headings(): array
    {
        return [
            'Jumlah',
            'Tanggal',
            'Tipe Transaksi',
        ];
    }
}
