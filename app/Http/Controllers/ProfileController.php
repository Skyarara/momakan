<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\User;
use App\Corporate;
use Validator;
use Response;

class ProfileController extends Controller
{
    public function index($id)
    {
        $profile = User::with('corporate')->find($id);
        // dd($profile);
        $view =
            [
                'id'      => $id,
                'profile' => $profile
            ];
        return view('user.profile.index')->with($view);
    }

    public function pass($id)
    {
        $profile = User::with('corporate')->find($id);
        $view =
            [
                'id'      => $id,
                'profile' => $profile
            ];
        return view('user.profile.password')->with($view);
    }

    public function ubah(request $request)
    {
        $id = $request->id;

        $messages = [
            'email_edit.unique' => 'Email sudah digunakan ',
            'phone_edit.required' => 'Field nomor telpon Kosong ',
            'email_edit.email' => 'Format Email Salah',
            'email_edit.required' => 'Field email kosong'
        ];

        $rules = [
            'email_edit' => "required|email|unique:users,email,$id",
            'phone_edit' => 'required',
            'address_edit' => 'required',
        ];
        $validation = Validator::make($request->all(), $rules, $messages);
        $error_array = array();
        if ($validation->fails()) {
            foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages;
            }
        } else {
            $user = User::findOrFail($id);
            $user->email = $request->email_edit;
            $user->update();

            $corporate = Corporate::findOrFail($request->corporate_id);
            $corporate->address = $request->address_edit;
            $corporate->telp = $request->phone_edit;
            $corporate->update();
        }
        $output = [
            'error' => $error_array,
        ];
        return response()->json($output);
    }

    public function ubahpass(request $request)
    {
        $id = $request->id;
        $hashedPassword = Auth()->user()->getAuthPassword();
        if (Hash::check($request->old_password, $hashedPassword)) {
            $messages = [
                'new_password.required' => ' Field Password Baru Masih Kosong',
                'new_password.same' => 'Password Belum Sama',
                'old_password.required' => 'Field Password Lama Masih Kosong'
            ];

            $rules = [
                'new_password' => "required|same:confirm_password",
                'old_password' => 'required',
            ];
            $validation = Validator::make($request->all(), $rules, $messages);
            $error_array = array();
            if ($validation->fails()) {
                foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                    $error_array[] = $messages;
                }
            } else {
                $user = User::findOrFail($id);
                $user->password = bcrypt($request->new_password);
                $user->update();

                return redirect()->back()->with('success', "Password Baru Anda Sekarang Adalah [$request->new_password]  ");
            }
            $output = [
                'error' => $error_array,
            ];
            return redirect()->back()->withErrors($messages);
        } else {
            return redirect()->back()->with('fail', 'Password Tidak Sesuai Dengan yang Lama');
        }
    }
}
