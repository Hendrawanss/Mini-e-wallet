<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Banks;
use App\BankBalanceHistory;


class BankController extends Controller
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

    public function createBank(Request $request) {
        $banks = new Banks();
        $resp = new AuthController();
        $formData = [
            'name' => $request->name,
            'balance' => $request->balance,
            'balance_achieve' => 1,
            'code' => $request->code,
            'enable' => 1,
        ];
        $state = $banks->create($formData);
        if($state == true) {
            return $resp->response('Success', 200, 'Balance bank sukses dibuat, silahkan cek kembali data anda!');
        } else {
            return $resp->response('Failed', 500, 'Balance bank gagal dibuat, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getAllBanks() {
        $banks = new Banks();
        $dataBanks = $banks->getAll();
        return response()->json($dataBanks);
    }

    public function getAllBankBalanceHistory() {
        $bankBalanceHistory = new BankBalanceHistory();
        $dataBank = $bankBalanceHistory->getAll();
        return response()->json($dataBank);
    }

    public function getBankBalanceHistoryById($bank_id) {
        $bankBalanceHistory = new BankBalanceHistory();
        $dataBank = $bankBalanceHistory->getById($bank_id);
        return response()->json($dataBank);
    }

    public function updateBank(Request $request, $id) {
        $banks = new Banks();
        $resp = new AuthController();
        $rowAffected = $banks->updateDataBank($id,$request->input());
        if($rowAffected == 1) {
            return $resp->response('Success', 200, 'Update sukses, silahkan cek kembali data anda!');
        } else {
            return $resp->response('Failed', 500, 'Update gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function deleteBank($id) {
        $banks = new Banks();
        $bankBalanceHistory = new BankBalanceHistory();
        $resp = new AuthController();
        $rowAffected = $banks->deleteDataBank($id);
        if($rowAffected == 1) {
            if($bankBalanceHistory->countHistory($id) == 0) {
                return $resp->response('Success', 200, 'Delete sukses, silahkan cek kembali data anda!');
            } else {
                $rowAffected = $bankBalanceHistory->deleteDataHistoryByBankId($id);
                if($rowAffected >= 1) {
                    return $resp->response('Success', 200, 'Delete sukses, silahkan cek kembali data anda!');
                } else {
                    return $resp->response('Failed', 500, 'Delete history gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
                }
            }
        } else {
            return $resp->response('Failed', 500, 'Delete gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }
    //
}
