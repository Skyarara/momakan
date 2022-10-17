<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ListEmployeeFood;
use App\Makanan;
use App\ExtraEmployee;

class ExtraEmployeeController extends Controller
{
    public function tambah(request $request)
    {
        if (array_key_exists('data', $request->all()))
            //    array_key_exists('food_id', $request->all()) &&
            //    array_key_exists('notes', $request->all()) &&
            //    array_key_exists('qty', $request->all()))
            {
                foreach ($request->get('data') as $dt) {
                        $data_ef = ListEmployeeFood::find($dt['employee_food_id']);
                        $ch = false;
                        if ($data_ef) {
                                $data_food = Makanan::find($dt['food_id']);
                                if ($data_food) {
                                        $exe = ExtraEmployee::where('employee_food_id', $dt['employee_food_id'])
                                            ->where('food_id', $dt['food_id'])->first();

                                        if ($exe) {
                                            $exe->qty = $dt['qty'];
                                            $exe->save();
                                            $ch = true;
                                        } else {
                                            $ee = new ExtraEmployee();
                                            $ee->employee_food_id = $dt['employee_food_id'];
                                            $ee->food_id = $dt['food_id'];
                                            $ee->notes = $dt['notes'];
                                            $ee->qty = $dt['qty'];
                                            $ee->save();
                                        }
                                    } else return response()->json([
                                    'error' => 'Data gagal ditambah',
                                    'parameter' => 'food_id'
                                ]);
                            } else return response()->json([
                            'error' => 'Data gagal ditambah',
                            'parameter' => 'employee_food_id'
                        ]);
                    }
                if ($ch == false) {
                        return response()->json([
                            'status' => true,
                            'message' => 'Sukses menambahkan data Extra Employee'
                        ]);
                    } else {
                    return response()->json([
                        'status' => true,
                        'message' => 'Sukses menambahkan dan data Extra Employee dan mengubah data yang sama'
                    ]);
                }
            } else return response()->json(['error' => 'Parameter tidak valid'], 401);
    }
    public function ubah(request $request)
    {
        if (
            array_key_exists('extra_employee_food_id', $request->all()) &&
            array_key_exists('notes', $request->all()) &&
            array_key_exists('qty', $request->all())
        ) {
                $eef = ExtraEmployee::find($request->extra_employee_food_id);
                if ($eef) {
                        $eef->notes = $request->notes;
                        $eef->qty = $request->qty;
                        $eef->save();

                        return response()->json([
                            'status' => true,
                            'message' => 'Sukses mengubah data Extra Employee'
                        ]);
                    } else return response()->json([
                    'error' => 'Data gagal diubah',
                    'parameter' => 'extra_employee_food_id'
                ]);
            } else return response()->json(['error' => 'Parameter tidak valid'], 401);
    }
    public function hapus(request $request)
    {
        if (array_key_exists('extra_employee_food_id', $request->all())) {
                $eef = ExtraEmployee::find($request->extra_employee_food_id);
                if ($eef) {
                        $eef->delete();

                        return response()->json([
                            'status' => true,
                            'message' => 'Sukses menghapus data Extra Employee'
                        ]);
                    } else return response()->json([
                    'error' => 'Data gagal dihapus',
                    'parameter' => 'extra_employee_food_id'
                ]);
            } else return response()->json(['error' => 'Parameter tidak valid'], 401);
    }
}
