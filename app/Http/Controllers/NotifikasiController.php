<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Menu;
use App\ContractEmployee;
use App\Employee;
use App\Contract;
use App\ContractDetail;
use App\Config;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Session;

class NotifikasiController extends Controller
{
    public function index()
    {
      $configs = Config::where('isLaporan', '0')->latest()->get();
      return view('notifikasi.index', compact('configs'));
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
            $data->isLaporan = 0;
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

    private function sendNotif($message, $title, $fcm_token)
    {
        $client = new Client();

        // headers
      $headers  = [
        'Authorization'   =>  'key=AIzaSyBeQAlvl7zDYR2JFrzPoeCmLq7YxOG-_jo',
        'Content-Type'    =>  'application/json'
      ];
 
      // body
      $body = [
        'registration_ids'  =>  $fcm_token,
        'notification'      =>  [
          'body'  =>  $message,
          'title' =>  $title
        ]
      ];      
      $response   = $client->post('https://fcm.googleapis.com/fcm/send', ['headers' => $headers, 'json' => $body]);
            
      return response()->json($response->getBody()->getContents());

    }
    public function send(request $request)
    {        
        $data = User::get();

        $data_send = [];
        foreach($data as $dt)
        {
            if($dt->fcm_token != "" || $dt->fcm_token != null)
            {
                $data_send[] = ($dt->fcm_token);
            }
        }

        $text_tosend = Config::find($request->pesan);
        if($text_tosend) {
            $this->sendNotif($text_tosend->letter_text, "Momakan", $data_send);

            Session::flash("message", "Berhasil mengirim notifikasi!");
            
            return redirect('/notifikasi');
        } else {
            Session::flash("messages", "Gagal mengirim notifikasi! Pilih pesan terlebih dahulu.");
            
            return redirect('/notifikasi');
        }
    }
    
}
