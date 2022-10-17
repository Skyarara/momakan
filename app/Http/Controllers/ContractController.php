<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use App\http\Requests;
use App\Corporate;
use App\Makanan;
use App\ListEmployeeFood;
use App\Employee;
use DB;


class ContractController extends Controller
{
    public function getFood(request $request)
    {
      $data = Makanan::where('isPackage', 1)
                     ->where('price', '<=', $request->budget)->get();
                     dd($data);
      $content = "";
      foreach($data as $dt)
      {
          $content .= '<option value="' . $dt->id .'">' . $dt->name . ' - Rp. ' . number_format($dt->price) . '</option>';
      }        
      return $content;   
    }
    public function index(Request $request){

        $contract = Contract::filter($request)->paginate(10);
        $corporate = Corporate::all();
        $food = Makanan::all();
        $time = date('Y-m-d');
        return view('contract.index' , compact('contract','corporate','food','time'));
    }

    public function add(Request $request){    
      $contract = Contract::where('corporate_id', $request->name)
                          ->where('date_end', '>=', date('Y-m-d'))->latest()->get();
      $fnd = "";
      foreach($contract as $dt)
      {
          if(date('Y-m-d') >= $dt->date_start) $fnd = $dt; break;
      }  
        $rules = array(
          'name' => 'required',
          'order_budget' => 'required',
          'date_start' => 'required',
          'date_end' => 'required',
          'food' => 'required'

        );
      $validator = Validator::make ( Input::all(), $rules);
     
      if ($validator->fails()) {
        return Response::json(array('errors'=> $validator->getMessageBag()->toarray()));
      } else {
        if($fnd != "") return 0;

        $data = new Contract;
        $data->corporate_id = $request->name;
        $data->budget_max_order = $request->order_budget;
        $data->initial_food_id = $request->food;
        $data->date_start = $request->date_start;
        $data->date_end = $request->date_end;

        $contract2 = Contract::latest()->first();
        
        $data->contract_code = "DKC/" . $data->corporate_id . "/" .(!$contract2 ? 1 : $contract2->id + intval(1));
        $data->save();

        $employee = Employee::where('corporate_id', $request->name)->get();
        foreach($employee as $dt)
        {
            $efe = ListEmployeeFood::where('employee_id', $dt->id)->get();
            if($efe->count() == 1)
            {
                $ef = ListEmployeeFood::where('employee_id', $dt->id)
                                      ->where('food_id', $request->food)
                                      ->get();
                if($ef->count() == 1)
                {
                    foreach($ef as $dts)
                    {
                      $dts->isActive = 1;
                      $dts->save();
                    }
                } else if($ef->count() == 0)
                {
                    $dis = ListEmployeeFood::where('employee_id', $dt->id)->get();
                    foreach($dis as $dts)
                    {
                        $dts->isActive = 0;
                        $dts->save();
                    }
                    $ef_n = new ListEmployeeFood();
                    $ef_n->employee_id = $dt->id;
                    $ef_n->food_id = $request->food;
                    $ef_n->notes = "";
                    $ef_n->isActive = 1;
                    $ef_n->save();
                } else {
                    //Nothing
                }
              } else if($efe->count() == 0)
              {
                $ef_n = new ListEmployeeFood();
                $ef_n->employee_id = $dt->id;
                $ef_n->food_id = $request->food;
                $ef_n->notes = "";
                $ef_n->isActive = 1;
                $ef_n->save();    
            } else {
                $fnd = "";
                foreach($efe as $dtb)
                {
                    $chck = ListEmployeeFood::where('employee_id', $dtb->employee_id)
                                            ->where('food_id', $request->food)
                                            ->first();
                    if($chck){
                        $data_e = ListEmployeeFood::where('employee_id', $dtb->employee_id)->get();
                        foreach($data_e as $dtk)
                        {                                                        
                            $dtk->isActive = 0;
                            $dtk->save();                            
                        }
                        $ef_s = ListEmployeeFood::find($chck->id);
                        $ef_s->isActive = 1;
                        $ef_s->save();
                    } else {
                      $ef_n = new ListEmployeeFood();
                      $ef_n->employee_id = $dt->id;
                      $ef_n->food_id = $request->food;
                      $ef_n->notes = "";
                      $ef_n->isActive = 1;
                      $ef_n->save();
                    }
                }               
            }
        }
        return 1;
      }
  }

