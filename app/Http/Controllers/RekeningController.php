<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rekening;
use Validator;
use Response;
use File;
use Illuminate\Support\Facades\Input;

class RekeningController extends Controller {

	public function index() {
		$data = Rekening::all();
		return view('rekening.index', compact('data'));
	}

	public function add(Request $request) {
		
		$rules = array(
			'bank_name' => 'required',
			'account_name' => 'required',
			'account_number' => 'required',
		);
		
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			
		} else {
			$bank_logo = $request->file('bank_logo');
            $ext = $bank_logo->getClientOriginalExtension();
            $name = time() . str_slug($bank_logo->getClientOriginalName(), '_') . '.' . $bank_logo->getClientOriginalExtension();
			$this->SavePhoto($bank_logo, public_path('storage/images/'));
			$data = new Rekening;
			$data->bank_name = $request->bank_name;
			$data->account_name = $request->account_name;
			$data->account_number = $request->account_number;
			$data->bank_logo = $name;
			$data->save();
		}
	}

	private function SavePhoto($bank_logo, $path) {
        $file = $bank_logo;
        $photo = time() . str_slug($file->getClientOriginalName(), '_') . '.' . $file->getClientOriginalExtension();
        $file->move($path, $photo);
    }

	public function edit(request $request) {
    	$rekening = Rekening::find($request->id);
        $rules = array(
            'edit_bank_name' => 'required',
            'edit_account_name' => 'required',
            'edit_account_number' => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
			return 2;
			
        } else if ($request->hasFile('gambar_baru')) {
            File::delete(public_path('storage/images/' . $rekening->bank_logo));
            $bank_logo = $request->file('gambar_baru');
            $ext = $bank_logo->getClientOriginalExtension();
            $name = time() . str_slug($bank_logo->getClientOriginalName(), '_') . '.' . $bank_logo->getClientOriginalExtension();
            $this->SavePhoto($bank_logo, public_path('storage/images/'));

            $rekening->bank_name = $request->edit_bank_name;
            $rekening->account_name = $request->edit_account_name;
            $rekening->account_number = $request->edit_account_number;
            $rekening->bank_logo = $name;
            $rekening->save();
			return 1;
			
        } else {
            $rekening->bank_name = $request->edit_bank_name;
            $rekening->account_name = $request->edit_account_name;
            $rekening->account_number = $request->edit_account_number;
            $rekening->save();
            return 1;
        }
	}

	public function delete(request $request) {
		$data = Rekening::find($request->id);
    	$data->delete();
    	return 3;
    }
}
