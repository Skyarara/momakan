<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Employee;
use App\Invoice;
use App\payment;
use App\Rekening;
use function GuzzleHttp\json_encode;

class InvoiceController extends Controller
{
    public function getMyInvoice()
    {
        //auth()->user()->employee->id
        if (auth()->user()->employee == null) return response()->json([
            'error'=> 'User Tidak Terdaftar sebagai employee perusahaan',
            'parameter'=> auth()->user()->id
        ],401);

        $invoices = Invoice::where('employee_id',auth()->user()->employee->id)->get();

        if ($invoices->count() == 0) return response()->json([
            'status'=>false,
            'message' => 'tidak berhasil mendapatkan invoice atau user tidak memiliki invoice',
            'data'=>[]
        ],404);

        foreach ($invoices as  $invoice) {
            
            //get detail order employee and total invoice
            $totalInvoice = 0;
            $orders = [];
            foreach ($invoice->detail->load('order') as $invoiceDetail) {
                $totalInvoice += $invoiceDetail->order->total_extra;
                $orderExtra = $invoiceDetail->order->order_detail_extra;

                //get extra item at the same order
                foreach ($orderExtra->load('menu') as $extra) {
                    $orders[] = ([
                        'date'=> $invoiceDetail->order->datetime->toDateString(),
                        'pesanan' => $extra->menu->name,
                        'kuantitas'=>$extra->quantity,
                        'harga'=>$extra->price,
                        'subtotal'=>$extra->quantity*$extra->price
                    ]);
                }
            }

            //check invoice payment
            // isbayar :
            // 0 = belum memiliki invoice, 1 = memiliki invoice dan sudah dibayar, 2 = memiliki invoice dan belum dibayar
            $isDibayar = 0;
            $invoicePayment = payment::where('invoice_id',$invoice->id)->first();
            if ($invoicePayment) {
                if ($invoicePayment->is_verified) {
                    $isDibayar = 1;
                }else{
                    $isDibayar = 2;
                }
            }
            
            //get rekening
            $rekeningAll = Rekening::all();
            $rekeningResponse = [];
            foreach ($rekeningAll as $rekening ) {
                $rekeningResponse[] = ([
                    'id'=>$rekening->id,
                    'bank_name'=>$rekening->bank_name,
                    'bank_logo'=> url("/storage/images/$rekening->bank_logo"),
                    'account_name'=>$rekening->account_name,
                    'account_number'=>$rekening->account_number,
                ]);
            }

            $data[] = ([
                'id'=> $invoice->id,
                'month'=>$invoice->month->format('M-Y'),
                'due_date'=>$invoice->due_date->toDateString(),
                'nomor'=> $invoice->nomor,
                'detail' =>$orders,
                'total_invoice'=> $totalInvoice,
                'isDibayar'=>$isDibayar,
                'rekening'=>$rekeningResponse
            ]);
        }
        
        $response = [
            'status'=>true,
            'message'=> "berhasil mendapatkan data invoice",
            'data'=>$data,

        ];
        return response(json_encode($response, JSON_UNESCAPED_SLASHES))->header('Content-Type', "application/json");
    }
}
