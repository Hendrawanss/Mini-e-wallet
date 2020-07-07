<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Users extends Model
{
    protected $table = 'users';
    protected $tableUserBalance = 'user_balance';

    public function is_exist($username, $password) {
        $datauser = DB::table($this->table)->select('id','key','is_login','password')
                        ->where('username', '=', $username)
                        ->first();
        if($datauser) {
            if(Hash::check($password,$datauser->password)) {
                if($datauser->is_login == 1) {
                    return 'Sudah login';
                } else {
                    return $datauser;
                }
            } else {
                return 'Password tidak ada';
            }
        } else {
            return 'Username tidak ada';
        }
    }

    public function create($formData) {
        $id = DB::table($this->table)->insertGetId($formData);
        return $id;
    }

    public function getById($id) {
        $data = DB::table($this->table)->select('id', 'username', 'email','role','is_login')
                        ->where('id', '=', $id)
                        ->first(); 
        return $data;
    }

    public function getUserKey($id) {
        $data = DB::table($this->table)->select('key')
                        ->where('id', '=', $id)
                        ->first(); 
        return $data->key;
    }

    public function is_login($key) {
        $data = DB::table($this->table)->select('is_login')
                        ->where('key', '=', $key)
                        ->first();
        $state = $data == NULL ? 0 : $data->is_login; 
        return $state;
    }
    
    public function is_admin($key) {
        $data = DB::table($this->table)->select('role')
                        ->where('key', '=', $key)
                        ->first(); 
        return $data->role;
    }

    public function make_login($id) {
        $affected = DB::table($this->table)->where('id', '=', $id)
                        ->update(['is_login' => 1]);
        return $affected;
    }

    public function make_logout($key) {
        $affected = DB::table($this->table)->where('key','=', $key)
                        ->update(['is_login' => 0]);
        return $affected;
    }

    public function getAll() {
        $data = DB::table($this->table)
                        ->join($this->tableUserBalance, $this->table.'.id', '=', $this->tableUserBalance.'.user_id')
                        ->select($this->table.'.id','username','email','balance','balance_achieve')
                        ->get();
        return $data;
    }

    public function updateDataUser($id, $formData) {
        $affected = DB::table($this->table)->where('id','=', $id)
                        ->update($formData);
        return $affected;
    }

    public function deleteDataUser($id) {
        $affected = DB::table($this->table)->where('id', '=', $id)->delete();
        return $affected;
    }
}