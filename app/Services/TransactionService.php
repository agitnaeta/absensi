<?php

namespace App\Services;

use App\Models\LoanPayment;
use App\Models\SalaryRecap;
use App\Models\User;
use App\Services\Acc\Acc;
use App\Services\Acc\AccTransaction;
use App\Services\Acc\AccTransactionType;
use Carbon\Carbon;

class TransactionService
{

    protected  $acc;
    protected  $accTransaction;
    public function __construct(Acc $acc, AccTransaction $accTransaction) {
        $this->acc = $acc;
        $this->accTransaction = $accTransaction;
    }

    public function recordSalaryToACC(SalaryRecap $data): void
    {
        $code = "GAJIAN";
        $user = User::find($data->user_id);
        $acc  = \App\Models\Acc::where("code",$code)->first();
        $transaction = $this->accTransaction;
        $transaction->type = AccTransactionType::WITHDRAWAL;
        $transaction->amount = $data->received;
        $transaction->date = $data->updated_at;
        $transaction->description = "$code $user->name";
        $transaction->source_id = $acc->source_id;
        $transaction->destination_id = $acc->destination_id;
        $transaction->tags = $code;
        $transaction->notes = $data->method;
        $transaction->internal_reference = "ABSEN-$code-".$data->id;
        $transaction->external_id =$data->id;

        // Save To ACC
        $record = $this->acc->withdraw($transaction);

        // save transaction id to database
        $data->acc_id = $record->data->id;
        $data->saveQuietly();
    }


    public function recordPayLoanACC(LoanPayment $data): void
    {
        $time = Carbon::now()->format("H:i:s");
        $code = "BAYARKASBON";
        $user = User::find($data->user_id);
        $acc  = \App\Models\Acc::where("code",$code)->first();
        $transaction = $this->accTransaction;
        $transaction->type = AccTransactionType::DEPOSIT;
        $transaction->amount = $data->amount;
        $transaction->date = $data->date." ".$time;
        $transaction->description = "$code $user->name";
        $transaction->source_id = $acc->source_id;
        $transaction->destination_id = $acc->destination_id;
        $transaction->tags = $code;
        $transaction->notes = $code;
        $transaction->internal_reference = "ABSEN-$code-".$data->id;
        $transaction->external_id =$data->id;

        // Save To ACC
        $record = $this->acc->deposit($transaction);

        // save transaction id to database
        $data->acc_id = $record->data->id;
        $data->saveQuietly();
    }

    public function updateRecordPayLoanACC(LoanPayment $data): void
    {
        if($data->acc_id == null){
            $this->recordPayLoanACC($data);
        }

        else{
            $code = "BAYARKASBON";
            $time = Carbon::now()->format("H:i:s");
            $user = User::find($data->user_id);

            $transaction = $this->accTransaction;
            $transaction->amount = $data->amount;
            $transaction->description = "$code $user->name";
            $transaction->date = $data->date." ".$time;

            $this->acc->updateTransaction($data->acc_id,$transaction);
        }

    }

    public function deleteRecordPayLoanAcc(LoanPayment $data)
    {
        if($data->acc_id){
            $this->acc->delete($data->acc_id);
        }
    }
}
