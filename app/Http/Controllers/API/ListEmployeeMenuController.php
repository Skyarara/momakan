<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListEmployeeFood;
use App\Makanan;
use App\Employee;
use App\ExtraEmployee;
use Auth;
use Illuminate\Support\Facades\Input;
class ListEmployeeFoodController extends Controller
{
    public function data()
    {
        $id_user = Auth::user()->id;
        $employee_id = Employee::where('user_id', $id_user)->first()->id;
        $data = ListEmployeeFood::where('employee_id', $employee_id)->get();
        if(Input::has('employee_food_id')) { 
            $data = ListEmployeeFood::where('id', Input::get('employee_food_id'))
                                    ->where('employee_id', $employee_id)
                                    ->get();
        }
        if(Input::has('isActive')){
            if(Input::get('isActive') == 1) $data = ListEmployeeFood::where('employee_id', $employee_id)->where('isActive', 1)->get();
            else if(Input::get('isActive') == 0) $data = ListEmployeeFood::where('employee_id', $employee_id)->where('isActive', 0)->get();
        }
        if($data)
        {
            $data_result = null;
            foreach($data as $dt)
            {
                $data_res = array();
                $eefg = ExtraEmployee::where('employee_food_id', $dt->id)->get();                
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
                } else $data_res = null;               
                    $data_result[] = (['id' => $dt->id,
                                    'notes' => $dt->notes,
                                    'isActive' => $dt->isActive,
                                    'food' => [
                                        'id' => $dt->food_id,
                                        'name' => $dt->food->name,
                                        'description' => $dt->food->description,
                                        'price' => $dt->food->price,
                                        'image' => url('/storage/images/' . $dt->food->image),
                                        'isPackage' =>$dt->food->isPackage                                    
                                    ],
                                    'extra_employee_food' => $data_res
                                    ]);                
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get List Employee Food Data',
                'data' => $data_result
            ]);
        } else return response()->json(['error'=>'Gagal mengambil data List Employee Food'], 401);  
    }
}
