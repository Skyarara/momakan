<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Employee;
use App\Makanan;
use App\ListEmployeeFood;
use App\DetailOrder;
use App\Contract;
use App\ExtraEmployee;

class EmployeeFoodController extends Controller
{
    public function tambah(request $request)
    {
        dd($request->all());
        if (!array_key_exists('menu_id', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 400);
        if (!array_key_exists('notes', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 400);

        $makanan = Makanan::find($request->menu_id);
        if ($makanan) {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            if ($employee) {
                $check = ListEmployeeFood::where('employee_id', $employee->id)
                    ->where('menu_id', $request->menu_id)->first();
                if ($check) return response()->json(['error' => 'Gagal Menambahkan data Employee Food! Makanan yang dipilih sudah ada di makanan anda'], 400);


                $listemf = ListEmployeeFood::where('employee_id', $employee->id)->get();
                foreach ($listemf as $dt) {
                    $dt->isActive = 0;
                    $dt->save();
                }
                $empf = new ListEmployeeFood();
                $empf->employee_id = $employee->id;
                $empf->menu_id = $request->menu_id;
                $empf->notes = $request->notes;
                $empf->isActive = 1;
                $empf->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Success menambahkan employee food'
                ]);
            } else return response()->json(['error' => 'Gagal Menambahkan data Employee Food, Tidak dapat menemukan employee_id'], 400);
        } else return response()->json(['error' => 'Gagal Menambahkan data Employee Food', 'parameter' => 'menu_id'], 400);
    }
    public function ubah_status(request $request)
    {
        if (!array_key_exists('employee_menu_id', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if (!array_key_exists('notes', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        if (!array_key_exists('isActive', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);

        $data = ListEmployeeFood::find($request->employee_menu_id);
        if ($data) {
            if ($request->isActive == 1) {
                $data_other = ListEmployeeFood::where('employee_id', $data->employee_id)->get();
                foreach ($data_other as $dt) {
                    if ($dt->id != $request->employee_menu_id) {
                        $dt->isActive = 0;
                        $dt->save();
                    }
                }
                $data->isActive = 1;
                $data->notes = $request->notes;
                $data->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Success ubah data Employee Food'
                ]);
            }
            $data->notes = $request->notes;
            $data->save();
            return response()->json([
                'status' => true,
                'message' => 'Success ubah data Employee Food'
            ]);
        } else return response()->json([
            'error' => 'Gagal Mengubah data Employee Food',
            'parameter' => 'employee_menu_id'
        ], 400);
    }
    public function ubah_food(request $request)
    {
        if (!array_key_exists('menu_id', $request->all())) return response()->json(['error' => 'Parameter tidak valid!'], 401);
        $makanan = Makanan::find($request->menu_id);
        if ($makanan) {
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            if ($employee) {
                $employeefood = ListEmployeeFood::where('employee_id', $employee->id)->get();
                if ($employeefood) {
                    $employeefood_aktif = ListEmployeeFood::where('employee_id', $employee->id)
                        ->where('isActive', 1)
                        ->first();
                    if ($employeefood_aktif) {

                        $employee_menu_id = 0;

                        $check = ListEmployeeFood::where('employee_id', $employee->id)
                            ->where('menu_id', $request->menu_id)
                            ->first();
                        if ($check) {
                            $employee_menu_id = $check->id;
                            if ($employeefood->count() != 1 && $employeefood_aktif->menu_id != $request->menu_id) {
                                $employeefood_aktif->isActive = 0;
                                $employeefood_aktif->save();
                            }
                            $check->isActive = 1;
                            $check->save();
                        } else {
                            $employee_menu_id = $employeefood_aktif->id;
                            $employeefood_aktif->menu_id = $request->menu_id;
                            $employeefood_aktif->save();
                        }

                        // Output Result
                        $data_result = null;
                        $data_res = array();

                        $eefg = ExtraEmployee::where('employee_menu_id', $employee_menu_id)->get();
                        if ($eefg) {
                            foreach ($eefg as $eef) {
                                $data_res[] = ([
                                    'id' => $eef->id,
                                    'employee_menu_id' => $eef->employee_menu_id,
                                    'food' => [
                                        'id' => $eef->menu_id,
                                        'name' => Makanan::find($eef->menu_id)->name,
                                        'description' => Makanan::find($eef->menu_id)->description,
                                        'price' => Makanan::find($eef->menu_id)->price,
                                        'image' => url('/storage/images/' . Makanan::find($eef->menu_id)->image),
                                        'isPackage' => Makanan::find($eef->menu_id)->isPackage
                                    ],
                                    'notes' => $eef->notes,
                                    'qty' => $eef->qty
                                ]);
                            }
                        } else $data_res = null;
                        $dt = ListEmployeeFood::find($employee_menu_id);
                        $data_result[] = ([
                                'id' => $dt->id,
                                'notes' => $dt->notes,
                                'isActive' => $dt->isActive,
                                'food' => [
                                    'id' => $dt->menu_id,
                                    'name' => $dt->food->name,
                                    'description' => $dt->food->description,
                                    'price' => $dt->food->price,
                                    'image' => url('/storage/images/' . $dt->food->image),
                                    'isPackage' => $dt->food->isPackage
                                ],
                                'extra_employee_food' => $data_res
                            ]);
                        return response()->json([
                            'status' => true,
                            'message' => 'Berhasil mengubah data employee food',
                            'data' => $data_result
                        ]);
                    }
                } else return response()->json([
                    'error' => 'Gagal Mengubah data Employee Food. Pegawai ini tidak memiliki Makanan',
                    'parameter' => null
                ], 400);
            } else return response()->json([
                'error' => 'Gagal Mengubah data Employee Food. ID User Employee tidak di temukan',
                'parameter' => null
            ], 400);
        } else return response()->json([
            'error' => 'Gagal Mengubah data Employee Food',
            'parameter' => 'menu_id'
        ], 400);
    }
}
