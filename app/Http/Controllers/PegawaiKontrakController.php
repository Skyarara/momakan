<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\ContractEmployee;
use App\Employee;
use App\User;
use Carbon\Carbon;
use App\Contract;
use App\EmployeeMenu;
use App\ContractDetail;
use App\ContractDetailMenu;
use App\MenuPlan;
use App\MenuPlanDetail;

class PegawaiKontrakController extends Controller
{
    public function index(Request $request, $id, $id_dk)
    {
        $contract = Contract::find($id);
        if ($contract) {
            $cd = ContractDetail::find($id_dk);
            if ($cd) {
                $result = [];
                $ce = ContractEmployee::where('contract_detail_id', $cd->id)->get();
                foreach ($ce as $dt) {
                    $results[] = [
                        'nama'      => User::find(Employee::find($dt->employee_id)->user_id)->name,
                        'email'     => User::find(Employee::find($dt->employee_id)->user_id)->email,
                        'nomor_hp'  => User::find(Employee::find($dt->employee_id)->user_id)->phone_number,
                        'isActive'  => $dt->isActive,
                        'id'        => $dt->id
                    ];
                }

                $pegawai_tersedia = [];

                $em = Employee::where('corporate_id', $contract->corporate_id)->get();
                foreach ($em as $dt) {
                    $ce = ContractEmployee::where('employee_id', $dt->id)->get();
                    if ($ce->count() == 0) {
                        $pegawai_tersedia[] = ([
                            'id'    => $dt->id,
                            'name'  => User::find($dt->user_id)->name
                        ]);
                    } else {
                        $active = false;

                        foreach ($ce as $dts) {
                            $cd = ContractDetail::find($dts->contract_detail_id);
                            if ($cd) {
                                $ct = Contract::where('id', $cd->contract_id)
                                    ->where('status', 1)
                                    ->first();
                                if ($ct) {
                                    // Aktif
                                    $active = true;
                                    break;
                                } else {
                                    // Non Aktif                                                                                                            
                                }
                            }
                        }
                        if ($active == false) {
                            $pegawai_tersedia[] = ([
                                'id'    => $dt->id,
                                'name'  => User::find($dt->user_id)->name
                            ]);
                        }
                    }
                }

                // Dokumentasi Ada di MenuPlanController@index
                $results = collect($results)->sortBy('nama');
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $currentPageItems =  $results->slice(($currentPage * 10) - 10, 10)->all();
                $results = new LengthAwarePaginator($currentPageItems, count($results), 10);
                $results->setPath($request->url());

                return view('kontrak_pegawai.index', compact(
                    'results',
                    'id',
                    'id_dk',
                    'pegawai_tersedia'
                ));
            } else return redirect('/kontrak');
        } else return redirect('/kontrak');
    }

    public function tambah(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            $cd = ContractDetail::find($request->id_dk);
            if ($cd) {
                $em = Employee::find($request->id_em);
                if ($em) {
                    $ce = ContractEmployee::where('employee_id', $em->id)->first();
                    $cen                        = new ContractEmployee();
                    $cen->employee_id           = $request->id_em;
                    $cen->contract_detail_id    = $cd->id;
                    $cen->isActive             = $request->isActive;
                    $cen->save();

                    $tomorrow = Carbon::tomorrow()->toDateString();
                    $menu_plan = MenuPlan::where('date', $tomorrow)->value('id');
                    $menuplandetail = MenuPlanDetail::where('menu_plan_id', $menu_plan)->where('status', 1)->get();
                    foreach ($menuplandetail as $dt) {
                        $emenu                  = new EmployeeMenu();
                        $emenu->employee_id     = $request->id_em;
                        $emenu->notes           = null;
                        $emenu->menu_id         = $dt->menu_id;
                        $emenu->quantity        = 1;
                        $emenu->save();
                    }

                    return response()->json([
                        'result'    => 'Berhasil Menambahkan Data',
                        'nama'      => User::find($em->user_id)->name,
                        'email'     => User::find($em->user_id)->email,
                        'nomor_hp'  => User::find($em->user_id)->phone_number,
                        'id'        => $cen->id,
                        'isActive'  => $cen->isActive
                    ]);
                } else return response()->json(['errors' => 'Pegawai ini tidak ada']);
            } else return response()->json(['errors' => 'Kontrak Detail ini tidak ada']);
        } else return response()->json(['errors' => 'Kontrak ini tidak ada']);
    }

    public function hapus(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            $cd = ContractDetail::find($request->id_dk);
            if ($cd) {
                $cem = ContractEmployee::find($request->id_p);
                if ($cem) {

                    $emenu = EmployeeMenu::where('employee_id', $cem->employee_id)->get();
                    foreach ($emenu as $dt) {
                        $dt->delete();
                    }

                    $cem->delete();

                    $em = Employee::where('corporate_id', $contract->corporate_id)->get();
                    foreach ($em as $dt) {
                        $ce = ContractEmployee::where('employee_id', $dt->id)->get();
                        if ($ce->count() == 0) {
                            $pegawai_tersedia[] = ([
                                'id'    => $dt->id,
                                'name'  => User::find($dt->user_id)->name
                            ]);
                        } else {

                            $active = false;

                            foreach ($ce as $dts) {
                                $cd = ContractDetail::find($dts->contract_detail_id);
                                if ($cd) {
                                    $ct = Contract::where('id', $cd->contract_id)
                                        ->whereRaw('"' . date('Y-m-d') . '" BETWEEN `created_at` AND `updated_at`')
                                        ->first();
                                    if ($ct) {
                                        // Aktif
                                        $active = true;
                                        break;
                                    } else {
                                        // Non Aktif                                                                                                            
                                    }
                                }
                            }
                            if ($active == false) {
                                $pegawai_tersedia[] = ([
                                    'id'    => $dt->id,
                                    'name'  => User::find($dt->user_id)->name
                                ]);
                            }
                        }
                    }
                    return response()->json(
                        [
                            'result'    => 'Berhasil Menghapus Data',
                            'pegawai'   => $pegawai_tersedia
                        ]
                    );
                } else return response()->json(['errors' => 'Kontrak Pegawai ini tidak ada']);
            } else return response()->json(['errors' => 'Kontrak Detail ini tidak ada']);
        } else return response()->json(['errors' => 'Kontrak ini tidak ada']);
    }

    public function status_change(Request $request)
    {
        $contract_employee = ContractEmployee::find($request->id);
        $data = $contract_employee->isActive == 1 ? 0 : 1;
        $contract_employee->update(['isActive' => $data]);
        return $data;
    }
}
