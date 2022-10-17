<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\Corporate;
use App\Order;
use App\Employee;
use App\DetailOrder;
use App\User;
use App\Config;
use App\ContractDetail;
use App\ContractEmployee;

use Session;
use Illuminate\Support\Facades\Input;
class LaporanController extends Controller
{
    public function index()
    {
        $data = Contract::get();
        if($data)
        {
            return view('laporan.index', ['data' => $data]);
        } else return redirect('/home');
    }

    public function detail($id){
        $data = Contract::find($id);
        $date_start = null;
        $date_end = null;
        if($data)
        {
            $date_start = date_format($data->created_at, 'Y-m-d');
            $date_end = null;

            if(Input::has('ab'))
            {
                $a = Input::get('ab');
                $d = date_format($data->created_at, 'Y-m');
                if($a == $d) {
                    $date_end = date_format($data->created_at, 'Y-m-t');                    
                } else if ($a <= $d)
                {
                    // Tanggal yang di input dibawah tanggal mulai
                    Session::flash('message', 'Tidak ada Order pada bulan ini');
                    return redirect('/laporan');
                } else {
                    $date_start = date('Y-m-d', strtotime(Input::get('ab')));
                    $date_end = $a . '-' . date('t', strtotime(Input::get('ab')));
                }
            }    
            $corporate = Corporate::find($data->corporate_id);
            if($corporate)
            {
                $order = Order::where('contract_id', $data->id)
                              ->whereDate('created_at', '>=', $date_start)
                              ->whereDate('created_at', '<=', $date_end)
                              ->latest()->get();                                        
                if($order->count() == 0 || !$order)                           
                {
                    Session::flash('message', 'Tidak ada Order pada bulan ini');
                    return redirect('/laporan');
                }
                $date_end = date_format($order->first()->created_at, 'Y-m-d'); //Mengambil data pertama sebagai tanggal/bulan terakhir
                $total_perusahaan = 0;
                $total_pegawai = $order->sum('total_extra');

                foreach($order as $dt)
                {
                    $ce = ContractEmployee::where('employee_id', $dt->employee_id)->get();
                    foreach($ce as $dts)
                    {
                        $cd = ContractDetail::find($dts->contract_detail_id);
                        if($cd)
                        {
                            if($cd->contract_id == $data->id)
                            {
                                $total_perusahaan += $cd->budget;
                            }
                        }
                    }
                    
                    /*$orderdetail = DetailOrder::where('order_id', $dt->id)->get();
                    if($orderdetail)
                    {
                        $total_perusahaan += $orderdetail->where('isExtra', 0)->sum('price');
                        $total_pegawai += $orderdetail->where('isExtra', 1)->sum('price');
                    }*/
                }
                         

                $employee = Employee::where('corporate_id', $corporate->id)->get()->count();

                $total_semua = Order::where('contract_id', $data->id)
                                    ->whereDate('created_at', '>=', $date_start)
                                    ->whereDate('created_at', '<=', $date_end)
                                    ->sum('total_cost');       
                                    
                // Mengecheck Apakah Melebihi Budget
                $isMoreThanBudget = false;
                $contract_detail_bd = ContractDetail::where('contract_id', $data->id)->first();
                if($contract_detail_bd)
                {
                    if($contract_detail_bd->budget >= $total_semua) { $isMoreThanBudget = true; }
                }      

                $data_employee = Employee::where('corporate_id', $corporate->id)->get();
                $result = array();
                foreach($data_employee as $dt)
                {
                    $order_employee = Order::where('employee_id', $dt->id)->get();
                    if($order_employee)
                    {
                        $total_cost_od = 0;
                        $exc = [];                        
                        foreach($order_employee as $dts)
                        {
                            if(!array_key_exists($dts->id, $exc))
                            {
                                $od = DetailOrder::where('order_id', $dts->id)
                                                 ->where('created_at', 'LIKE', date_format($dts->created_at, 'Y-m-d') . '%')->get();
                                if($od)
                                {
                                        
                                    if($od->count() != 1)
                                    {
                                            
                                        foreach($od as $ods)
                                        {
                                            $total_cost_od += $ods->price * $ods->quantity;                                            
                                            $exc[$ods->id] = 1;
                                        }                                        
                                    } else {
                                        $total_cost_od = $od->first()->price * $od->first()->quantity;
                                    }
                                        
                                } else $total_cost_od = $dts->price * $dts->quantity; 
                                    
                                $result[] = (['nama_pegawai' => User::find($dt->user_id)->name,
                                            'tanggal' => date_format($dts->created_at, 'Y-m-d'),
                                            'total' => $total_cost_od - $data->budget_max_order]);                                    
                            }
                                $total_cost_od = 0;
                        }
                            $exc = [];
                        // } else {
                        //     $odfa = DetailOrder::where('order_id', $order_employee->first()->id)->latest()->first();
                        //     $result[] = (['nama_pegawai' => User::find($dt->user_id)->name,
                        //                   'tanggal' => date_format($order_employee->first()->created_at, 'Y-m-d'),
                        //                   'total' => ($odfa->price * $odfa->quantity) - $data->budget_max_order]);
                        // }
                        
                    }                
                }                
                $config_laporan = Config::latest()->get()->first(); 
                return view('laporan.detail', compact('data' , 'corporate', 'order', 
                                                      'employee', 'total_semua', 'result',
                                                      'total_perusahaan', 'total_pegawai',
                                                      'date_start', 'date_end', 'config_laporan',
                                                      'isMoreThanBudget')); 	
            } else return redirect('/laporan');
        } else return redirect('/laporan');
    } 
}
