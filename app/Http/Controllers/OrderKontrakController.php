<?php

namespace App\Http\Controllers;

use App\Contract;
use App\Order;
use App\OrderDetail;
use App\Employee;
use App\User;
use App\EmployeeMenu;
use App\ContractDetail;
use Session;
use App\Menu;
use Carbon\Carbon;
use App\ContractEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderKontrakController extends Controller
{
    public function index($id)
    {
        $contract = Contract::find($id);
        if ($contract) {
            $data = Order::where('contract_id', $id)->groupBy('datetime')->get();

            $result = [];
            $no = 0;
            foreach ($data as $dt) {
                $no++;
                $result[] = ([
                    'tanggal'           => $dt->datetime,
                    'total_pembayaran'  => Order::where('contract_id', $id)->where('datetime', $dt->datetime)->get()->sum('total_cost'),   'id' => $dt->id,
                ]);
            }
            return view('kontrak_order.index', compact(
                'result',
                'id'
            ));
        } else return redirect()->back();
    }
    public function tambah_page($id)
    {
        $contract = Contract::find($id);
        if ($contract) {
            $result = [];
            $result_makanan = [];

            $data_pegawai = [];

            $employee = Employee::where('corporate_id', $contract->corporate_id)->whereHas('contract_employee', function ($e) {
                $e->where('isActive', 1);
            })->get();
            
            foreach($employee as $dt)
            {
                $result_makanan_temp = [];
                $em = EmployeeMenu::where('employee_id', $dt->id)->get();
                foreach ($em as $dtem) {
                    if (array_key_exists($dtem->menu_id, $result_makanan)) {
                        $result_makanan[$dtem->menu_id] = array(
                            [
                                'id'    => $dtem->menu_id,
                                'cnt'   => $result_makanan[$dtem->menu_id][0]['cnt'] + $dtem->quantity
                            ]
                        );
                    } else {
                        $result_makanan[$dtem->menu_id] = array(
                            [
                                'id'    => $dtem->menu_id,
                                'cnt'   => $dtem->quantity
                            ]
                        );
                    }
                    $result[] = ([
                        'nama_pegawai'    => User::find($dt->user_id)->name,
                        'nama_makanan'    => Menu::find($dtem->menu_id)->name,
                        'catatan'         => $dtem->notes,
                        'jumlah'          => $dtem->quantity
                    ]);

                    $result_makanan_temp[] = ([
                        'nama_pegawai'    => User::find($dt->user_id)->name,
                        'nama_makanan'    => Menu::find($dtem->menu_id)->name,
                        'catatan'         => $dtem->notes,
                        'jumlah'          => $dtem->quantity
                    ]);
                }
                $data_pegawai[] = ([
                    'nama_pegawai'  => User::find($dt->user_id)->name,
                    'makanan'       => $result_makanan_temp
                ]);
            }

            $data_makanan = [];
            foreach ($result_makanan as $dt) {
                $data_makanan[] = ([
                    'nama_makanan'  => Menu::find($dt[0]['id'])->name,
                    'photo'         => Menu::find($dt[0]['id'])->image,
                    'porsi'         => $dt[0]['cnt']
                ]);
            }

            $data_pegawai = collect($data_pegawai)->sortBy('nama_pegawai');
            $result = collect($result)->sortBy('nama_pegawai');
            
            return view('kontrak_order.tambah', compact(
                'result',
                'id',
                'data_makanan',
                'data_pegawai'
            ));
        } else return redirect()->back();
    }
    public function tambah_action(request $request)
    {
        DB::beginTransaction();
        try {
            $date = $request->tanggal;
            $request->tanggal = Carbon::createFromFormat('d-m-Y', $request->tanggal)->format('Y-m-d');
            $contract = Contract::find($request->id);
            $orderDay = Order::where('datetime', $request->tanggal)->where('contract_detail_id', $contract->contractDetail->pluck('id'))->first();
            if ($orderDay !== null) {
                Session::flash('result', 'Order Pada Tanggal ' . $date . ' Sudah Dibuat');
                Session::flash('danger', 'danger');

                return redirect('/kontrak/order/' . $contract->id);
            }

            if($contract)
            {
                $cd = ContractDetail::where('contract_id', $contract->id)->get();    
                foreach($cd as $dt)
                {
                    $ce = ContractEmployee::where('contract_detail_id', $dt->id)->where('isActive', 1)->get();
                    foreach($ce as $dt_ce)
                    {
                        $order              = new Order();
                        $order->datetime    = $request->tanggal;
                        $order->contract_id = $contract->id;
                        $order->total_cost  = 0; // Sementara, Dibawah akan diupdate
                        $order->total_extra = 0; // Sementara, Dibawah akan diupdate
                        $order->employee_id = $dt_ce->employee_id;
                        $order->contract_detail_id = $dt->id;
                        $order->save();
        
                        $em = EmployeeMenu::where('employee_id', $dt_ce->employee_id)->get();
                        foreach($em as $dts)
                        {
                            $od             = new OrderDetail();
                            $od->order_id   = $order->id;
                            $od->price      = Menu::find($dts->menu_id)->price;
                            $od->notes      = $dts->notes;
                            $od->quantity   = $dts->quantity;
                            $od->menu_id    = $dts->menu_id;
                            $od->isExtra    = $dts->isExtra;
                            $od->save();
                        }
    
                        $total_cost = 0;
                        $total_extra = 0;
    
                        $od_list = OrderDetail::where('order_id', $order->id)->get();
                        foreach ($od_list as $dts) {
                            $total_cost += intval($dts->price) * intval($dts->quantity);
                        }
    
                        $total_extra = intval($total_cost) - intval($dt->budget);
                        if ($total_extra <= 0) {
                            $total_extra = 0;
                        }
                        $order->total_extra     = $total_extra;
                        $order->total_cost      = $total_cost;
                        $order->save();
                    }
                }

                DB::commit();
    
                Session::flash('result', 'Berhasil Menambahkan Data');
                return redirect('/kontrak/order/' . $contract->id);
            } else {
                return redirect()->back();
            } 
        } catch (\Exception $th) {
            DB::rollBack();

            Session::flash('danger', 'danger');
            Session::flash('result', 'Terjadi Error : ' . $e->getMessage());
            return redirect('/kontrak/order/' . $contract->id)->withInput();
        }
    }

    public function hapus(request $request)
    {
        $tanggal = $request->tahun . '-' . $request->bulan . '-' . $request->tanggal;
        $date = \Carbon\Carbon::parse($tanggal);
        $order = Order::with('order_detail')->whereDate('datetime', $date);
        $response = [];
        foreach ($order->get() as $detail) {
            if ($detail->order_detail->count() > 0) {
                $response = [
                    'status' => false,
                    'pesan'  => 'Tidak dapat menghapus karena order di pakai order detail',
                ];
            } else if ($detail->invoice_detail->count() > 0) {
                $response = [
                    'status' => false,
                    'pesan'  => 'Tidak dapat menghapus karena order di pakai invoice detail',
                ];
            }
        }

        if (empty($response)) {
            $order->delete();
            $response = [
                'status' => true,
                'pesan'  => 'Data berhasil di hapus',
            ];
        }


        return response()->json($response);
    }

    public function detail($id, $tanggal)
    {
        $data = Order::where('contract_id', $id)
            ->where('datetime', $tanggal)
            ->get();
        if ($data) {
            $data_pegawai = [];
            $data_menu = [];
            foreach ($data as $dt) {
                $data_od = OrderDetail::where('order_id', $dt->id)->get();
                foreach ($data_od as $dts) {
                    $menu = Menu::find($dts->menu_id);
                    if (array_key_exists($menu->id, $data_menu)) {
                        $data_menu[$menu->id] = ([
                            'nama'      => $menu->name,
                            'jumlah'    => $data_menu[$menu->id]['jumlah'] + $dts->quantity
                        ]);
                    } else {
                        $data_menu[$menu->id] = ([
                            'nama'      => $menu->name,
                            'jumlah'    => $dts->quantity
                        ]);
                    }
                }

                $employee = Employee::find($dt->employee_id);

                $data_pegawai[] = ([
                    'nama'      => User::find($employee->user_id)->name,
                    'jumlah'    => $dt->total_cost,
                    'ekstra'    => $dt->total_extra,
                    'id'        => $dt->id
                ]);
            }
            return view('kontrak_order.detail', compact('data_pegawai', 'data_menu', 'id'));
        } else return redirect()->back();
    }
    public function detail_pegawai($id, $id_pegawai)
    {
        $contract = Contract::find($id);
        if ($contract) {
            // ID_PEGAWAI = ID ORDER
            $order = Order::where('id', $id_pegawai)->where('contract_id', $contract->id)->first();
            if ($order) {
                $order_detail = OrderDetail::where('order_id', $order->id)->get();
                return view('kontrak_order.order_detail', compact('order_detail'));
            } else return redirect()->back();
        } else return redirect()->back();
    }
}
