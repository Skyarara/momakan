<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use App\http\Requests;
use App\Contract;
use App\Corporate;
use App\ContractDetail;
use App\Invoice;
use App\InvoiceDetail;
use App\Menu;
use App\ListEmployeeFood;
use App\Employee;
use App\ContractEmployee;
use App\ContractDetailMenu;
use App\payment;
use App\Feedback;
use DB;

class KontrakController extends Controller
{
    public function index(request $request)
    {
        $data = Contract::all();
        $corporate = Corporate::all();
        $time = date('Y-m-d');
        $id_terbaru = Contract::latest()->first();
        if ($id_terbaru) {
            $id_terbaru = $id_terbaru->id;
        } else {
            $id_terbaru = 0;
        }
        if (auth()->user()->role_id == 3) {
            $corporate_contract = Contract::where('corporate_id', '=', Auth()->user()->corporate->id)->get();
            return view('kontrak.index', compact('data', 'corporate', 'time', 'id_terbaru', 'corporate_contract'));
        }
        return view('kontrak.index', compact('data', 'corporate', 'time', 'id_terbaru'));
    }
    public function tambah(request $request)
    {
        $contract = Contract::get();
        if ($contract->count() != 0) {
            $fnd = null;
            $check = Contract::where('corporate_id', $request->perusahaan_id)                
                            ->latest()
                            ->get();
            foreach ($check as $dt) {
                if ($dt->status == true) $fnd = $dt;
                break;
            }

            if ($fnd != null) {
                return response()->json(['errors' => "Tidak bisa menambah karena masih ada kontrak yang aktif pada perusahaan ini"]);
            } else {
                $corporate = Corporate::find($request->perusahaan_id);
                if ($corporate) {

                    $ltc = Contract::where('corporate_id', $request->perusahaan_id)
                                    ->latest()
                                    ->get()
                                    ->first();

                    $contract = new Contract();
                    $contract->corporate_id = $request->perusahaan_id;
                    $contract->contract_code = "DKC/" . $corporate->id . "/" . (intval(Contract::latest()->get()->first()->id) + intval(1));
                    $contract->status = true;
                    // $contract->date_start = $request->tanggal_mulai;
                    // $contract->date_end = $request->tanggal_berakhir;
                    $contract->save();

                    if ($request->restore == 1) {
                        if ($ltc) {
                            $contract_latest_id = $ltc->id;
                            $cd = ContractDetail::where('contract_id', $contract_latest_id)->get();
                            foreach ($cd as $dt) {
                                $cd_new = new ContractDetail();
                                $cd_new->contract_id = $contract->id;
                                $cd_new->budget = $dt->budget;
                                $cd_new->contract_detail_name = $dt->contract_detail_name;
                                $cd_new->save();

                                $cdm = ContractDetailMenu::where('contract_detail_id', $dt->id)->get();
                                foreach ($cdm as $dts) {
                                    $cdm_new = new ContractDetailMenu();
                                    $cdm_new->contract_detail_id = $cd_new->id;
                                    $cdm_new->menu_id = $dts->menu_id;
                                    $cdm_new->save();
                                }

                                $ce = ContractEmployee::where('contract_detail_id', $dt->id)->get();
                                foreach ($ce as $dts) {
                                    $ce_new = new ContractEmployee();
                                    $ce_new->employee_id = $dts->employee_id;
                                    $ce_new->contract_detail_id = $cd_new->id;
                                    $ce_new->save();
                                }
                            }
                        }
                    }

                    return response()->json([
                        'result' => 'Menambahkan Data Berhasil',
                        'kode_kontrak' => "DKC/" . $corporate->id . "/" . Contract::latest()->get()->first()->id,
                        'nama_perusahaan' => $corporate->name,
                        'id' => Contract::latest()->get()->first()->id
                        // 'tgs' => date('Y-m-d'),
                        // 'tgm' => $request->tanggal_mulai,
                        // 'tgb' => $request->tanggal_berakhir
                    ]);
                } else return response()->json(['errors' => 'Perusahaan yang anda input tidak ada']);
            }
        } else {
            $corporate = Corporate::find($request->perusahaan_id);
            if ($corporate) {
                $contract = new Contract();
                $contract->corporate_id = $request->perusahaan_id;
                $contract->contract_code = "DKC/" . $corporate->id . "/1";
                // $contract->date_start = $request->tanggal_mulai;
                // $contract->date_end = $request->tanggal_berakhir;
                $contract->save();

                $contract->update(['contract_code' => "DKC/" . $corporate->id . "/" . $contract->id]);
                return response()->json([
                    'result' => 'Menambahkan Data Berhasil',
                    'kode_kontrak' => $contract->contract_code,
                    'nama_perusahaan' => $corporate->name,
                    'id' => $contract->id,
                    'tgs' => date('Y-m-d'),
                    'tgm' => $request->tanggal_mulai,
                    'tgb' => $request->tanggal_berakhir
                ]);
            } else return response()->json(['errors' => 'Perusahaan yang anda input tidak ada']);
        }
    }
    public function detail(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            return response()->json(
                [
                    'tgm' => $contract->date_start,
                    'tgb' => $contract->date_end,
                    'np' => Corporate::find($contract->corporate_id)->id
                ]
            );
        } else return response()->json(['errors' => 'Data Tidak ditemukan']);
    }
    public function ubah(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            $corporate = Corporate::find($request->perusahaan_id);
            if ($corporate) {
                $time = date('Y-m-d');
                //if($time >= $contract->date_start && $time <= $contract->date_end){
                $contract->corporate_id = $request->perusahaan_id;
                $contract->contract_code = "DKC/" . $corporate->id . "/" . $request->id;
                $contract->date_start = $request->tanggal_mulai;
                $contract->date_end = $request->tanggal_berakhir;
                $contract->save();
                return response()->json([
                    'result' => 'Mengubah Data Berhasil',
                    'kode_kontrak' => "DKC/" . $corporate->id . "/" . $request->id,
                    'nama_perusahaan' => $corporate->name,
                    'id' => $request->id,
                    'tgm' => $request->tanggal_mulai,
                    'tgb' => $request->tanggal_berakhir,
                    'tgs' => date('Y-m-d')
                ]);
                //} else return response()->json(['errors' => 'Data kontrak ini tidak aktif']);
            } else return response()->json(['errors' => 'Perusahaan yang anda input tidak ada']);
        } else return response()->json(['errors' => 'Data yang ingin diubah, tidak tersedia']);
    }
    public function hapus(request $request)
    {        
        $contract = Contract::find($request->id);
        if ($contract) {
            // $payment = payment::where('invoice_id', $contract->id)->get();
            // foreach ($payment as $pyt) {
            //     $pyt->delete();
            // }
            // $ce = ContractEmployee::where('contract_detail_id', $contract->id)->get();
            // foreach ($ce as $del) {
            //     $del->delete();
            // }
            // $cdm = ContractDetailMenu::where('contract_detail_id', $contract->id)->get();
            // foreach ($cdm as $dld){
            //     $dld->delete();
            // }
            $cd = ContractDetail::where('contract_id', $contract->id)->get();
            foreach ($cd as $dl) {
                $dl->delete();
            }
            $invoice = Invoice::where('contract_id', $contract->id)->get();
            foreach ($invoice as $ivc) {
                $ivc->delete();
            }
            $feedback = Feedback::where('order_id', $contract->id)->get();
            foreach ($feedback as $fdb) {
                $fdb->delete();
            }

            $contract->delete();
            $ltc = Contract::latest()->get()->first();
            if ($ltc) {
                $ltc = $ltc->id;
            } else {
                $ltc = "";
            }
            return response()->json(['result' => 'Data Berhasil di hapus', 'ltc' => $ltc]);
        } else return response()->json(['errors' => 'Data yang ingin dihapus, tidak tersedia']);
    }
    public function terbaru(request $request)
    {
        $corporate = Corporate::find($request->perusahaan_id);
        if ($corporate) {
            $contract = Contract::where('corporate_id', $corporate->id)->get();
            if ($contract->count() == 0) {
                return response()->json(['result' => false]);
            } else {
                return response()->json(['result' => true]);
            }
        } else return response()->json(['errors' => 'Gagal! Perusahaan yang anda pilih tidak ada']);
    }
    public function batalkan(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract)
        {
            if($contract->status == false) return response()->json(['errors' => 'Data sudah tidak aktif!']);
            else {
                $contract->status = false;
                $contract->save();

                return response()->json(['result' => 'Data berhasil di Nonaktifkan']);
            }
        } else return response()->json(['errors' => 'Data tidak ditemukan!']);
    }

    public function aktifkan(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            if ($contract->status == false) {
                $contract->status = true;
                $contract->save();
                return response()->json(['result' => 'Data berhasil di Aktifkan']);
            }else return response()->json(['errors' => 'Data sudah aktif!']);
            
        } else return response()->json(['errors' => 'Data tidak ditemukan!']);
        
    }

    // ==========================================================================================

    public function tambah_detail(request $request)
    {
        $contract = Contract::find($request->id);
        if ($contract) {
            $cd = new ContractDetail();
            $cd->contract_id = $request->id;
            $cd->budget = str_replace(',', '', $request->budget); // Replace Coma to Empty
            $cd->contract_detail_name = $request->nama;
            $cd->save();
            return response()->json(
                [
                    'result' => "Berhasil Menambahkan Data",
                    'nama' => $request->nama,
                    'budget' => $request->budget,
                    'id' => $cd->id
                ]
            );
        } else return response()->json(['errors' => 'Data kontrak yang dipilih tidak ada']);
    }
}
