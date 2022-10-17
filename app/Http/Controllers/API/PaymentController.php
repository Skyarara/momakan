<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\payment;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function addPayment(Request $request)
    {
        $invoice = Invoice::find($request->invoice_id);
        $paymentCheck = Payment::where('invoice_id',$request->invoice_id)->first();
        if ($paymentCheck){
            if ($paymentCheck->is_verified) {
                return response()->json([
                    'status'=>false,
                    'message'=>'Pembayaran telah dibuat sebelumnya dan telah kami verifikasi'
                ],400);
            }else{
                return response()->json([
                    'status'=>false,
                    'message'=>'Pembayaran telah dibuat sebelumnya, akan kami verifikasi segera'
                ],400);
            }
        }
        

        if (!$invoice) return response()->json([
            'status'=>false,
            'message'=>'invoice tidak ditemukan'
        ],404);

        $file = $request->file('receipt_photo');
        $photo = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return response()->json([
            'error'=>'file yang diupload harus berupa image'
        ],401);
        $path = public_path() . '/storage/images/receipt_photo/';
        $name = time() . str_slug($photo, '_') . '.' . $ext;
        $file->move($path, $name);

        $payment = new payment();
        $payment->invoice_id = $request->invoice_id;
        $payment->date = Carbon::now()->toDateString();
        $payment->payment_number = rand();
        $payment->is_verified = 0;
        $payment->paid_amount = 0;
        $payment->receipt_photo = $name;
        $payment->save();

        return response()->json([
            'status'=> true,
            'message'=> "berhasil mengupload bukti pembayaran"
        ]);
    }
}
