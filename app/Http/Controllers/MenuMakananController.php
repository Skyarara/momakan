<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Validator;
// use Response;
use Illuminate\Support\Facades\Input;
use App\Menu;
use App\MenuPackage;
use App\MenuCategory;
use App\MenuPlanDetail;
use App\Vendor;
use App\ScheduleMenu;
use DB;
use Storage;
use File;

class MenuMakananController extends Controller
{
    public function index(Request $request)
    {
        // $menu_package = MenuPackage::pluck('parent_id');
        $menu_makanan = Menu::paginate(10);
        $menu_category = MenuCategory::where('id', '>', 1)->get();

        // $schedule_menu = ScheduleMenu::pluck('menu_id');
        // $menu_buat_schedule = Menu::whereNotIn('id', $schedule_menu)->get();

        return view('menu.menu_makanan', [
            'menu_makanan' => $menu_makanan,
            'menu_category' => $menu_category
            // 'menu_buat_schedule' => $menu_buat_schedule
        ]);
    }
    public function tambah(request $request)
    {
        $menu_category = MenuCategory::find($request->kategori_makanan);
        if ($menu_category) {
            $image = $request->file('gambar');
            $ext = $image->getClientOriginalExtension();
            if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return "Gagal Menambahkan Data! Ektensi Gambar tidak benar/valid";
            $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
            $this->SavePhoto($image, public_path('storage/images/'));
            // $destinationPath = public_path('/images');
            // $image->move($destinationPath, $name);

            $menu_makanan = new Menu();
            $menu_makanan->menu_category_id = $request->kategori_makanan;
            $menu_makanan->name = $request->nama_makanan;
            $menu_makanan->description = $request->deskripsi;
            $menu_makanan->price = $request->harga;
            $menu_makanan->image = $name;
            $menu_makanan->save();

            return "success|" . $name . '|' . $request->nama_makanan . '|' .
                $request->deskripsi . '|Rp. ' . number_format($request->harga);
        } else return "Kategori Makanan tidak ditemukan! Harap coba lagi";
    }
    public function hapus(request $request)
    {
        $menu = Menu::find($request->id);
        $menu->name = $request->nama_makanan;
        $menu->description = $request->deskripsi;
        $menu->price = $request->harga;

        $menuplandetail = MenuPlanDetail::where('menu_id', $request->id)->first();
        if ($menuplandetail) return 2;

        if ($menu) {
            // File::delete(public_path('/images/') . $menu->image);
            File::delete(public_path('storage/images/' . $menu->image));
            $menu->delete();

            return '1|' . $request->nama_makanan . '|' .
                $request->deskripsi . '|' . $request->harga;
        } else return 0;
    }
    public function ubah(request $request)
    {
        $menu_makanan = Menu::find($request->id);
        if ($menu_makanan) {
            $menu_category = MenuCategory::find($request->kategori_makanan_edit);
            if ($menu_category) {
                $name = "";
                if ($request->hasFile('gambar_edit')) {

                    $image = $request->file('gambar_edit');
                    $ext = $image->getClientOriginalExtension();
                    if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return "err|Gagal Menambahkan Data! Ektensi Gambar tidak benar/valid";
                    // File::delete(public_path('/images/') . $menu_makanan->image);
                    File::delete(public_path('storage/images/' . $menu_makanan->image));
                    // $name = time() . '.' . $image->getClientOriginalExtension();
                    // $destinationPath = public_path('/images');
                    $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
                    $this->SavePhoto($image, public_path('storage/images/'));
                    $menu_makanan->image = $name;
                }
                $menu_makanan->menu_category_id = $request->kategori_makanan_edit;
                $menu_makanan->name = $request->nama_makanan_edit;
                $menu_makanan->description = $request->deskripsi_edit;
                $menu_makanan->price = $request->harga_edit;
                $menu_makanan->save();

                return '1|' . $name . '|' . $request->nama_makanan . '|' .
                    $request->deskripsi . '|Rp. ' . number_format($request->harga);
            } else return "err|Kategori Makanan tidak ditemukan! Harap coba lagi";
        } else return "err|Error! Data yang di ubah tidak sesuai / tidak valid";
    }
    public function detail(request $request)
    {
        $menu = Menu::find($request->id);
        if ($menu) {

            $ran = rand();
            $content = '
            <div id="gambar_detail">
                <center>
                <img src="' . url('/storage/images/' . $menu->image) . '" class="img-thumbnail" width="400" height="400">
                </center>
            </div><br><font size="4px">
            <label>Kategori Makanan :</label> <input type="text" hidden value="' . MenuCategory::find($menu->menu_category_id)->id . '" id="kategori_makanan_detail_' . $ran . '">' . MenuCategory::find($menu->menu_category_id)->name . '<br>
            <label>Nama Makanan :</label> ' . $menu->name . '<br>
            <label>Deskripsi Makanan :</label> ' . $menu->description . '<br>
            <label>Harga Makanan :</label> Rp.' . number_format($menu->price) . '<br><input type="text" hidden value="' . $ran . '"></font>';
            return "1|" . $content . "|CNT|" . $ran;
        } else return "0|Data tidak ditemukan";
    }

    private function SavePhoto($image, $path)
    {
        $file = $image;
        $photo = time() . str_slug($file->getClientOriginalName(), '_') . '.' . $file->getClientOriginalExtension();
        $file->move($path, $photo);
    }

    public function change(request $request)
    {
        $id = $request->id;
        $data = Menu::find($id);
        if ($data->isActive == 1) {
            $data->isActive = 0;
        } else {
            $data->isActive = 1;
        }
        $data->update();

        return 100;
    }

    // Schedule Menu_ID
    public function tambah_notifikasi(request $request)
    {
        $data = $request->data;
        $data_split = explode(',', $data);
        foreach ($data_split as $dt) {
            if ($dt == "") { } else {
                $menu = Menu::find($dt);
                if ($menu) {
                    $sm = ScheduleMenu::where('menu_id', $menu->id)->first();
                    if (!$sm) {
                        $sm             = new ScheduleMenu();
                        $sm->menu_id    = $menu->id;
                        $sm->save();
                    }
                }
            }
        }
        return response()->json(['success' => 'Data berhasil ditambahkan']);
    }
}
