<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
class VendorController extends Controller
{
    public function data()
    {
        $data = Vendor::get();
        if($data)
        {
            $data_result = null;
            foreach($data as $dt)
            {
                $data_result[] = (['id' => $dt->id,
                                 'name' => $dt->name,
                                 'address' => $dt->address,
                                 'tagline' => $dt->tagline
                                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get vendor data',
                'data' => $data_result
            ]);
        } else return response()->json(['error'=>'Gagal mengambil data vendor'], 401);  
    }
}
