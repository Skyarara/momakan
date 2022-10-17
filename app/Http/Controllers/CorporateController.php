<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use App\http\Requests;
use App\Corporate;
use App\User;
use App\Contract;
use App\Employee;


class CorporateController extends Controller
{

  public function index()
  {

    $data = Corporate::paginate(10);
    return view('corporate.index', compact('data'));
  }

  public function add(Request $request)
  {
    $messages = [
      'pass.same' => 'Password Dan Konfirmasi Password Berbeda',
      'email.unique' => 'Email sudah digunakan ',
    ];

    $rules = [
      'name' => 'required',
      'email' => 'required|email|unique:users',
      'telp' => 'required',
      'address' => 'required',
      'pass' => 'required|same:pass2'
    ];
    $validation = Validator::make($request->all(), $rules, $messages);

    $error_array = array();
    if ($validation->fails()) {
      foreach ($validation->messages()->getMessages() as $field_name => $messages) {
        $error_array[] = $messages;
      }
    } else {
      $user = new User;
      $user->name = $request->name;
      $user->email = $request->email;
      $user->phone_number = $request->telp;
      $user->role_id = 3;
      $user->password = bcrypt($request->pass);
      $user->save();


      $data = new Corporate;
      $data->name = $request->name;
      $data->telp = $request->telp;
      $data->address = $request->address;
      $data->user_id = User::latest()->first()->id;
      $data->save();
    }
    $output = [
      'error' => $error_array,
    ];
    return response()->json($output);
  }

  public function delete(request $request)
  {
    //Rasyid

    // $contract = Contract::where('corporate_id', $request->id)
    //                     ->where('status')->latest()->get();
    // $fnd = "";
    // foreach($contract as $dt)
    // {
    //     if('status' >= 1) $fnd = $dt; break;
    // }  
    // if($fnd != "") return 0;

    // yg dibawah ini agar jika perusahaan dihapus maka semua user perusahaan
    // dan user-user employee juga ikut terhapus

    $data = Corporate::find($request->id);
    $user = User::find($data->user_id);

    $contract = Contract::where('corporate_id', $data->id)->get();
    if ($contract->isNotEmpty()) {
      return 3;
    }

    $employee = Employee::where('corporate_id', $data->id)->get();
    foreach ($employee as $dt) {
      $users = User::find($dt->user_id)->delete();
    }
    $data->delete();
    $user->delete();
    return response()->json(['status' => 'Berhasil di hapus'], 200);
  }

  //

  public function edit(request $request)
  {
    $rules = array(
      'name' => 'required',
      'telp' => 'required',
      'address' => 'required',
    );

    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails())
      return Response::json(array('errors' => $validator->getMessageBag()->toarray()));
    else {
      $data = Corporate::find($request->id);
      $data->name = $request->name;
      $data->telp = $request->telp;
      $data->address = $request->address;
      $data->save();
      return response()->json($data);
    }
  }
}
