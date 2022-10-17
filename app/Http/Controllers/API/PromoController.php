<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Promo;

class PromoController extends Controller
{
    public function data()
    {
        $data = Promo::get();
        if($data)
        {
            $data_result = null;
            foreach($data as $dt)
            {
                $data_result[] = (['id' => $dt->id,
                                 'title' => $dt->title,
                                 'description' => $dt->description,
                                 'image' => url('/storage/images/' . $dt->image)
                                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get promo data',
                'data' => $data_result
            ]);
        } else return response()->json(['error'=>'Gagal mengambil data promo'], 401);  
    }
}


