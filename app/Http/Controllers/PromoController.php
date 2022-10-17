<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Promo;
use Validator;
use Response;
use File;
use Illuminate\Support\Facades\Input;


class PromoController extends Controller
{
    public function index()
    {
        $promo = Promo::all();
        return view('promo.index', compact('promo'));
    }

    public function add(Request $request)
    {
        $rules = array(
            'title' => 'required',
            'deskripsi' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            
        } else {
            $image = $request->file('gambar');
            $ext = $image->getClientOriginalExtension();
            $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
            $this->SavePhoto($image, public_path('storage/images/'));
            $data = new Promo();
            $data->title = $request->title;
            $data->description = $request->deskripsi;
            $data->image = $name;
            $data->save();
        }
    }

    private function SavePhoto($image, $path)
    {
        $file = $image;
        $photo = time() . str_slug($file->getClientOriginalName(), '_') . '.' . $file->getClientOriginalExtension();
        $file->move($path, $photo);
    }

    public function delete(request $request)
    {
        $promo = Promo::find($request->id);
        File::delete(public_path('storage/images/' . $promo->image));
        $promo->delete();
        return 3;
    }

    public function ubah(Request $request)
    {
        $promo = Promo::find($request->id);
        $rules = array(
            'edit_title' => 'required',
            'edit_deskripsi' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return 2;
        } else if ($request->hasFile('gambar_baru')) {
            File::delete(public_path('storage/images/' . $promo->image));
            $image = $request->file('gambar_baru');
            $ext = $image->getClientOriginalExtension();
            $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $image->getClientOriginalExtension();
            $this->SavePhoto($image, public_path('storage/images/'));

            $promo->title = $request->edit_title;
            $promo->description = $request->edit_deskripsi;
            $promo->image = $name;
            $promo->save();
            return 1;
        } else {
            $promo->title = $request->edit_title;
            $promo->description = $request->edit_deskripsi;
            $promo->save();
            return 1;
        }
    }
}
