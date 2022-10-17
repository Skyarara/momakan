<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contract;
use App\Corporate;
use App\Invoice;
use App\InvoiceDetail;
use App\Order;
use App\Employee;
use App\OrderDetail;
use App\ContractEmployee;
use App\ContractDetail;
use Carbon\Carbon;
use App\Payment;
use Validator;
use Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Auth\Events\Failed;
use PDF;
use Illuminate\Support\Facades\Mail;
use Carbon\CarbonPeriod;
use PHPUnit\Framework\Constraint\IsEmpty;

class InvoiceController extends Controller
{
    public function index($id)
    {
        $contract = Contract::with('corporate', 'order')->find($id);
        $invoices = Invoice::with('contract.order', 'employee.user')->where('contract_id', $id)->get();
        $invoice = [];
        $invoice_pegawai = [];
        foreach ($invoices as $dt) {
            $date = explode('-', $dt->month->toDateString(), -1);
            $join_explode = join('-', $date);
            // dd($dt->detail->pluck('order_id')->implode(', '));
            $total_cost = Order::whereIn('id', $dt->detail->pluck('order_id'))->where('contract_id', $contract->id)->whereBetween('datetime', [$dt->month->startOfMonth()->toDateString(), $dt->month->endOfMonth()->toDateString()])->sum('total_cost');

            $total_extra_cost = Order::whereIn('id', $dt->detail->pluck('order_id'))->where('contract_id', $contract->id)->whereBetween('datetime', [$dt->month->startOfMonth()->toDateString(), $dt->month->endOfMonth()->toDateString()])->sum('total_extra');

            $total_extra = Order::whereIn('id', $dt->detail->pluck('order_id'))->where('contract_id', $contract->id)->where('employee_id', $dt->employee_id)->whereBetween('datetime', [$dt->month->startOfMonth()->toDateString(), $dt->month->endOfMonth()->toDateString()])->sum('total_extra');

            
            if ($dt->employee_id != null) {
                $invoice_pegawai[] = ([
                    'id'        => $dt->id,
                    'nomor'     =>  $dt->nomor,
                    'employee_id' => $dt->employee->user->name,
                    'date'      => $join_explode,
                    'batas'      => $dt->due_date->toDateString(),
                    'year'      => date('Y'),
                    'total'     => $total_extra
                ]);
            } else {
                $contract_budget = $total_cost - $total_extra_cost;
                $invoice[] = ([
                    'id'        => $dt->id,
                    'nomor'     =>  $dt->nomor,
                    'date'      => $join_explode,
                    'employee_id' => $dt->employee_id,
                    'batas'      => $dt->due_date->toDateString(),
                    'year'      => date('Y'),
                    'total'     => $contract_budget
                ]);
            }
        }

        $view =
            [
                'id'      => $id,
                'contract' => $contract,
                'invoice' => $invoice,
                'invoice_pegawai' => $invoice_pegawai
            ];

        return view('invoice.index')->with($view);
    }

