<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\payment;
use App\Invoice;
use App\Order;
use App\Contract;
use App\Corporate;
use File;

class PaymentController extends Controller
{
    public function index()
    {
        $payment = payment::with('invoice.contract.order')->get();
        $invoice_id_already = payment::pluck('invoice_id');
        $invoice_id = Invoice::whereNotIn('id', $invoice_id_already)->get();
        $invoice = Invoice::get();
        $view = [
            'payment' => $payment,
            'invoice_id' => $invoice_id,
            'invoice'   =>  $invoice
        ];
        return view('payment.index')->with($view);
    }

    public function get_invoice(Request $request)
    {
        $invoice = Invoice::with('detail')->find($request->invoice_id);
        $totalCost = 0;
        if ($invoice == null) {
            return 0;
        }
        if ($invoice->employee_id != null) {
            foreach ($invoice->detail->load('order') as  $invoiceDetail) {
                $totalCost += $invoiceDetail->order->total_extra;
            }
            $contract = $invoice->contract;
            $view =
                [
                    'id'    =>  $invoice->id,
                    'employee' => $invoice->employee->user->name,
                    'nomor' => $invoice->nomor,
                    'perusahaan' => $contract->corporate->name,
                    'bulan' => $invoice->month->toDateString(),
                    'total' => number_format($totalCost)
                ];

            return response()->json($view);
        }
        $contract = $invoice->contract;

        
        foreach ($invoice->detail->load('order') as  $invoiceDetail) {
            $totalCost += $invoiceDetail->order->total_cost;
            //cek apakah order tersebut memiliki extra
            if ($invoiceDetail->order->total_extra != 0) {
                $totalCost -= $invoiceDetail->order->total_extra;
            }
        }

        $view =
            [
                'employee' => 'false',
                'id'    =>  $invoice->id,
                'nomor' => $invoice->nomor,
                'perusahaan' => $contract->corporate->name,
                'bulan' => $invoice->month->toDateString(),
                'total' => number_format($totalCost)
            ];

        return response()->json($view);
    }

    public function tambah(Request $request)
    {
        if ($request->invoice_id == 0 || $request->date == null || $request->is_verified == null || $request->paid_amount == null || $request->file("receipt_photo") == null) {
            return 2;
        }
        $payment = payment::where('invoice_id', $request->invoice_id)->get();
        if ($payment->isNotEmpty()) {
            return 1;
        }
        
        $image = $request->file('receipt_photo');
        $ext = $image->getClientOriginalExtension();
        if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return 3;
        $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $ext;
        $image->move(public_path('storage/images/receipt_photo/'), $name);

        $invoice = new payment();
        $invoice->invoice_id = $request->invoice_id;
        $invoice->date = $request->date;
        $invoice->payment_number = rand();
        $invoice->is_verified = $request->is_verified;
        $invoice->paid_amount = $request->paid_amount;
        $invoice->receipt_photo = $name;
        $invoice->save();
    }

    public function edit(Request $request)
    {
        if ($request->edit_invoice_id == null || $request->edit_date == null || $request->is_verified == null || $request->paid_amount == null) {
            return 4;
        }
        $payment = payment::where('invoice_id', $request->edit_invoice_id)->get();
        $self = payment::where('id', $request->old_id)->where('invoice_id', $request->edit_invoice_id)->get();
        if ($self->isEmpty()) {
            if ($payment->isNotEmpty()) {
                return 1;
            }
        }
        $invoice = payment::find($request->old_id);

        //check if request has image upload
        if ($request->file('receipt_photo')) {
            $image = $request->file('receipt_photo');
            $ext = $image->getClientOriginalExtension();
            if ($ext != "jpg" && $ext != "png" && $ext != "PNG" && $ext != "JPG") return 3;
            File::delete(public_path('storage/images/receipt_photo/' . $invoice->receipt_photo));
            $name = time() . str_slug($image->getClientOriginalName(), '_') . '.' . $ext;
            $image->move(public_path('storage/images/receipt_photo/'), $name);
            $invoice->receipt_photo = $name;
        }

        $invoice->is_verified = $request->is_verified;
        $invoice->paid_amount = $request->paid_amount;
        $invoice->invoice_id = $request->edit_invoice_id;
        $invoice->date = $request->edit_date;
        $invoice->update();
    }

    public function delete(request $request)
    {
        $payment = payment::find($request->id);
        $payment->delete();
    }
}
