<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Employee;
use App\Corporate;
use App\Vendor;
use File;

class UpdateUserController extends Controller
{
    public function update(request $request)
    {
        Auth::user()->id;

        if (!array_key_exists('name', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if (!array_key_exists('email', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if (!array_key_exists('phone_number', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);

        $data = User::find(Auth::user()->id);
        if ($data) {
            $data->name = $request->name;
            $data->email = $request->email;
            $data->phone_number = $request->phone_number;
            $photo = $data->photo_profile;
            if ($request->has('photo_profile')) {
                $data->photo_profile ? File::delete(public_path('storage/images/photo_profile' . $photo)) : '';
                $file = $request->file('photo_profile');
                if (!$file->isValid()) {
                    return response()->json(['invalid_file_upload'], 400);
                }
                $photo = $file->getClientOriginalName();
                $path = public_path() . '/storage/images/photo_profile/';
                $file->move($path, $photo);
            }
            $data->photo_profile = $photo;
            $data->update();
            $photo_profile = $data->photo_profile ? url("/storage/images/photo_profile/$data->photo_profile") : '';

            return response(json_encode([
                'status' => true,
                'message' => 'Success update data User',
                'name' => $data->name,
                'email' => $data->email,
                'phone_number' => $data->phone_number,
                'photo_profile' => $photo_profile
            ], JSON_UNESCAPED_SLASHES))->header('Content-Type', "application/json");
        } else return response()->json([
            'error' => 'Gagal Mengupdate data User',
            'parameter' => 'id'
        ], 401);
    }
}
