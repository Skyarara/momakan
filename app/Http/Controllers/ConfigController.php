<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Config;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;

//menampilkan index

class ConfigController extends Controller
{
    public function index()
    {
        $config = Config::where('isLaporan', '1')->latest()->get();

        return view('config_laporan.index', compact('config'));
    }


    public function add(Request $request)
    {
        $rules = array(
            'isi' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toarray()));
        } else {
            $data = new Config();
            $data->letter_text = $request->isi;
            $data->isLaporan = 1;
            $data->save();
        }
    }

    public function edit(request $request)
    {
        $rules = [
            'isi_edit' => 'required',
        ];
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->getMessageBag()->toarray()));
        } else {
            $data = Config::find($request->id);
            $data->letter_text = $request->isi_edit;
            $data->save();

            return response()->json($data);
        }
    }

    public function delete(request $request)
    {
        $data = Config::find($request->id);
        $data->delete();
    }
}
