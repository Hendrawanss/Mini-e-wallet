<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BankBalanceHistory extends Model
{
    protected $table = 'balance_bank_history';

    public function getAll() {
        $data = DB::table($this->table)->select('*')->get();
        return $data;
    }

    public function countHistory($bank_id) {
        $jumlah = DB::table($this->table)->where('balance_bank_id', '=', $bank_id)->count();
        return $jumlah;
    }

    public function getById($bank_id) {
        $data = DB::table($this->table)->select('*')->where('balance_bank_id', '=', $bank_id)->get();
        return $data;
    }

    public function store($formData) {
        $affected = DB::table($this->table)->insert($formData);
        return $affected;
    }

    public function deleteDataHistoryByBankId($bank_id) {
        $affected = DB::table($this->table)->where('balance_bank_id', '=', $bank_id)->delete();
        return $affected;
    }
}