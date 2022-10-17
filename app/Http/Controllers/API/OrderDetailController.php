<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrderDetail;
use App\Order;
use App\Makanan;
use App\Employee;
use Auth;

class OrderDetailController extends Controller
{
    public function tambah(request $request)
    {
        if(!array_key_exists('order_id', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if(!array_key_exists('food_id', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if(!array_key_exists('price', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if(!array_key_exists('notes', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if(!array_key_exists('quantity', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if(!array_key_exists('isExtra', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);        

        $order = Order::find($request->order_id);
        if($order)
        {
            $makanan = Makanan::find($request->food_id);
            if($makanan)
            {
                $employee = Employee::where('user_id', Auth::user()->id)->first();
                if($employee)
                {
                    $ord_det = new OrderDetail();
                    $ord_det->employee_id = $employee->id;
                    $ord_det->order_id = $request->order_id;
                    $ord_det->food_id = $request->food_id;
                    $ord_det->price = $request->price;
                    $ord_det->notes = $request->notes;
                    $ord_det->quantity = $request->quantity;
                    $ord_det->isExtra = $request->isExtra;
                    $ord_det->save();

                    return response()->json([
                        'status' => true,
                        'message' => 'Success add order detail'
                    ]);
                } else return response()->json(['error' => 'Gagal Menambahkan data Order Detail, Tidak dapat menemukan employee_id'], 401);  

            } else return response()->json(['error' => 'Gagal Menambahkan data Order Detail, Cek kembali data yang ingin di tambahkan',
                                            'parameter' => 'food_id'], 401);  
        } else return response()->json(['error'=>'Gagal Menambahkan data Order Detail, Cek kembali data yang ingin di tambahkan',
                                        'parameter' => 'order_id'], 401);  
    }
}
