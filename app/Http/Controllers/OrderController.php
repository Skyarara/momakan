<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\DetailOrder;
use App\OrderDetail;
use App\Contract;
use App\Corporate;
use App\Employee;
use App\ListEmployeeFood;
use App\User;
use App\Makanan;
use App\ExtraEmployee;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use App\http\Requests;

class OrderController extends Controller
{
        public function index($id)
        {
                $corporate = Corporate::where('id', $id)->first();
                if ($corporate) {
                        $contract = Contract::where('corporate_id', $corporate->id)->first();
                        if ($contract) {
                                $data = Order::where('contract_id', $contract->id)->get();
                                if ($data) {

                                        return view('order.index', compact('data', 'id'));
                                } else return redirect()->back();
                        } else return redirect()->back();
                } else return redirect()->back();
        }

        public function detail($id)
        {
                $data = DetailOrder::where('order_id', $id)->get();
                if ($data) {
                        $time = date('Y-m-d');
                        return view('order.detail', compact('data', 'id'));
                } else return redirect('/contract');
        }
        public function tambah($id)
        {
                $result = array();

                $corporate = Corporate::where('id', $id)->first();
                if ($corporate) {
                        $employee = Employee::where('corporate_id', $corporate->id)->get();
                        if ($employee) {
                                $nama_pegawai = "";
                                $nama_makanan = "";
                                $notes = "";

                                foreach ($employee as $dt) {
                                        $employee = ListEmployeeFood::where('employee_id', $dt->id)->get();
                                        if ($employee) {
                                                $isActive = false;
                                                $nama_pegawai = User::find($dt->user_id)->name;
                                                foreach ($employee as $dts) {
                                                        if ($isActive == false) {
                                                                if ($dts->isActive == true) {
                                                                        $isActive = true;
                                                                        $nama_makanan = Makanan::find($dts->food_id)->name;
                                                                        $notes = $dts->notes;

                                                                        $extraemployee = ExtraEmployee::where('employee_food_id', $dts->id)->get();
                                                                        if ($extraemployee) {
                                                                                foreach ($extraemployee as $dtee) {
                                                                                        $result[] = ([
                                                                                                'nama_pegawai' => $nama_pegawai,
                                                                                                'nama_makanan' => Makanan::find($dtee->food_id)->name,
                                                                                                'notes' => $dtee->notes,
                                                                                                'qty' => $dtee->qty
                                                                                        ]);
                                                                                }
                                                                        }
                                                                        $result[] = ([
                                                                                'nama_pegawai' => $nama_pegawai,
                                                                                'nama_makanan' => $nama_makanan,
                                                                                'notes' => $notes,
                                                                                'qty' => 1
                                                                        ]);
                                                                }
                                                        } else {
                                                                $dts->isActive = 0;
                                                                $dts->save();
                                                        }
                                                }
                                        }
                                }
                                return view('order.tambah', ['data' => $result, 'id' => $id]);
                        }
                } else return redirect()->back();
        }
        public function tambah_action(request $request)
        {
                dd(1);
                $corporate = Corporate::where('id', $request->id)->first();
                if ($corporate) {
                        $contract = Contract::where('corporate_id', $corporate->id)->first();
                        if ($contract) {
                                $employee = Employee::where('corporate_id', $corporate->id)->get();
                                if ($employee) {
                                        /*$total_cost = 0; 
                    $temp_id = 0;
                    foreach($employee as $dt)
                    {            
                        if($temp_id != $dt->id)                              
                        {
                            $employee = ListEmployeeFood::where('employee_id', $dt->id)->where('isActive', 1)->first();
                            if($employee)
                            {                                           
                                $price = Makanan::where('id', $employee->food_id)->first();
                                $total_cost += $price->price;                                                         
                            }                
                            $temp_id = $dt->id;        
                        }
                    }*/
                                        $order = new Order();
                                        $order->datetime = date('Y-m-d H:i:s');
                                        $order->total_cost = 0;
                                        $order->contract_id = $contract->id;
                                        $order->save();

                                        $id_order = Order::latest()->first()->id;

                                        $employee = Employee::where('corporate_id', $corporate->id)->get();
                                        foreach ($employee as $dt) {
                                                $employee_food = ListEmployeeFood::where('employee_id', $dt->id)->where('isActive', 1)->first();

                                                if ($employee_food) {
                                                        $od = new OrderDetail();
                                                        $od->employee_id = $dt->id;
                                                        $od->food_id = $employee_food->food_id;
                                                        $od->order_id = Order::latest()->first()->id;
                                                        $od->price = Makanan::find($employee_food->food_id)->price;
                                                        $od->notes = $employee_food->notes;
                                                        $od->quantity = 1;
                                                        $od->isExtra = false;
                                                        $od->save();
                                                        // if(ExtraEmployee::where('employee_food_id', $employee_food->id)->first())
                                                        // {
                                                        $et = ExtraEmployee::where('employee_food_id', $employee_food->id)->get();
                                                        foreach ($et as $extra) {
                                                                $ex = new OrderDetail();
                                                                $ex->employee_id = $dt->id;
                                                                $ex->food_id = $extra->food_id;
                                                                $ex->order_id = Order::latest()->first()->id;
                                                                $ex->price = Makanan::find($extra->food_id)->price;
                                                                $ex->notes = $extra->notes;
                                                                $ex->quantity = 1;
                                                                $ex->isExtra = true;
                                                                $ex->save();
                                                        }
                                                        // }
                                                }
                                        }

                                        $orders = Order::find($id_order);
                                        $order_detail = OrderDetail::where('order_id', $orders->id)->sum('price');
                                        $orders->total_cost = $order_detail;
                                        $orders->save();

                                        return redirect('contract/order/' . $request->id . '/');
                                } else return redirect()->back();
                        } else return redirect()->back();
                } else return redirect()->back();
        }
}
