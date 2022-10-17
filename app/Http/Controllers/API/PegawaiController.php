<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth; 
use Validator;
use Hash;

class PegawaiController extends Controller
{
    public function ubah_password(request $request)
    {
        if(array_key_exists('password_lama', $request->all()) &&
           array_key_exists('password_baru', $request->all())) { 
            $data = User::find(Auth::user()->id);
            if($data)
            {                
                if(Hash::check($request->password_lama, $data->password))
                {
                    $data->password = bcrypt($request->password_baru);
                    $data->save();
                    return response()->json([
                        'status' => true,
                        'message' => 'Berhasil mengubah password'
                    ]);
                } else return response()->json(['error' => 'Gagal mengubah password! Password lama tidak valid'], 401);
            } else return response()->json(['error' => 'Gagal mengubah password! Data tidak ditemukan'], 401);
        } else return response()->json(['error' => 'Parameter Tidak Valid'], 401);
    }
}
