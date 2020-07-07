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
        $this->banks = new Banks();
        $this->bankBalanceHistory = new BankBalanceHistory();
        $this->resp = new AuthController();
    }

    public function createBank(Request $request) {
        $formData = [
            'name' => $request->name,
            'balance' => $request->balance,
            'balance_achieve' => 1,
            'code' => $request->code,
            'enable' => 1,
        ];
        $state = $this->banks->create($formData);
        if($state == true) {
            return $this->resp->response('Success', 200, 'Balance bank sukses dibuat, silahkan cek kembali data anda!');
        } else {
            return $this->resp->response('Failed', 500, 'Balance bank gagal dibuat, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getBankById($bank_id) {
        $dataBank = $this->banks->getById($bank_id);
        if($dataBank) {
            return $this->resp->response('Success', 200, $dataBank);
        } else {
            return $this->resp->response('Failed', 500, 'Gagal mengambil data bank, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getAllBanks() {
        $dataBanks = $this->banks->getAll();
        if($dataBanks) {
            return $this->resp->response('Success', 200, $dataBanks);
        } else {
            return $this->resp->response('Failed', 500, 'Gagal mengambil data bank, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getAllBankBalanceHistory() {
        $dataBank = $this->bankBalanceHistory->getAll();
        if($dataBank) {
            return $this->resp->response('Success', 200, $dataBank);
        } else {
            return $this->resp->response('Failed', 500, 'Gagal mengambil data history bank, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function getBankBalanceHistoryById($bank_id) {
        $dataBank = $this->bankBalanceHistory->getById($bank_id);
        if($dataBank) {
            return $this->resp->response('Success', 200, $dataBank);
        } else {
            return $this->resp->response('Failed', 500, 'Gagal mengambil data history bank, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function updateBank(Request $request, $id) {
        $rowAffected = $this->banks->updateDataBank($id,$request->input());
        if($rowAffected == 1) {
            return $this->resp->response('Success', 200, 'Update sukses, silahkan cek kembali data anda!');
        } else {
            return $this->resp->response('Failed', 500, 'Update gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }

    public function deleteBank($id) {
        $rowAffected = $this->banks->deleteDataBank($id);
        if($rowAffected == 1) {
            if($this->bankBalanceHistory->countHistory($id) == 0) {
                return $this->resp->response('Success', 200, 'Delete sukses, silahkan cek kembali data anda!');
            } else {
                $rowAffected = $this->bankBalanceHistory->deleteDataHistoryByBankId($id);
                if($rowAffected >= 1) {
                    return $this->resp->response('Success', 200, 'Delete sukses, silahkan cek kembali data anda!');
                } else {
                    return $this->resp->response('Failed', 500, 'Delete history gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
                }
            }
        } else {
            return $this->resp->response('Failed', 500, 'Delete gagal, terdapat kesalahan teknis hubungi pihak developer segera!');
        }
    }
    //
}
