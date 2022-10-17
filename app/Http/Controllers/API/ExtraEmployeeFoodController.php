<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Makanan;
use App\Employee;
use App\ListEmployeeFood;
use App\ExtraEmployee;
use Auth;
use Illuminate\Support\Facades\Input;

class ExtraEmployeeFoodController extends Controller
{
    public function data()
    {                   
        $data_res = array();
        $eefg = ExtraEmployee::get();
        if(Input::has('employee_food_id')) $eefg = ExtraEmployee::where('employee_food_id', Input::get('employee_food_id'))->get();
        
        if($eefg)
        {
            foreach($eefg as $eef)
            {
                $data_res[] = (['id' => $eef->id,
                                'employee_food_id' => $eef->employee_food_id,
                                'food' => ['id' => $eef->food_id,
                                        'name' => Makanan::find($eef->food_id)->name,
                                        'description' => Makanan::find($eef->food_id)->description,
                                        'price' => Makanan::find($eef->food_id)->price,
                                        'image' => url('/storage/images/' . Makanan::find($eef->food_id)->image),
                                        'isPackage' => Makanan::find($eef->food_id)->isPackage],
                                'notes' => $eef->notes,
                                'qty' => $eef->qty]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get List Extra Employee Food Data',
                'data' => $data_res
                ]);   
        } else return response()->json(['error'=>'Gagal mengambil data List Employee Food'], 401);                    
    }
}
