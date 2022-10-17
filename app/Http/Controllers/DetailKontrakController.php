<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DetailOrder;
use App\Contract;
use App\ContractDetail;
use App\Menu;
use App\MenuCategory;
use App\ContractEmployee;
use App\Employee;
use App\User;
use App\ContractDetailMenu;
use App\EmployeeMenu;
use Illuminate\Support\Facades\Session;

class DetailKontrakController extends Controller
{
    public function list_detail($id)
    {
        $contract = Contract::find($id);
        if (!$contract) return redirect()->back();
        $data = ContractDetail::where('contract_id', $id)->get();
        $id = $contract->id;
        return view('kontrak.list', compact('data', 'id'));

        $dm = ContractDetailMenu::where('contract_detail_id', $cd->id)->get();

    }
    public function tambah_page($id)
    {
        $contract = Contract::find($id);
        if (!$contract) return redirect('/kontrak');

        $result = [];
        $data_kategori = MenuCategory::where('id', '>', '1')->get();
        foreach ($data_kategori as $dt) {
            $makanan = [];
            $data_makanan = Menu::where('menu_category_id', $dt->id)->OrderBy('name', 'asc')->get();
            if ($data_makanan) {
                foreach ($data_makanan as $dts) {
                    $makanan[] = ([
                        'id'            => $dts->id,
                        'nama_makanan'  => $dts->name,
                        'harga'         => number_format($dts->price),
                        'harga_asli'    => $dts->price
                    ]);
                }
            }
            $result[] = ([
                'nama_kategori'  => $dt->name,
                'makanan'        => $makanan
            ]);
        }
        $id = $id;
        return view('kontrak_detail.tambah', compact('result', 'id'));
    }
    public function ubah_page($id, $id_dk)
    {
        $contract = Contract::find($id);
        if (!$contract) return redirect('/kontrak');
        $cd = ContractDetail::find($id_dk);
        if (!$cd) return redirect('/kontrak');

        $result = [];
        $data_kategori = MenuCategory::where('id', '>', '1')->get();

        foreach ($data_kategori as $dt) {
            $makanan = [];
            $data_makanan = Menu::where('menu_category_id', $dt->id)->get();
            if ($data_makanan) {
                foreach ($data_makanan as $dts) {
                    $makanan[] = ([
                        'id'            => $dts->id,
                        'nama_makanan'  => $dts->name,
                        'harga'         => number_format($dts->price),
                        'harga_asli'    => $dts->price
                    ]);
                }
            }
            $result[] = ([
                'nama_kategori'  => $dt->name,
                'makanan'        => $makanan
            ]);
        }

        $cdm = ContractDetailMenu::where('contract_detail_id', $cd->id)->get();
        $list_makanan = null;
        foreach ($cdm as $dt) {
            $list_makanan .= $dt->menu_id . ',';
        }
        $id = $id;
        $id_cd = $id_dk;
        return view('kontrak_detail.ubah', compact(
            'result',
            'list_makanan',
            'cd',
            'id',
            'id_cd'
        ));
    }
    public function tambah_detail(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            // if ($request->makanan == "") {
            //     Session::flash('errors', 'Tidak ada makanan yang di pilih!');
            //     return redirect('/kontrak/detail/' . $contract->id);
            // } elseif (strpos($request->makanan, ',') == false) {
            //     Session::flash('errors', 'Pengisian makanan tidak valid');
            //     return redirect('/kontrak/detail/' . $contract->id);
            // } elseif ($request->total_harga_tf > $request->budget) {
            //     Session::flash('errors', 'Total Harga Melebihi Budget!');
            //     return redirect('/kontrak/detail/' . $contract->id);
            // } else {
                $cd = new ContractDetail();
                $cd->contract_id            = $contract->id;
                $cd->budget                 = $request->budget;
                $cd->contract_detail_name   = $request->name;
                $cd->save();

                $lm = $request->makanan;
                $lms = explode(',', $lm);
                for ($i = 0; $i < count($lms) - 1; $i++) {
                    $id = $lms[$i];
                    $menu = Menu::find($id);
                    if ($menu) {
                        $cdm                        = new ContractDetailMenu();
                        $cdm->contract_detail_id    = $cd->id;
                        $cdm->menu_id               = $id;
                        $cdm->save();
                    }
                }

                Session::flash('success', 'Menambahkan Data Berhasil');
                return redirect('/kontrak/detail/' . $contract->id);
            // }
        } else return redirect()->back();
    }
    public function hapus_detail(request $request)
    {
        $contract = Contract::find($request->id_contract);
        if ($contract) {
            $cd = ContractDetail::find($request->id);
            if ($cd) {
                if ($cd->contract_id == $contract->id) {
                    $cdm = ContractDetailMenu::where('contract_detail_id', $cd->id)->get();
                    foreach ($cdm as $dt) {
                        $dt->delete();
                    }

                    $ce = ContractEmployee::where('contract_detail_id', $cd->id)->get();
                    foreach ($ce as $dt) {
                        $dt->delete();
                    }

                    $cd->delete();
                    return response()->json(
                        [
                            'result' => "Berhasil Menghapus Data",
                        ]
                    );
                } else return response()->json(['errors' => 'Data Detail Kontrak ini Berbeda dengan Kontrak yang anda pilih! Silahkan Refresh Page']);
            } else return response()->json(['errors' => 'Data detail kontrak yang dipilih tidak ada']);
        } else return response()->json(['errors' => 'Data kontrak yang dipilih tidak ada']);
    }
    public function ubah_detail(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            $cd = ContractDetail::find($request->id_cd);
            if ($cd) {
                if ($request->total_harga_tf > $request->budget) {
                    Session::flash('errors', 'Total Harga Melebihi Budget!');
                    return redirect('/kontrak/detail/' . $contract->id);
                }
                $cd->budget                 = str_replace(',', '', $request->budget); // Replace Coma to Empty
                $cd->contract_detail_name   = $request->name;
                $cd->save();

                $cdm = ContractDetailMenu::where('contract_detail_id', $cd->id)->get();
                foreach ($cdm as $dt) {
                    $dt->delete();
                }

                $lm = $request->makanan;
                $lms = explode(',', $lm);
                for ($i = 0; $i < count($lms) - 1; $i++) {
                    $id = $lms[$i];
                    $menu = Menu::find($id);
                    if ($menu) {
                        $cdm                        = new ContractDetailMenu();
                        $cdm->contract_detail_id    = $cd->id;
                        $cdm->menu_id               = $id;
                        $cdm->save();
                    }
                }

                $cemp = ContractEmployee::where('contract_detail_id', $cd->id)->get();
                foreach ($cemp as $dt) {
                    $empm = EmployeeMenu::where('employee_id', $dt->employee_id)->get();
                    foreach ($empm as $dts) {
                        $dts->delete();
                    }
                    for ($i = 0; $i < count($lms) - 1; $i++) {
                        $id = $lms[$i];
                        $menu = Menu::find($id);
                        if ($menu) {
                            $empmn                 = new EmployeeMenu();
                            $empmn->employee_id    = $dt->employee_id;
                            $empmn->notes          = null;
                            $empmn->menu_id        = $id;
                            $empmn->quantity       = 1;
                            $empmn->save();
                        }
                    }
                }

                Session::flash('success', 'Mengubah Data Berhasil');
                return redirect('/kontrak/detail/' . $contract->id);
            } else {
                Session::flash('errors', 'Data detail kontrak yang dipilih tidak ada');
                return redirect('/kontrak/detail/' . $contract->id);
            }
        } else {
            //Session::flash('errors', 'Data kontrak yang dipilih tidak ada');
            return redirect('/kontrak/');
        }
    }
    public function data_detail(request $request)
    {
        $contract = Contract::find($request->idc);
        if ($contract) {
            $cd = ContractDetail::find($request->id);
            if ($cd) {
                $result = [];
                $pegawai = [];

                $cdm = ContractDetailMenu::where('contract_detail_id', $cd->id)->get();
                foreach ($cdm as $dt) {
                    $result[] = (['nama' => Menu::find($dt->menu_id)->name]);
                }
                $ce = ContractEmployee::where('contract_detail_id', $cd->id)->get();
                foreach ($ce as $dt) {
                    $pegawai[] = (['nama' => User::find(Employee::find($dt->employee_id)->user_id)->name]);
                }

                return response()->json(['result' => $result, 'pegawai' => $pegawai]);
            } else return response()->json(['errors' => 'Data Detail Kontrak ini tidak ditemukan']);
        } else return response()->json(['errors' => 'Data Kontrak ini tidak ditemukan']);
    }
}
