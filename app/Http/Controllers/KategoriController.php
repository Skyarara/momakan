<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MenuCategory;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;

class KategoriController extends Controller
{
    /**
     * Menampilkan Halaman Index Kategori
     */
    public function index()
    {
        $data = MenuCategory::paginate(10);

        return view('kategori.index', compact('data'));
    }

    public function add(Request $request)
    {
        $rules = array(
            'name' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toarray()));
        } else {
            $data = new MenuCategory();
            $data->name = $request->name;
            $data->save();

            return response()->json($data);
        }
    }

    public function edit(request $request)
    {
        $rules = [
            'name' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return 1;
        } else {
            $data = MenuCategory::find($request->id);
            $data->name = $request->name;
            $data->save();

            return response()->json($data);
        }
    }

    public function delete(request $request)
    {
        $data = MenuCategory::find($request->id);
        if ($data) {
            $data->delete();
            return response()->json(['status' => 'Success Deleted'], 200);
        } else {
            return 0;
        }
    }
}
