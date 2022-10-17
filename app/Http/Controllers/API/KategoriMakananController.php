<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MenuCategory;

class KategoriMakananController extends Controller
{
    public function data()
    {
        $data = MenuCategory::get();
        if ($data) {
            $data_result = null;
            foreach ($data as $dt) {
                $data_result[] = ([
                    'id' => $dt->id,
                    'name' => $dt->name
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get category food data',
                'data' => $data_result
            ]);
        } else return response()->json(['error' => 'Gagal mengambil data kategori makanan'], 401);
    }
}
