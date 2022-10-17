<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Contract;
use App\Employee;
use App\ContractEmployee;
use App\ContractDetail;
use App\Corporate;
use Illuminate\Support\Facades\Auth;

class KontrakController extends Controller
{
    public function data()
    {
        $employee = Employee::where('user_id', Auth::user()->id)->first();
        if ($employee) {
            $contract = ContractEmployee::where('employee_id', $employee->id)->get();
            if ($contract) {
                foreach ($contract as $item) {
                    $cd = ContractDetail::find($item->contract_detail_id);
                }
                if (!$cd) return response()->json(['errors' => 'Tidak dapat menemukan Kontrak Detail Pada Kontrak Pegawai'], 400);

                $contract_main = Contract::where('id', $cd->contract_id)
                    ->where('status', 1)->first();
                if ($contract_main) {
                    $data = ([
                        'corporate_id'  => $contract_main->corporate_id,
                        'contract_code' => $contract_main->contract_code,
                        'status'    => $contract_main->status,
                        'budget'        => $cd->budget,
                        'contract_name' => $cd->contract_detail_name
                    ]);
                    return response()->json(
                        [
                            'status' => true,
                            'message' => 'Terdapat data Kontrak Yang Aktif',
                            'data' => $data
                        ]
                    );
                } else {
                    return response()->json(
                        [
                            'status' => true,
                            'message' => ' tidak ada kontak yang aktif',
                            'data' => null
                        ]
                    );
                }
            } else {
                return response()->json(
                    [
                        'status' => true,
                        'message' => ' kontrak pegawai berdasarkan employee_id ini kosong/tidak ada',
                        'data' => null
                    ]
                );
            }
        } else return response()->json(
            [
                'status' => true,
                'message' => 'Tidak dapat menemukan employee_id pada akun ini',
                'data' => null
            ]
        );
    }
}
