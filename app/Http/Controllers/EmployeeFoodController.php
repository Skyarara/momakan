<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EmployeeMenu;
use App\Employee;
use App\User;
use App\Menu;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use App\http\Requests;

class EmployeeFoodController extends Controller
{
    public function index($id)
    {
        $empmenu = EmployeeMenu::get();
        $menu = Menu::with('categories')->get();

        $makanan = [];

        foreach ($menu as $dt) {
            $empmenu_check = EmployeeMenu::where('menu_id', $dt->id)->where('employee_id', $id)->first();
            if (!$empmenu_check) {
                $makanan[] = ([
                    'nama_makanan' => $dt->name,
                    'menu_id' => $dt->id,
                    'price'   => $dt->price,
                    'categories_id'    => $dt->menu_category_id
                ]);
            }
        }
        
        $employee = Employee::findOrFail($id);
        $id_employee = $employee->id;
        $data = EmployeeMenu::with('menu')->where('employee_id', $employee->id)->get();
        return view('employeefood.index', compact('empmenu', 'menu', 'data', 'id_employee', 'employee', 'id', 'makanan'));
    }

    public function tambah(request $request)
    {
        $menu = Menu::find($request->menu);
        if ($menu) {
            $empmenu = new EmployeeMenu();
            $empmenu->employee_id = $request->id;
            if ($request->menu)
                $empmenu->menu_id = $request->menu;
            if ($request->notes != "") {
                $empmenu->notes = $request->notes;
            }
            $empmenu->quantity = $request->qty;
            $empmenu->isExtra = $request->extra;
            $empmenu->save();

            return "success|" . Menu::find($request->menu)->name . '|' . $request->qty . '|' . $request->notes;
        } else {
             return "Makanan tidak ditemukan! Harap coba lagi";
        }
    }

    public function ubah(request $request)
    {
        $empmenu = EmployeeMenu::find($request->id);
        if ($empmenu) {
            $menu = Menu::find($request->menu_edit);
            if ($menu) {
                $empmenu->menu_id = $request->menu_edit;
                $empmenu->quantity = $request->qty_edit;
                $empmenu->notes = $request->notes_edit;
                $empmenu->isExtra = $request->extra_edit;
                $empmenu->save();

                return 1;
                // return "success|" . Menu::find($request->menu)->name . '|' . $request->qty . '|' . $request->notes;
            } else {
                return "err| Makanan tidak ditemukan! Harap coba lagi";
            } 
        } else {
            return "err|Error! Data yang di ubah tidak sesuai / tidak valid";
        } 
    }

    public function detail(request $request)
    {
        $empmenu = EmployeeMenu::with('menu')->find($request->id);
        if ($empmenu) {
            $ran = rand();
            $content = '
            <div id="gambar_detail">
            <center>
            <img src="' . url('/storage/images/' . $empmenu->menu->image) . '" class="img-thumbnail" width="400" height="400">
            </center>
            </div><br>
            <label>Makanan :</label> <span id="menu' . $ran . '" hidden>' .  $empmenu->menu->id . '</span><span>' . $empmenu->menu->name . '</span> <br>
            <label>Harga :</label> <span id="harga'  . $ran . '">' . $empmenu->menu->price . '</span> <br>
            <label>Jumlah :</label> <span id="qty'  . $ran . '">' . $empmenu->quantity . '</span> <br>
            <label>Subtotal :</label> <span id="subtotal'  . $ran . '">' . ($empmenu->menu->price * $empmenu->quantity) . '</span> <br>
            <label>Catatan :</label> <span id="notes'  . $ran . '">' . $empmenu->notes . '</span> <br>';
            return "1|" . $content . "|CNT|" . $ran;
        } else {
            return "0|Data tidak ditemukan";
        } 
    }

    public function hapus(request $request)
    {
        $empmenu = EmployeeMenu::find($request->id);
        if ($empmenu) {
            $empmenu->delete();

            $empmenus = EmployeeMenu::get();
            $content = '
            <tr>
            <th>No</th>
            <th>Nama Makanan</th>
            <th>Jumlah</th>
            <th>Catatan</th>
            <th>Aksi</th>
            </tr>';
            $no = 0;
            foreach ($empmenus as $dt) {
                $no++;
                $content .= '
            <tr>
                <td>' . $no . '</td>
                <td id="menu' . $dt->id . '">' . Menu::find($empmenu->menu_id)->name . '</td>
                <td id="qty' . $dt->id . '">' . $dt->quantity . '</td>
                <td id="notes' . $dt->id . '">' . $dt->notes . '</td>
                <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" onclick="view_data(' . $dt->id . ');"><i class="fa fa-eye"></i></button>
                    <button type="button" class="btn btn-warning" onclick="edit_data(' . $dt->id . ');"><i class="fa fa-edit"></i></button>
                    <button type="button" class="btn btn-danger" onclick="delete_data(' . $dt->id . ');"><i class="fa fa-remove"></i></button>
                </div>
                </td>
            </tr>';
            }
            return $content;
        } else {
            return 0;
        } 
    }
}