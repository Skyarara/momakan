<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Menu;
use App\Employee;
use App\EmployeeMenu;
use Auth;

class EmployeeMenuController extends Controller
{
    public function data()
    {
        $id_user = Auth::user()->id;
        $employee_id = Employee::where('user_id', $id_user)->first()->id;
        $data = EmployeeMenu::with('menu')->where('employee_id', $employee_id)->get();
        if ($data->isNotEmpty()) {
            $data_result = [];
            foreach ($data as $dt) {
                $isAvailable = false;
                if ($dt->menu->isActive == true) $isAvailable = true;
                $isExtra = false;
                if ($dt->isExtra == true)  $isExtra = true;
                $data_result[] = ([
                    'id' => $dt->id,
                    'employee_id' => $dt->employee_id,
                    'notes' => $dt->notes,
                    'isExtra'   => $isExtra,
                    'menu' => [
                        'id' => $dt->menu_id,
                        'name' => $dt->menu->name,
                        'description' => $dt->menu->description,
                        'price' => $dt->menu->price,
                        'image' => url('/storage/images/' . $dt->menu->image),
                        'category' => [
                            'id' => $dt->menu->categories->id,
                            'name' => $dt->menu->categories->name,
                        ],
                    ],
                    'quantity' => $dt->quantity,
                    'isAvailable' => $isAvailable,
                ]);
            }
            return response()->json([
                'status' => true,
                'message' => 'Success get Employee Menu data',
                'data' => $data_result
            ]);
        } else return response()->json(['status' => false, 'error' => 'Gagal mengambil data Employee Menu'], 401);
    }

    public function tambah(request $request)
    {
        if ($request->has('data')) {
            $data = $request->data;
            // Menghapus Semua Data EmployeeMenu
            $employee = Employee::where('user_id', Auth::user()->id)->first();
            if (!$employee) return response()->json(['status' => false, 'errors' => 'Tidak dapat menemukan Employee ID user Ini'], 400);

            $em_list = EmployeeMenu::where('employee_id', $employee->id)->get();
            foreach ($em_list as $dt) {
                $dt->delete();
            }
            // End
            foreach ($data as $dt) {
                $menu = Menu::find($dt['menu_id']);
                if ($menu) {
                    if ($employee) {
                        $em                     = new EmployeeMenu();
                        $em->employee_id        = $employee->id;
                        $em->notes              = $dt['notes'];
                        $em->menu_id            = $dt['menu_id'];
                        $em->quantity           = $dt['quantity'];
                        $em->isExtra            = $dt['isExtra'];
                        $em->save();
                    } else return response()->json(['status' => false, 'errors' => 'Tidak Dapat menemukan Employee ID Dari User ini'], 400);
                } else return response()->json(['status' => false, 'errors' => 'Tidak Dapat menemukan Menu ID [' . $dt['menu_id'] . ']'], 400);
            }

            return response()->json(['status' => true, 'message' => 'Menambahkan Data Employee Menu Berhasil!'], 200);
        } else {
            if (
                $request->has('notes') && $request->has('menu_id') &&
                $request->has('quantity') && $request->isExtra != null
            ) {
                $employee = Employee::where('user_id', Auth::user()->id)->first();
                if ($employee) {
                    $menu = Menu::find($request->menu_id);
                    if ($menu) {
                        $em = null;
                        $isExits = EmployeeMenu::where('employee_id', $employee->id)
                            ->where('menu_id', $menu->id)
                            ->first();
                        if (!$isExits) {
                            $em                 = new EmployeeMenu();
                            $em->employee_id    = $employee->id;
                            $em->notes          = $request->notes;
                            $em->menu_id        = $menu->id;
                            $em->quantity       = $request->quantity;
                            $em->isExtra        = $request->isExtra;
                            $em->save();
                        } else {
                            $em             = $isExits;
                            $em->notes      = $request->notes;
                            $em->quantity   = $request->quantity;
                            $em->isExtra    = $request->isExtra;
                            $em->save();
                        }

                        $isAvailable = false;
                        if ($menu->isActive == true) $isAvailable = true;
                        $isExtra = false;
                        if ($em->isExtra == true)  $isExtra = true;

                        return response()->json(
                            [
                                'status'        => true,
                                'message'       => 'Berhasil menambahkan data',
                                'data'          => [
                                    'id'            => $em->id,
                                    'notes'         => $em->notes,
                                    'quantity'      => $em->quantity,
                                    'isExtra'       => $isExtra,
                                    'employee_id'   => Employee::where('user_id', Auth::user()->id)->first()->id,
                                    'menu'          => array(
                                        'id'            => $menu->id,
                                        'name'          => $menu->name,
                                        'category' => [
                                            'id' => $menu->categories->id,
                                            'name' => $menu->categories->name,
                                        ],
                                        'description'   => $menu->description,
                                        'price'         => $menu->price,
                                        'image'         => url('/storage/images/' . $menu->image),
                                        'isAvailable'   => $isAvailable
                                    )
                                ]
                            ],
                            200
                        );
                    }
                    return response()->json(['status' => false, 'errors' => 'Tidak Dapat menemukan Menu ID [' . $request->menu_id . ']'], 400);
                } else return response()->json(['status' => false, 'errors' => 'Tidak Dapat menemukan Employee ID Dari User ini'], 400);
            } else return response()->json(['status' => false, 'errors' => 'Parameter tidak valid'], 400);
        }
    }

