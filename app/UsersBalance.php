<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UsersBalance extends Model
{
    protected $table = 'user_balance';
    protected $tableUsers = 'users';

    public function create($formBalance){
        $affected = DB::table($this->table)->insert($formBalance);
        return $affected;
    }

    public function deleteBalance($user_id) {
        $affected = DB::table($this->table)->where('user_id', '=', $user_id)->delete();
        return $affected;
    }

    public function checkBalance($key, $nominal){
        $data = DB::table($this->table)
                        ->join($this->tableUsers, $this->table.'.user_id', '=', $this->tableUsers.'.id')
                        ->select('balance')
                        ->where('key', '=', $key)
                        ->first();
        $state = $data->balance > $nominal ? 'Saldo Cukup' : 'Saldo Kurang';
        return $state;
    }

    public function additionBalance($key,$nominal) {
        $user = DB::table($this->tableUsers)
                    ->select('id')
                    ->where('key', '=', $key)
                    ->first();
        
        $data = DB::table($this->table)
                    ->select('id', 'user_id', 'balance')
                    ->where('user_id', '=', $user->id)
                    ->first();

        $currentBalance = $data->balance+$nominal;

        $affected = DB::table($this->table)->where('id','=', $data->id)
                        ->update(['balance' => $currentBalance]);
        
        if($affected == 1) {
            $response = [
                'status' => 'Success',
                'id' => $data->user_id,
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

    public function balanceReduction($key, $nominal) {
        $data = DB::table($this->table)
                        ->join($this->tableUsers, $this->table.'.user_id', '=', $this->tableUsers.'.id')
                        ->select($this->table.'.id','user_id','balance')
                        ->where('key', '=', $key)
                        ->first();
        
        $currentBalance = $data->balance - $nominal;

        $affected = DB::table($this->table)->where('id','=', $data->id)
                        ->update(['balance' => $currentBalance]);
        
        if($affected == 1) {
            $response = [
                'status' => 'Success',
                'id' => $data->user_id,
                'balanceBefore' => $data->balance,
                'balanceAfter' => $currentBalance
            ];
        } else {
            $response = [
                'status' => 'Failed',
                'balanceBefore' => null,
                'balanceAfter' => null
            ];
        }
        return $response;
    }
}