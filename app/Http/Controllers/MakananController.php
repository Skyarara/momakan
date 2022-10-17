<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Makanan;
use App\KategoriMakanan;
use App\Vendor;
use App\Contract;
use Storage;
use File;

class MakananController extends Controller
{
    public function index()
    {
        $food = Makanan::get();
        $kategori_makanan = KategoriMakanan::get();
        $vendor = Vendor::get();
        return view('makanan.index', [
            'food' => $food,
            'food_category' => $kategori_makanan,
            'vendor' => $vendor
        ]);
    }
    public function tambah(request $request)
    {
        $kategori_makanan = KategoriMakanan::find($request->kategori_makanan);
        if ($kategori_makanan) {
            $vendor = Vendor::find($request->vendor);
            if ($vendor) {
                $image = $request->file('gambar');
                $ext = $image->getClientOriginalExtension();
                if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return "Gagal Menambahkan Data! Ektensi Gambar tidak benar/valid";
                $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
                $this->SavePhoto($image, public_path('storage/images/'));
                $food = new Makanan();
                $food->food_category_id = $request->kategori_makanan;
                $food->vendor_id = $request->vendor;
                $food->name = $request->nama_makanan;
                $food->description = $request->deskripsi;
                $food->price = $request->harga;
                $food->image = $name;
                $food->isPackage = $request->paket;
                $food->save();

                $paket = "Bukan Paket";
                if ($request->paket == 1) {
                    $paket = "Paket";
                }

                return "success|" . $name . '|' . $request->nama_makanan . '|' .
                    $request->deskripsi . '|Rp. ' . number_format($request->harga) . '|' .
                    $paket . '|' . Makanan::latest()->first()->id;
            } else return "Vendor tidak ditemukan! Harap coba lagi";
        } else return "Kategori Makanan tidak ditemukan! Harap coba lagi";
    }
    public function hapus(request $request)
    {
        $makanan = Makanan::find($request->id);
        if ($makanan) {
            $contract = Contract::where('initial_food_id', $request->id)->first();
            if ($contract) return 2;
            //Storage::disk('local')->delete('/public/images/' . $makanan->image);
            File::delete(public_path('storage/images/' . $makanan->image));
            $makanan->delete();

            $makanan_list = Makanan::get();
            $content = '
            <tr>
                <th>No</th>
                <th>Gambar</th>
                <th>Nama Makanan</th>
                <th style="width: 35%">Deskripsi</th>
                <th>Harga</th>
                <th>Status Paket</th>
                <th>Action</th>
            </tr>';
            $no = 0;
            foreach ($makanan_list as $dt) {
                $no++;
                $status_paket = "Paket";
                if ($dt->isPackage == false) {
                    $status_paket = "Bukan Paket";
                }
                $content .= '
                <tr>
                    <td>' . $no . '</td>
                    <td id="image' . $dt->id . '"><img src="' . asset('storage/images/' . $dt->image) . '" class="img-thumbnail" width="75" height="75"></img></td>
                    <td id="name' . $dt->id . '">' . $dt->name . '</td>
                    <td id="desc' . $dt->id . '">' . $dt->description . '</td>
                    <td id="price' . $dt->id . '">Rp. ' . number_format($dt->price) . '</td>
                    <td>' . $status_paket . '</td>
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
        } else return 0;
    }
    public function ubah(request $request)
    {
        $makanan = Makanan::find($request->id);
        if ($makanan) {
            $kategori_makanan = KategoriMakanan::find($request->kategori_makanan_edit);
            if ($kategori_makanan) {
                $vendor = Vendor::find($request->vendor_edit);
                if ($vendor) {
                    $name = "";
                    if ($request->hasFile('gambar_edit')) {

                        $image = $request->file('gambar_edit');
                        $ext = $image->getClientOriginalExtension();
                        if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return "err|Gagal Menambahkan Data! Ektensi Gambar tidak benar/valid";
                        //Storage::disk('local')->delete('/public/images/' . $makanan->image);

                        File::delete(public_path('storage/images/' . $makanan->image));

                        // $name = time() . '.' . $image->getClientOriginalExtension();
                        // $destinationPath = public_path('/images');
                        // Storage::disk('local')->put('/public/images/' . $name, File::get($image));
                        $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
                        $this->SavePhoto($image, public_path('storage/images/'));

                        $makanan->image = $name;
                    }
                    $makanan->food_category_id = $request->kategori_makanan_edit;
                    $makanan->vendor_id = $request->vendor_edit;
                    $makanan->name = $request->nama_makanan_edit;
                    $makanan->description = $request->deskripsi_edit;
                    $makanan->price = $request->harga_edit;
                    $makanan->isPackage = $request->paket_edit;
                    $makanan->save();

                    $makanan_list = Makanan::get();
                    $content = '
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Makanan</th>
                        <th style="width: 35%">Deskripsi</th>
                        <th>Harga</th>
                        <th>Status Paket</th>
                        <th>Action</th>
                    </tr>';
                    $no = 0;
                    foreach ($makanan_list as $dt) {
                        $no++;
                        $status_paket = "Paket";
                        if ($dt->isPackage == false) {
                            $status_paket = "Bukan Paket";
                        }
                        $content .= '
                        <tr>
                            <td>' . $no . '</td>
                            <td id="image' . $dt->id . '"><img src="' . asset('storage/images/' . $dt->image) . '" class="img-thumbnail" width="75" height="75"></img></td>
                            <td id="name' . $dt->id . '">' . $dt->name . '</td>
                            <td id="desc' . $dt->id . '">' . $dt->description . '</td>
                            <td id="price' . $dt->id . '">Rp. ' . number_format($dt->price) . '</td>
                            <td>' . $status_paket . '</td>
                            <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="view_data(' . $dt->id . ');"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-warning" onclick="edit_data(' . $dt->id . ');"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" onclick="delete_data(' . $dt->id . ');"><i class="fa fa-remove"></i></button>
                            </div>
                            </td>
                        </tr>';
                    }
                    return '1|' . $content;
                } else return "err|Vendor tidak ditemukan! Harap coba lagi";
            } else return "err|Kategori Makanan tidak ditemukan! Harap coba lagi";
        } else return "err|Error! Data yang di ubah tidak sesuai / tidak valid";
    }
    public function detail(request $request)
    {
        $makanan = Makanan::find($request->id);
        if ($makanan) {
            $paket = "0";
            if ($makanan->isPackage) $paket = "1";
            $ran = rand();
            $content = '
            <div id="gambar_detail">
                <center>
                <img src="' . url('/images/' . $makanan->image) . '" class="img-thumbnail" width="400" height="400">
                </center>
            </div><br><font size="4px">
            <label>Kategori Makanan :</label> <input type="text" hidden value="' . KategoriMakanan::find($makanan->food_category_id)->id . '" id="kategori_makanan_detail_' . $ran . '">' . KategoriMakanan::find($makanan->food_category_id)->name . '<br>
            <label>Nama Vendor :</label> <input type="text" hidden value="' . Vendor::find($makanan->vendor_id)->id . '" id="vendor_detail_' . $ran . '">' . Vendor::find($makanan->vendor_id)->name . '<br>
            <label>Alamat Vendor :</label> ' . Vendor::find($makanan->vendor_id)->address . '<br>
            <label>Tagline Vendor :</label> ' . Vendor::find($makanan->vendor_id)->tagline . '<br>
            <label>Nama Makanan :</label> ' . $makanan->name . '<br>
            <label>Deskripsi Makanan :</label> ' . $makanan->description . '<br>
            <label>Harga Makanan :</label> Rp.' . number_format($makanan->price) . '<br><input type="text" hidden value="' . $paket . '" id="paket_detail_' . $ran . '"></font>';
            return "1|" . $content . "|CNT|" . $ran;
        } else return "0|Data tidak ditemukan";
    }

    private function SavePhoto($image, $path)
    {
        $file = $image;
        $photo = time() . str_slug($file->getClientOriginalName(), '_') . '.' . $file->getClientOriginalExtension();
        $file->move($path, $photo);
    }
}
