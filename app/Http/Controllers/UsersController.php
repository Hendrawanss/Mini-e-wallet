<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Users;
use App\UsersBalanceHistory;
use App\Banks;
use App\BankBalanceHistory;
use App\UsersBalance;


class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getUserById(Request $request) {
        $users = new Users();
        $resp = new AuthController();
        $dataUser = $users->getById($request->id);
        if($dataUser) {
            return $resp->response('Success', 200, $dataUser);
        } else {
            return $resp->response('Failed', 500, 'Gagal mengambil data user, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getUserBalanceHistoryById($user_id) {
        $usersBalanceHistory = new UsersBalanceHistory();
        $resp = new AuthController();
        $data = $usersBalanceHistory->getById($user_id);
        if($data) {
            return $resp->response('Success', 200, $data);
        } else {
            return $resp->response('Failed', 500, 'Gagal mengambil data history user, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getAllUserBalanceHistory() {
        $usersBalanceHistory = new UsersBalanceHistory();
        $resp = new AuthController();
        $data = $usersBalanceHistory->getALl();
        if($data) {
            return $resp->response('Success', 200, $data);
        } else {
            return $resp->response('Failed', 500, 'Gagal mengambil data history user, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function createUsers(Request $request) {
        $users = new Users();
        $userBalance = new UsersBalance();
        $resp = new AuthController();
        $formData = [
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'key' => Hash::make(Str::random(10))
        ];
        $id = $users->create($formData);
        if($id) {
            $formBalance = [
                'user_id' => $id,
                'balance' => 0,
            ];
            $rowAffected = $userBalance->create($formBalance);
            if($rowAffected == true){
                return $resp->response('Success', 200, 'Sukses membuat user, silahkan cek kembali data anda!');
            } else {
                return $resp->response('Failed', 500, 'Gagal membuat balance user, terdapat kesalahan teknis hubungi pihak developer segera!');
            }
        } else {
            return $resp->response('Failed', 500, 'Gagal membuat user, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getAllUsers() {
        $users = new Users();
        $resp = new AuthController();
        $dataUsers = $users->getAll();
        if($dataUsers) {
            return $resp->response('Success', 200, $dataUsers);
        } else {
            return $resp->response('Failed', 500, 'Gagal mengambil data user, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function updateUser(Request $request, $id) {
        $users = new Users();
        $resp = new AuthController();
        $formData = $request->input();
        if($formData['password']) {
            $formData['password'] = Hash::make($formData['password']);
        }
        $rowAffected = $users->updateDataUser($id,$formData);
        if($rowAffected == 1) {
            return $resp->response('Success', 200, 'Update data sukses, silahkan cek kembali data anda!');
        } else {
            return $resp->response('Failed', 500, 'Update gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function deleteUser($id) {
        $users = new Users();
        $userBalance = new UsersBalance();
        $userBalanceHistory = new UsersBalanceHistory();
        $resp = new AuthController();
        $rowAffected = $users->deleteDataUser($id);
        if($rowAffected == 1) {
            $rowAffected = $userBalance->deleteBalance($id);
            if($rowAffected == 1) {
                if($userBalanceHistory->countHistory($id) == 0) {
                    return $resp->response('Success', 200, 'Delete sukses, silahkan cek kembali data anda!');
                } else {
                    $rowAffected = $userBalanceHistory->deleteBalanceHistory($id);
                    if($rowAffected >= 1) {
                        return $resp->response('Success', 200, 'Delete sukses, silahkan cek kembali data anda!');
                    } else {
                        return $resp->response('Failed', 500, 'Delete balance history gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
                    }
                }
            } else {
                return $resp->response('Failed', 500, 'Delete balance gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
            }
        } else {
            return $resp->response('Failed', 500, 'Delete gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function updateUserBalanceHistory(Request $request, $id) {
        $usersBalanceHistory = new UsersBalanceHistory();
        $resp = new AuthController();
        $rowAffected = $usersBalanceHistory->updateDataUserBalanceHistory($id,$request->input());
        if($rowAffected == 1) {
            return $resp->response('Success', 200, 'Update sukses, silahkan cek kembali data anda!');
        } else {
            return $resp->response('Failed', 500, 'Update gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function deleteUserBalanceHistory($id) {
        $usersBalanceHistory = new UsersBalanceHistory();
        $resp = new AuthController();
        $rowAffected = $usersBalanceHistory->deleteDataUserBalanceHistory($id);
        if($rowAffected == 1) {
            return $resp->response('Success', 200, 'Delete sukses, silahkan cek kembali data anda!');
        } else {
            return $resp->response('Failed', 500, 'Delete gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function checkBalance($jenis, $code = null, $key = null, $nominal) {
        $state = 'null';
        switch($jenis) {
            case 'Bank':
                // Untuk Pengecekan Topup antar user
                $banks = new Banks();
                $state = $banks->checkBalance($code,$nominal);
                break;
            case 'User':
                // Untuk Pengecekan Transfer antar user
                $usersBalance = new UsersBalance();
                $state = $usersBalance->checkBalance($key,$nominal);
                break;
        }
        return $state;
    }

    public function topup(Request $request) {
        $codeBank = $request->bank;
        $key = $request->header('Authorization');
        $userAgent = $request->header('User-Agent');
        $nominal = $request->nominal;
        $type = $request->type;
        $loc = $request->location;


        $resp = new AuthController();
        $usersBalanceHistory = new UsersBalanceHistory();
        $banks = new Banks();
        $banksBalanceHistory = new BankBalanceHistory();
        $userBalance = new UsersBalance();
        
        // Pengecekan Status Bank
        $is_enable = $banks->is_enabled($codeBank);
        if($is_enable == 0) {
            return $resp->response('Failed', 405, 'Bank yang anda pilih saat ini sedang tidak melayani topup, silahkan gunakan jasa lain!');
        } else if($is_enable == 0) {
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses pengecekan availablelity bank');
        }

        // Pengecekan Saldo Bank
        $state = $this->checkBalance('Bank', $codeBank, null, $nominal);
        if($state == 'Saldo Kurang') {
            return $resp->response('Failed', 405, 'Saldo Bank yang anda pilih kurang untuk melayani nominal topup anda!');
        }

        // Transaction Begin
        DB::beginTransaction();

        // Pengurangan Saldo Bank
        $state = $banks->balanceReduction($codeBank,$nominal);
        if($state['status'] == 'Failed') {
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses pengurangan Saldo Bank, Data di Rollback!');
        }
        
        // Pencatatan History Bank
        $dataHistoryBank = [
            'balance_bank_id' => $state['id'],
            'balance_before' => $state['balanceBefore'],
            'balance_after' => $state['balanceAfter'],
            'activity' => 'Topup',
            'type' => $type,
            'ip' => $request->ip(),
            'location' => $loc,
            'user_agent' => $userAgent,
            'author' => $key
        ];

        $state = $banksBalanceHistory->store($dataHistoryBank);
        if(!$state){
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses penyimpanan History Bank, Data di Rollback!');
        } 

        // Penambahan Balance pada User
        $state = $userBalance->additionBalance($key,$nominal);
        if($state['status'] == 'Failed') {
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses penambahan Saldo User, Data di Rollback!');
        }

        // Pencatatan History User
        $dataHistoryUser = [
            'user_id' => $state['id'],
            'balance_before' => $state['balanceBefore'],
            'balance_after' => $state['balanceAfter'],
            'activity' => 'Topup',
            'type' => $type,
            'ip' => $request->ip(),
            'location' => $loc,
            'user_agent' => $userAgent,
            'author' => $key
        ];

        $state = $usersBalanceHistory->store($dataHistoryUser);
        if(!$state){
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses penyimpanan History User, Data di Rollback!');
        }

        DB::commit();
        return $resp->response('Success', 200, 'Proses topup anda berhasil, silahkan cek saldo anda kembali.');
    }

    public function transferBetweenUser(Request $request) {
        $idDestinationUser = $request->id;
        $key = $request->header('Authorization');
        $userAgent = $request->header('User-Agent');
        $nominal = $request->nominal;
        $type = $request->type;
        $loc = $request->location;

        $resp = new AuthController();
        $usersBalanceHistory = new UsersBalanceHistory();
        $userBalance = new UsersBalance();
        $users = new Users();

        // Pengecekan Saldo User Pengirim
        $state = $this->checkBalance('User', null, $key, $nominal);
        if($state == 'Saldo Kurang') {
            return $resp->response('Failed', 405, 'Saldo anda kurang untuk melakukan transfer, isi saldo anda dan lanjutkan transaksi!');
        }

        // Transaction Begin
        DB::beginTransaction();

        // Pengurangan Saldo User Pengirim
        $state = $userBalance->balanceReduction($key,$nominal);
        if($state['status'] == 'Failed') {
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses pengurangan Saldo Pengirim, Data di Rollback!');
        }
        
        // Pencatatan History User Pengirim
        $dataHistoryUser = [
            'user_id' => $state['id'],
            'balance_before' => $state['balanceBefore'],
            'balance_after' => $state['balanceAfter'],
            'activity' => 'Transfer',
            'type' => $type,
            'ip' => $request->ip(),
            'location' => $loc,
            'user_agent' => $userAgent,
            'author' => $key
        ];

        $state = $usersBalanceHistory->store($dataHistoryUser);
        if(!$state){
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses penyimpanan History User Pengirim, Data di Rollback!');
        } 

        // Get Key User Penerima
        $keyDestination = $users->getUserKey($idDestinationUser);
        if(!$keyDestination){
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses pengambilan Key User Pengirim, Data di Rollback!');
        } 

        // Penambahan Balance pada User Penerima
        $state = $userBalance->additionBalance($keyDestination,$nominal);
        if($state['status'] == 'Failed') {
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses penambahan Saldo User Penerima, Data di Rollback!');
        }

        // Pencatatan History User
        $dataHistoryUser = [
            'user_id' => $state['id'],
            'balance_before' => $state['balanceBefore'],
            'balance_after' => $state['balanceAfter'],
            'activity' => 'Transfer',
            'type' => $type,
            'ip' => $request->ip(),
            'location' => $loc,
            'user_agent' => $userAgent,
            'author' => $key
        ];

        $state = $usersBalanceHistory->store($dataHistoryUser);
        if(!$state){
            DB::rollBack();
            return $resp->response('Failed', 500, 'Terjadi masalah dalam proses penyimpanan History User Penerima, Data di Rollback!');
        }

        DB::commit();
        return $resp->response('Success', 200, 'Proses Transfer anda berhasil, silahkan cek saldo anda kembali.');
    }
    //
}