      public function edit(request $request)
      {
        $contract = Contract::where('corporate_id', $request->name)
                            ->where('date_end', '>=', date('Y-m-d'))->latest()->get();
        $fnd = "";
        foreach($contract as $dt)
        {
            if(date('Y-m-d') >= $dt->date_start) $fnd = $dt; break;
        }          

          $rules = array(
            'name' => 'required',
            'order_budget' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'food' => 'required'
          );
        $validator = Validator::make ( Input::all(), $rules);
        if ($validator->fails()) {
          return Response::json(array('errors'=> $validator->getMessageBag()->toarray()));
        } else {
          $cntrc = null;
          if($fnd == "") {
              // Tetap bisa di Edit
              // Todo Code : T4303
              $cntrc = Contract::find($request->id);
          } else {
              $cntrc = Contract::find($fnd->id);
          }
          
          if(!$cntrc) return 0;

          $employee = Employee::where('corporate_id', $cntrc->corporate_id)->get();
          foreach($employee as $dt)
          {
              $efe = ListEmployeeFood::where('employee_id', $dt->id)->get();
              if($efe)
              {
                  //Jika data yang diambil hanya 1 maka :
                  //Tidak ada penghapusan!
                  if($efe->count() == 1)
                  {
                      $ef = ListEmployeeFood::where('employee_id', $dt->id)
                                            ->where('food_id', $cntrc->initial_food_id)
                                            ->first();
                      if($ef)
                      {                                                                        
                          $ef->food_id = $request->food;                          
                          $ef->save();                          
                      } else {
                          foreach($efe as $dts)
                          {
                              $dts->food_id = $request->food;                          
                              $dts->save();
                          }
                      }
                  } else {    
                      //Tetapi jika ada lebih 1! Maka terjadi Penghapusan

                      $except = null; //Pengecualian

                      $ef = ListEmployeeFood::where('employee_id', $dt->id)
                                            ->where('food_id', $cntrc->initial_food_id)
                                            ->first();                       
                      if(!$ef)
                      {
                          // Sementara Di Kosongin    
                      } else {
                          $chck = ListEmployeeFood::where('employee_id', $dt->id)
                                                  ->where('food_id', $request->food)
                                                  ->first();
                          if(!$chck) { 
                            $except = $ef;
                            $ef->food_id = $request->food;
                            $ef->save();                             
                          } else {
                            //Tidak Melakukan apa apa
                          }
                      }
                      foreach($efe as $dt)
                      {
                          $harga_makanan = Makanan::find($dt->food_id)->price;
                          if($harga_makanan <= $request->order_budget) { }
                          else {
                            if($except != null)
                            {
                                if($dt->id != $except->id)
                                {
                                    $dt->delete();
                                }
                            }
                          }
                      }                    
                  }
              } else return 1;
          }
          $data = Contract::find($request->id);
          $data->corporate_id = $request->name;
          // $data->contract_code = $request->contract_code;
          $data->budget_max_order = $request->order_budget;
          $data->initial_food_id = $request->food;
          $data->date_start = $request->date_start;
          $data->date_end = $request->date_end;
          $data->save(); 
          return response()->json(['status' => 'Sukses'], 200);
        }
      }

      public function delete(request $request){
        $contractr = Contract::find($request->id);
        $contract = Contract::where('id', $contractr->id)
                      ->where('date_end', '>=', date('Y-m-d'))->latest()->get();
        $fnd = "";
        foreach($contract as $dt)
        {
          if(date('Y-m-d') >= $dt->date_start) $fnd = $dt; break;
        }  
        if($fnd == "") { 
          //Berhasil
          $contract = Contract::find($request->id)->delete();
          return 1;
        } else {
          //Gagal
          return response()->json(['status' => 'Gagal di hapus'], 400);
        }
    }
}