    public function ubah(request $request)
    {
        $user = auth()->user()->id;
        if (!$request->has('menu_id') || !$request->has('category_id') || !$request->has('notes') || !$request->has('quantity')) {
            return response()->json(['status' => false, 'errors' => 'Parameter tidak valid'], 400);
        } else {
            $employee = Employee::where('user_id', $user)->first();
            if ($employee) {
                $employee_menu = EmployeeMenu::with('menu')->where('employee_id', $employee->id)->pluck('menu_id');
                $menu = Menu::whereIn('id', $employee_menu)->get();
                if ($employee_menu) {
                    $check_data = $menu->where('menu_category_id', $request->category_id)->first();
                    // dd($check_data);
                    if ($check_data != null) {
                        $isAvailable = false;
                        $new_menu = Menu::find($request->menu_id);
                        $emn = EmployeeMenu::where('employee_id', $employee->id)->where('menu_id', $check_data->id)->update(['menu_id' => $new_menu->id, 'quantity' => $request->quantity, 'notes' => $request->notes]);
                        $new_data = EmployeeMenu::where('employee_id', $employee->id)->where('menu_id', $new_menu->id)->first();

                        return response()->json(
                            [
                                'status'        => true,
                                'message'       => 'Berhasil mengubah data',
                                'data'          => [
                                    'id'            => $new_data->id,
                                    'notes'         => $new_data->notes,
                                    'quantity'      => $new_data->quantity,
                                    'employee_id'   => $new_data->employee_id,
                                    'menu'          => array(
                                        'id'            => $new_menu->id,
                                        'name'          => $new_menu->name,
                                        'category'  => [
                                            'id' => $new_menu->categories->id,
                                            'name' => $new_menu->categories->name,
                                        ],
                                        'description'   => $new_menu->description,
                                        'price'         => $new_menu->price,
                                        'image'         => url('/storage/images/' . $new_menu->image),
                                        'isAvailable'   => $isAvailable
                                    )
                                ]
                            ],
                            200
                        );
                    } else return response()->json(['status' => false, 'errors' => 'Menu Category Salah'], 400);
                } else return response()->json(['status' => false, 'errors' => 'Tidak Dapat menemukan Employee_menu user ini'], 400);
            } else return response()->json(['status' => false, 'errors' => 'Tidak Dapat menemukan Employee dengan user ini'], 400);
        }
    }


    public function hapus(request $request)
    {
        if (!$request->has('employee_menu_id')) {
            return response()->json(['status' => false, 'errors' => 'Parameter tidak valid'], 400);
        } else {
            $em = EmployeeMenu::find($request->employee_menu_id);
            if ($em) {
                $em->delete();

                return response()->json(
                    [
                        'status'        => true,
                        'message'       => 'Berhasil menghapus data'
                    ],
                    200
                );
            } else return response()->json(['status' => false, 'errors' => 'Tidak Dapat menemukan Employee Menu ID ini'], 400);
        }
    }
}
