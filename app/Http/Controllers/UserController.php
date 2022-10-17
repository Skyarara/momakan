<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\User;
use App\Corporate;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
//Menampilkan Halaman Index User 
{
  public function index()
  {
    $user_check = Auth::user()->role_id;
    if ($user_check != 1) {
      return redirect()->back();
    }
    $user = User::with('corporate')->get();

    return view('user.index', compact('user'));
  }

  //Mengedit Data
  /*
     * Fungsi untuk edit data Dan Validasi. Param (Request) Diambil Dari "Ajax Post Data Edit"
     */
  public function edit(request $request)
  {
    $id = $request->id;

    $messages = [
      'name.required' => 'Field name kosong',
      'email.unique' => 'Email sudah digunakan ',
      'phone_number.required' => 'Field nomor telpon Kosong ',
      'email.email' => 'Format Email Salah',
      'email.required' => 'Field email kosong'
    ];

    $rules = [
      'name' => 'required',
      'email' => "required|email|unique:users,email,$id",
      'phone_number' => 'required',
    ];
    $validation = Validator::make($request->all(), $rules, $messages);
    $error_array = array();
    if ($validation->fails()) {
      foreach ($validation->messages()->getMessages() as $field_name => $messages) {
        $error_array[] = $messages;
      }
    } else {
      $data = User::findOrFail($id);
      $data->name = $request->name;
      $data->email = $request->email;
      $data->phone_number = $request->phone_number;
      $data->save();
    }
    $output = [
      'error' => $error_array,
    ];
    return response()->json($output);
  }
}