    public function tambah(Request $request)
    {
        DB::beginTransaction();
        try {
            $contract = Contract::with('corporate', 'order')->find($request->id);
            $time = date('Y-m-d');
            $rules = array(
                'bulan' => 'required',
                'tanggal_akhir' => 'required'
            );
            $messages = [
                'bulan.required' => 'Field bulan kosong',
                'tanggal_akhir.required' => 'Field batas tanggal kosong',
            ];
            $validation = Validator::make($request->all(), $rules, $messages);
            $error_array = array();
            if ($validation->fails()) {
                foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                    $error_array[] = $messages;
                }
                $output = [
                    'error' => $error_array,
                ];
                return response()->json($output);
            } else {
                $explodes = explode('-', $request->bulan);
                $invoiceLast = Invoice::orderBy('nomor', 'DESC')->latest()->first();
                $invoice_code = $invoiceLast !== null ? $invoiceLast->nomor + 1  : 1001;

                $already = Invoice::where('contract_id', $contract->id)->whereMonth('month', $explodes[1])->whereYear('month', $explodes[0])->get();
                if ($already->isNotEmpty()) {
                    return 3;
                }
                $invoice = new Invoice();
                $invoice->month = $request->bulan . '-01';
                $invoice->due_date = $request->tanggal_akhir;
                $invoice->contract_id = $contract->id;
                $invoice->nomor = $invoice_code;
                $explode = explode('-', $invoice->month);
                $order = Order::where('contract_id', $contract->id)->whereMonth('datetime', $explode[1])->whereYear('datetime', $explode[0])->pluck('id');
                if ($order->isEmpty()) {
                    return 1;
                }
                $invoice->save();

                for ($i = 0; $i < count($order); $i++) {
                    $data = new InvoiceDetail;
                    $data->invoice_id = $invoice->id;
                    $data->order_id = $order[$i];
                    $data->save();
                }

                $order_employee = Order::where('contract_id', $contract->id)->whereMonth('datetime', $explode[1])->whereYear('datetime', $explode[0])->where('total_extra', '!=', 0);
                $employee_id = $order_employee->distinct()->pluck('employee_id');
                if ($employee_id->isNotEmpty()) {
                    for ($i = 0; $i < count($employee_id); $i++) {
                        $invoiceLast = Invoice::orderBy('nomor', 'DESC')->latest()->first();
                        $invoice_code = $invoiceLast !== null ? $invoiceLast->nomor + 1  : 1001;

                        $invoice_employee = new Invoice();
                        $invoice_employee->month = $invoice->month;
                        $invoice_employee->due_date = $invoice->due_date;
                        $invoice_employee->contract_id = $invoice->contract_id;
                        $invoice_employee->nomor = $invoice_code;
                        $invoice_employee->employee_id = $employee_id[$i];
                        $invoice_employee->save();

                        $order_extra_id = Order::where('contract_id', $contract->id)->whereMonth('datetime', $explode[1])->whereYear('datetime', $explode[0])->where('total_extra', '!=', 0)->where('employee_id', $invoice_employee->employee_id)->pluck('id');

                        // dd($order_extra_id);
                        
                        for ($a = 0; $a < count($order_extra_id); $a++) {
                            $data = new InvoiceDetail;
                            $data->invoice_id = $invoice_employee->id;
                            $data->order_id = $order_extra_id[$a];
                            $data->save();
                        }
                    }
                }
                $output = [
                    'error' => $error_array,
                ];
            }

            DB::commit();

            return response()->json($output);
        } catch (\Exception $e) {
            DB::rollBack();
            $output = [
                'error' => $e->getMessage()
            ];

            return response()->json($output);
        }
    }

    public function edit(Request $request)
    {
        // dd($request->all());
        $contract = Contract::with('corporate', 'order')->find($request->id);
        $time = date('Y-m-d');
        $rules = array(
            'bulan_edit' => 'required',
            'tanggal_akhir_edit' => 'required'
        );
        $messages = [
            'bulan.required' => 'Field bulan kosong',
            'tanggal_akhir.required' => 'Field batas tanggal kosong',
        ];
        $validation = Validator::make($request->all(), $rules, $messages);
        $error_array = array();
        if ($validation->fails()) {
            return 4;
        } else {
            $explodes = explode('-', $request->bulan_edit);
            $already = Invoice::whereMonth('month', $explodes[1])->whereYear('month', $explodes[0])->get();
            $self = Invoice::where('id', $request->old_id)->whereMonth('month', $explodes[1])->whereYear('month', $explodes[0])->get();
            if ($self->isEmpty()) {
                if ($already->isNotEmpty()) {
                    return 3;
                }
            }
            $invoice = Invoice::find($request->old_id);
            $invoice->month = $request->bulan_edit . '-01';
            $invoice->due_date = $request->tanggal_akhir_edit;
            $invoice->contract_id = $contract->id;
            $explode = explode('-', $invoice->month);
            $order = Order::where('contract_id', $contract->id)->whereMonth('datetime', $explode[1])->whereYear('datetime', $explode[0])->pluck('id');
            if ($order->isEmpty()) {
                return 1;
            }
            //delete invoice  detai lama
            $old_invoice_detail = InvoiceDetail::where('invoice_id', $request->old_id)->delete();
            //invoice detail update baru
            $invoice->update();
            for ($i = 0; $i < count($order); $i++) {
                $data = new InvoiceDetail;
                $data->invoice_id = $invoice->id;
                $data->order_id = $order[$i];
                $data->save();
            }
        }
    }

    public function detail($id)
    {
        $invoice = Invoice::with(['contract.corporate','detail'])->find($id);
        $totalCost = 0;
        $invoiceByDate = [];
        $employee_name = null;
        if ($invoice->employee_id != null) {
            $employee_name = $invoice->employee->user->name;
            foreach ($invoice->detail->load('order') as  $invoiceDetail) {
                $index = $invoiceDetail->order->datetime->toDateString();
                if ( isset($invoiceByDate[$index])) {
                    $invoiceByDate[$index]['total'] += $invoiceDetail->order->total_extra;
                    $invoiceByDate[$index]['qty']++;
                }else{
                    $invoiceByDate[$index] = (
                        [
                            'date' =>  $invoiceDetail->order->datetime->toDateString(),
                            'qty' => 1,
                            'total' =>$invoiceDetail->order->total_extra, 
                        ]
                    );
                }
            }
            return view('invoice.detail', compact('invoiceByDate', 'invoice', 'employee_name'));
        }
        foreach ($invoice->detail->load('order') as  $invoiceDetail) {
            $totalCost += $invoiceDetail->order->total_cost;
            $index = $invoiceDetail->order->datetime->toDateString();
            if ( isset($invoiceByDate[$index])) {
                $invoiceByDate[$index]['total'] += $invoiceDetail->order->total_cost;
                $invoiceByDate[$index]['qty']++;
            }else{
                $invoiceByDate[$index] = (
                    [
                        'date' =>  $invoiceDetail->order->datetime->toDateString(),
                        'qty' => 1,
                        'total' =>$invoiceDetail->order->total_cost, 
                    ]
                );
            }
            //cek apakah order tersebut memiliki extra
            if ($invoiceDetail->order->total_extra != 0) {
                $totalCost -= $invoiceDetail->order->total_extra;
                $invoiceByDate[$index]['total'] -= $invoiceDetail->order->total_extra;
            }
        }
        return view('invoice.detail', compact('invoiceByDate', 'invoice', 'employee_name'));
    }

    public function delete(request $request)
    {
        $invoice = Invoice::find($request->id);
        $pembayaran = Payment::where('invoice_id', $invoice->id)->get();
        if ($pembayaran->isNotEmpty()) {
            return 2;
        }
        $old_invoice_detail = InvoiceDetail::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();
    }
    public function printview($id)
    {
        $invoice = Invoice::with('contract.corporate')->find($id);
        $totalCost = 0;
        $invoiceByDatenDetailContract = [];
        foreach ($invoice->detail as  $invoiceDetail) {
            $totalCost += $invoiceDetail->order->total_cost;
            $index = $invoiceDetail->order->datetime->toDateString()."|".$invoiceDetail->order->contract_detail_id;
            if ( isset($invoiceByDatenDetailContract[$index])) {
                $invoiceByDatenDetailContract[$index]['total'] += $invoiceDetail->order->total_cost;
                $invoiceByDatenDetailContract[$index]['qty']++;
            }else{
                $invoiceByDatenDetailContract[$index] = (
                    [
                        'id' =>$invoiceDetail->order->contractDetail->id,
                        'name' => $invoiceDetail->order->contractDetail->contract_detail_name,
                        'budget' => $invoiceDetail->order->contractDetail->budget,
                        'date' =>  $invoiceDetail->order->datetime->toDateString(),
                        'qty' => 1,
                        'total' =>$invoiceDetail->order->total_cost, 
                    ]
                );

            }
            
            //cek apakah order tersebut memiliki extra
            if ($invoiceDetail->order->total_extra != 0) {
                $totalCost -= $invoiceDetail->order->total_extra;
                $invoiceByDatenDetailContract[$index]['total'] -= $invoiceDetail->order->total_extra;
            }
        }
        ksort($invoiceByDatenDetailContract);
        $view =
            [
                'id'      => $id,
                'cd_order' => $invoiceByDatenDetailContract,
                'invoice' => $invoice,
                'contract' => $invoice->contract,
                'total' => $totalCost
            ];
         return view('invoice.print')->with($view);
    }

    public function printview_pegawai($id)
    {
        $invoice = Invoice::with('contract.corporate')->find($id);
        $explode = explode('-', $invoice->month);
        $order = $invoice->contract->order()
            ->whereMonth('datetime', $explode[1])
            ->whereIn('id', $invoice->detail->pluck('order_id'))
            ->whereYear('datetime', $explode[0])
            ->where('employee_id', $invoice->employee_id)
            ->where('total_extra', '!=', 0)
            ->with('order_detail_extra')
            ->orderBy('datetime', 'asc')
            ->get();
        $total = 0;
        $view =
            [
                'id'      => $id,
                'invoice' => $invoice,
                'contract' => $invoice->contract,
                'order' => $order,
                'total'    => $total
            ];
        return view('invoice.print_pegawai')->with($view);
    }

    public function sendEmail($id)
    {
        $invoice = Invoice::with('contract.corporate')->find($id);
        $totalCost = 0;
        $invoiceByDatenDetailContract = [];
        foreach ($invoice->detail as  $invoiceDetail) {
            $totalCost += $invoiceDetail->order->total_cost;
            $index = $invoiceDetail->order->datetime->toDateString()."|".$invoiceDetail->order->contract_detail_id;
            if ( isset($invoiceByDatenDetailContract[$index])) {
                $invoiceByDatenDetailContract[$index]['total'] += $invoiceDetail->order->total_cost;
                $invoiceByDatenDetailContract[$index]['qty']++;
            }else{
                $invoiceByDatenDetailContract[$index] = (
                    [
                        'id' =>$invoiceDetail->order->contractDetail->id,
                        'name' => $invoiceDetail->order->contractDetail->contract_detail_name,
                        'budget' => $invoiceDetail->order->contractDetail->budget,
                        'date' =>  $invoiceDetail->order->datetime->toDateString(),
                        'qty' => 1,
                        'total' =>$invoiceDetail->order->total_cost, 
                    ]
                );

            }
            
            //cek apakah order tersebut memiliki extra
            if ($invoiceDetail->order->total_extra != 0) {
                $totalCost -= $invoiceDetail->order->total_extra;
                $invoiceByDatenDetailContract[$index]['total'] -= $invoiceDetail->order->total_extra;
            }
        }
        ksort($invoiceByDatenDetailContract);
        $view =
            [
                'id'      => $id,
                'cd_order' => $invoiceByDatenDetailContract,
                'invoice' => $invoice,
                'contract' => $invoice->contract,
                'total' => $totalCost
            ];
        $nameFile = "invoice ".$invoice->contract->corporate->name." Bulan ".$invoice->month->format('F').".pdf";
        $email_corporate = $invoice->contract->corporate->user->email;
        $corporate_name = $invoice->contract->corporate->name;
        
        $pdf = PDF::loadView('invoice.invoice_pdf',$view);
        $content= [
            'title'=>'Hi, '.$invoice->contract->corporate->name,
            'content'=>'Kami telah mengirimkan invoice untuk bulan '.$invoice->month->format('F')
        ];
        $response = [];
        try{
            Mail::send('invoice.email', $content, function ($message)use($pdf,$nameFile,$email_corporate,$corporate_name) {
                $message->to($email_corporate, $corporate_name);
                $message->subject('momakan invoice');
                $message->attachData($pdf->output(), $nameFile);
            });
        }catch(JWTException $exception){
            $response['serverstatuscode'] = "0";
            $response['serverstatusdes'] = $exception->getMessage();
        }
        if (Mail::failures()) {
            $response['description']  =   "Faktur Gagal Terkirim";
            $response['statuscode']  =   "0";
       }else{
            $response['description']  =   "Faktur Berhasil Terkirim";
            $response['statuscode']  =   "1";
       }
        return response()->json($response);
    }
}
