<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\http\Requests;
use App\Employee;
use App\Contract;
use App\ContractEmployee;
use App\User;
use Validator;
use Response;
use DB;
use Excel;
use App\Corporate;

class EmployeeController extends Controller
{
  public function index($id)
  {
    $corporate = Corporate::find($id);
    $id_corporate = $corporate->id;
    $data = Employee::with('user')->leftJoin('users', 'users.id', '=', 'employee.user_id')->select('employee.*')->where('corporate_id', $id_corporate)->orderBy('users.name', 'ASC')->paginate(10);
    
    return view('employee.index', compact('data', 'id_corporate', 'corporate'));
  }

  public function add(Request $request)
  {

    $messages = [
      'password.same' => 'Password Harus Sama',
      'email.unique' => 'Email Sudah digunakan',
      'phone_number.required' => 'Nomor Telepon Kosong'
    ];

    $rules = [
      'name' => 'required',
      'phone_number' => 'required',
      'email' => 'required|email|unique:users',
      'password' => 'required|same:password2'
    ];
    $validation = Validator::make($request->all(), $rules, $messages);

    $error_array = array();
    if ($validation->fails()) {
      foreach ($validation->messages()->getMessages() as $field_name => $messages) {
        $error_array[] = $messages;
      }
    } else {
      $data = new User;
      $data->name = $request->name;
      $data->role_id = 2;
      $data->phone_number = $request->phone_number;
      $data->email = $request->email;
      $data->password = bcrypt($request->password);
      $data->save();

      $employee = new Employee();
      $employee->user_id = User::latest()->first()->id;
      $employee->corporate_id = $request->id;
      $employee->save();
    }
    $output = [
      'error' => $error_array,
    ];
    return response()->json($output);
    // return response()->json(['data' => $data, 'empl' => $employee, 'error' => $error_array]);
  }

  public function delete(request $request)
  {
    $employee = Employee::where("user_id",$request->id)->first();
    
    $contract_employee = ContractEmployee::where('employee_id', $employee->id)->get();
    if ($contract_employee->isNotEmpty()) {
      return 3;
    }
    else {
      $user = User::find($employee->user_id);
      if ($user) {
        $employee->delete();
        $user->delete();
        return response()->json();
      }
    }
  }

  public function edit(request $request)
  {
    $rules = array(
      'name' => 'required',
      'phone_number' => 'required',
      'email' => 'required|email|unique:users'
    );
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      return Response::json(array('errors' => $validator->getMessageBag()->toarray()));
    } else if ($validator) { } else {
      $data = Employee::find($request->id);
      $user = $data->user;
      $user->update([
        'name'  => $request->name,
        'phone_number' => $request->phone_number,
        'email' => $request->email
      ]);

      $dataSuper = [
        'id'    => $data->id,
        'user_id' => $user->id,
        'email' => $user->email,
        'name'  => $user->name,
        'phone_number'  => $user->phone_number,
        'role_id' => $user->role_id,
      ];

      return response()->json($dataSuper);
    }
  }

  public function import(Request $request)
  {
    try {
      DB::beginTransaction();
      $id = $request->idk;
      if ($request->hasFile('file')) {
        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path, function ($reader) { })->get();
        if (!empty($data) && $data->count()) {
          foreach ($data as $key => $value)
            if (!empty($value->name) || !empty($value->email)) { { {
                  $messages = [
                    'email.unique' => 'Terdapat Email Duplikat',
                    'name.required' => 'Terdapat Nama yang Kosong',
                    'phone_number.required' => 'Terdapat Nomor Telpon yang Kosong',
                    'email.required' => 'Terdapat Email yang Kosong',
                    'email.email' => 'Terdapat Format Email yang Salah',
                  ];

                  $rules = [
                    'name' => 'required',
                    'phone_number' => 'required',
                    'email' => 'email|required|unique:users'
                  ];
                  $validation = Validator::make($value->all(), $rules, $messages);
                  $error_array = array();
                }
                if ($validation->fails()) {
                  foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                    $error_array = $messages;
                  }
                  DB::rollback();
                  return redirect()->back()->withErrors($messages);
                } else {
                  $user = new User();
                  $user->name = $value->name;
                  $user->email = $value->email;
                  $user->phone_number = $value->phone_number;
                  $user->role_id = 2;
                  $user->password = bcrypt('123456');
                  $user->save();

                  $employee = new Employee;
                  $employee->user_id = $user->id;
                  $employee->corporate_id = $id;
                  $employee->save();
                }
              }
            }
        }
      }
      DB::commit();
      return redirect()->back()->with('info', 'Berhasil Mengimpor Data Password Default Pegawai "123456"');
    } catch (\Exception $e) { }
  }
}
