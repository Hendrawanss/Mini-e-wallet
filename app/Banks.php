<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Banks extends Model
{
    protected $table = 'balance_bank';
    protected $tableBalanceBankHistory = 'balance_bank_history';

    public function getAll() {
        $data = DB::table($this->table)
                        ->select('id','code','balance','balance_achieve')
                        ->where('enable', '=', true)
                        ->get();
        return $data;
    }

    public function getById($bank_id) {
        $data = DB::table($this->table)
                        ->select('*')
                        ->where('id', '=', $bank_id)
                        ->first();
        return $data;
    }

    public function create($formData) {
        return DB:: table($this->table)->insert($formData);
    }

    public function getBalance($code) {
        $data = DB::table($this->table)
                        ->select('balance')
                        ->where('code', '=', $code)
                        ->first();
        return $data;
    }

    public function is_enabled($code) {
        $data = DB::table($this->table)
                        ->select('enable')
                        ->where('code', '=', $code)
                        ->first();
        $state = $data->enable == null ? null : $data->enable;
        return $state;
    }

    public function updateDataBank($id, $formData) {
        $affected = DB::table($this->table)->where('id','=', $id)
                        ->update($formData);
        return $affected;
    }

    public function deleteDataBank($id) {
        $affected = DB::table($this->table)->where('id', '=', $id)->delete();
        return $affected;
    }

    public function checkBalance($code, $nominal){
        $data = DB::table($this->table)
                        ->select('balance')
                        ->where('code', '=', $code)
                        ->first();
        $state = $data->balance > $nominal ? 'Saldo Cukup' : 'Saldo Kurang';
        return $state;
    }

    public function balanceReduction($code, $nominal) {
        $data = DB::table($this->table)
                    ->select('id','balance')
                    ->where('code', '=', $code)
                    ->first();
        
        $currentBalance = $data->balance-$nominal;

        $affected = DB::table($this->table)->where('id','=', $data->id)
                        ->update(['balance' => $currentBalance]);
        
        if($affected == 1) {
            $response = [
                'status' => 'Success',
                'id' => $data->id,
                'balanceBefore' => $data->balance,
                'balanceAfter' => $currentBalance
            ];
        } else {
            $response = [
                'status' => 'Failed',
                'id' => null,
                'balanceBefore' => null,
                'balanceAfter' => null
            ];
        }
        return $response;
    }
}