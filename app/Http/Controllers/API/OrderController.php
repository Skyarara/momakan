<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Order;
use App\OrderDetail;
use App\Contract;
use App\Makanan;
use App\Menu;
use App\Feedback;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function data()
    {
        $emp = Employee::where('user_id', Auth::user()->id)->first();
        $result = array();
        if ($emp) {
            $con = Contract::where('corporate_id', $emp->corporate_id)->latest()->first();
            $ord = Order::where('contract_id', $con->id)
                ->where('employee_id', $emp->id)
                ->orderBy('datetime', 'desc')
                ->get();
            foreach ($ord as $dt) {
                $result_detail = array();
                $ordd = OrderDetail::where('order_id', $dt->id)->get();
                foreach ($ordd as $dts) {
                    $result_detail[] = ([
                        'order_detail_id' => $dts->id,
                        'food' => [
                            'id' => Menu::find($dts->menu_id)->id,
                            'name' => Menu::find($dts->menu_id)->name,
                            'description' => Menu::find($dts->menu_id)->description,
                            'price' => Menu::find($dts->menu_id)->price,
                            'image' => url('/storage/images/' . Menu::find($dts->menu_id)->image)
                        ],
                        'notes' => $dts->notes,
                        'price' => $dts->price,
                        'quantity' => $dts->quantity
                    ]);
                }
                $feedback = Feedback::where('employee_id', $emp->id)->where('order_id', $dt->id)->first();
                if ($feedback != null) {
                    $feedbacks_result = ([
                        'id' => $feedback->id,
                        'rating' => $feedback->rating,
                        'order_id' => $feedback->order_id,
                        'description' => $feedback->description,
                    ]);
                } else {
                    $feedbacks_result = null;
                };
                $result[] = ([
                    'order_id' => $dt->id,
                    'contract_code' => $con->contract_code,
                    'datetime' => $dt->datetime->TodateString(),
                    'total_cost' => $dt->total_cost,
                    'feedback' => $feedbacks_result,
                    'order_detail' => $result_detail,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get order data',
                'data' => $result
            ]);
        } else return response()->json(['error' => 'employee tidak ditemukan'], 404);
    }
    public function status($contract_id)
    {
        $contract = Contract::find($contract_id);
        if ($contract) {
            $contracts = Contract::where('corporate_id', $contract->corporate_id)
                ->where('date_end', '>=', date('Y-m-d'))->latest()->get();
            $fnd = "";
            foreach ($contracts as $dt) {
                if (date('Y-m-d') >= $dt->date_start) $fnd = $dt;
                break;
            }
            if ($fnd == "") {
                return response()->json([
                    'status' => true,
                    'message' => 'Kontrak ini Tidak Aktif',
                    'result' => '0'
                ]);
            }
            $check = Order::where('contract_id', $contract->id)
                ->where('created_at', 'LIKE', date("Y-m-d") . '%')
                ->first();
            if ($check) {
                return response()->json([
                    'status' => true,
                    'message' => 'Kontrak ini terdapat bahwa hari ini menambahkan pesanan',
                    'result' => '2'
                ]);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'Kontrak ini belum ada membuat pesanan hari ini',
                    'result' => '1'
                ]);
            }
        } else return response()->json([
            'error' => 'Gagal Mengambil Status Order',
            'parameter' => 'contract_id'
        ], 400);
    }
}
