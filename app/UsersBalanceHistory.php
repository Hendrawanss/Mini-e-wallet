<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersBalanceHistory extends Model
{
    protected $table = 'user_balance_history';

    public function deleteBalanceHistory($user_id) {
        $affected = DB::table($this->table)->where('user_id', '=', $user_id)->delete();
        return $affected;
    }

    public function store($formData) {
        $affected = DB::table($this->table)->insert($formData);
        return $affected;
    }

    public function getAll() {
        $data = DB::table($this->table)->select('*')->get();
        return $data;
    }

    public function getById($user_id) {
        $data = DB::table($this->table)->select('*')->where('user_id', '=', $user_id)->get();
        return $data;
    }
}