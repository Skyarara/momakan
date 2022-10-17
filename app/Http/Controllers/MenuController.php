<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Menu;
use App\MenuPackage;
use App\Vendor;
use App\MenuCategory;
use DB;
use File;
use App\ContractDetailMenu;
use Illuminate\Support\Facades\Redirect;



class MenuController extends Controller
{

    public function makanan_paket()

    {
        $menu = MenuPackage::select('parent_id')->distinct()->get();
        return view('menu.makanan_paket', compact('menu'));
    }

    public function tambah_paket_page()
    {
        $menu_package = MenuPackage::pluck('parent_id');
        $vendor = Vendor::get();
        $result = [];
        $data_kategori = MenuCategory::where('id', '>', 1)->get();
        foreach ($data_kategori as $dt) {
            $makanan = [];
            $data_makanan = Menu::whereNotIn('id',  $menu_package)->where('menu_category_id', $dt->id)->get();
            if ($data_makanan) {
                foreach ($data_makanan as $dts) {
                    $makanan[] = ([
                        'id'            => $dts->id,
                        'harga'         => number_format($dts->price),
                        'nama_makanan'  => $dts->name
                    ]);
                }
            }
            $result[] = ([
                'nama_kategori'  => $dt->name,
                'makanan'        => $makanan
            ]);
        }
        $jumlahKategori = $data_kategori->count();
        return view('menu.tambah_paket', compact('result', 'jumlahKategori', 'vendor'));
    }

    public function tambah_paket(request $request)
    {
        if ($request->makanan == "") {
            Session::flash('errors', 'Tidak ada makanan yang di pilih!');
            return redirect('/makanan_paket');
        } elseif (strpos($request->makanan, ',') == false) {
            Session::flash('errors', 'Pengisian makanan tidak valid');
            return redirect('/makanan_paket');
        } else {
            $image = $request->file('gambar');
            $ext = $image->getClientOriginalExtension();
            if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return "Gagal Menambahkan Data! Ektensi Gambar tidak benar/valid";
            $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
            $this->SavePhoto($image, public_path('storage/images/'));
            $new_menu = new Menu;
            $new_menu->vendor_id = $request->vendor;
            $new_menu->name = $request->name;
            $new_menu->description = $request->deskripsi;
            $new_menu->price = $request->harga;
            $new_menu->image = $name;
            $new_menu->menu_category_id = 1;
            $new_menu->save();

            $lm = $request->makanan;
            $lms = explode(',', $lm);
            for ($i = 0; $i < count($lms) - 1; $i++) {
                $id = $lms[$i];
                $menu = Menu::find($id);
                if ($menu) {
                    $menu_package                        = new MenuPackage();
                    $menu_package->parent_id   = $new_menu->id;
                    $menu_package->menu_id               = $id;
                    $menu_package->save();
                }
            }

            Session::flash('success', 'Berhasil Menambahkan Makanan');
            return redirect('/makanan_paket');
        }
    }

    public function ubah_page($id)
    {
        $old_data = Menu::find($id);
        $menu_package = MenuPackage::pluck('parent_id');
        $vendor = Vendor::get();
        $result = [];
        $data_kategori = MenuCategory::where('id', '>', 1)->get();
        $old_package = MenuPackage::where('parent_id', $old_data->id)->get();
        $list_makanan = null;
        foreach ($old_package as $dt) {
            $list_makanan .= $dt->menu_id . ',';
        }
        foreach ($data_kategori as $dt) {
            $makanan = [];
            $data_makanan = Menu::whereNotIn('id',  $menu_package)->where('menu_category_id', $dt->id)->get();
            if ($data_makanan) {
                foreach ($data_makanan as $dts) {
                    $makanan[] = ([
                        'id'            => $dts->id,
                        'harga'         => number_format($dts->price),
                        'nama_makanan'  => $dts->name
                    ]);
                }
            }
            $result[] = ([
                'nama_kategori'  => $dt->name,
                'makanan'        => $makanan
            ]);
        }
        $jumlahKategori = $data_kategori->count();
        return view('menu.ubah_paket', compact('result', 'jumlahKategori', 'vendor', 'old_data', 'list_makanan'));
    }

    public function ubah(Request $request, $id)
    {
        if ($request->makanan == "") {
            Session::flash('errors', 'Tidak ada makanan yang di pilih!');
            return redirect('/makanan_paket');
        }
        $menu = Menu::find($request->id);
        if ($request->hasFile('gambar_edit')) {
            File::delete(public_path('storage/images/' . $menu->image));
            $image = $request->file('gambar_edit');
            $ext = $image->getClientOriginalExtension();
            if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return "Gagal Menambahkan Data! Ektensi Gambar tidak benar/valid";
            $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
            $this->SavePhoto($image, public_path('storage/images/'));

            $data = array(
                'vendor_id' => $request->input('vendor'),
                'name' => $request->input('name'),
                'description' => $request->input('deskripsi'),
                'price' => $request->input('harga'),
                'image' => $name
            );
            Menu::where('id', $id)
                ->update($data);

            $menu_id = Menu::where('id', $id)->value('id');
            $menu_package = MenuPackage::where('parent_id', $menu_id)->get();
            foreach ($menu_package as $dt) {
                $dt->delete();
            }
            $lm = $request->makanan;
            $lms = explode(',', $lm);
            for ($i = 0; $i < count($lms) - 1; $i++) {
                $id = $lms[$i];
                $menu = Menu::find($id);
                if ($menu) {
                    $menu_package                        = new MenuPackage();
                    $menu_package->parent_id             = $menu_id;
                    $menu_package->menu_id               = $id;
                    $menu_package->save();
                }
            }
            Session::flash('success', 'Berhasil Mengubah Makanan');
            return redirect('/makanan_paket');
        } else {
            $data = array(
                'vendor_id' => $request->input('vendor'),
                'name' => $request->input('name'),
                'description' => $request->input('deskripsi'),
                'price' => $request->input('harga')
            );
            Menu::where('id', $id)
                ->update($data);

            $menu_id = Menu::where('id', $id)->value('id');
            $menu_package = MenuPackage::where('parent_id', $menu_id)->get();
            foreach ($menu_package as $dt) {
                $dt->delete();
            }
            $lm = $request->makanan;
            $lms = explode(',', $lm);
            for ($i = 0; $i < count($lms) - 1; $i++) {
                $id = $lms[$i];
                $menu = Menu::find($id);
                if ($menu) {
                    $menu_package                        = new MenuPackage();
                    $menu_package->parent_id             = $menu_id;
                    $menu_package->menu_id               = $id;
                    $menu_package->save();
                }
            }
            Session::flash('success', 'Berhasil Mengubah Makanan');
            return redirect('/makanan_paket');
        }
    }

    public function delete(request $request)
    {
        $cdm = ContractDetailMenu::where('menu_id', $request->id)->first();
        if ($cdm) return 2;
        $menu = Menu::find($request->id);
        File::delete(public_path('storage/images/' . $menu->image));
        $menu->delete();
        return 3;
    }

    private function SavePhoto($image, $path)
    {
        $file = $image;
        $photo = time() . str_slug($file->getClientOriginalName(), '_') . '.' . $file->getClientOriginalExtension();
        $file->move($path, $photo);
    }

    public function read($id)
    {
        $old_data = Menu::with('vendor')->find($id);
        $paket = MenuPackage::with('menu')->find($id)->all('menu_id');

        return view('menu.detail', compact('old_data', 'paket'));
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
    }
}
