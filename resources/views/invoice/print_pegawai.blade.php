@extends('adminlte::page')
@section('title', 'Momakan Praktis Gembira')

@section('content_header')

  <h1>Momakan Praktis Gembira</h1>
  <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
      <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a>
      <li><i class="fa fa-credit-card"></i> Faktur </li>
      <li><i class="fa fa-print"></i> Print Invoice </li>
  </ol>

@stop

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
        <p class="pull-left" style="margin-top: -10px"> From. <br>
        Momakan <br>
        Jl. Ki Hajar Dewantara No 1 <br>
        Phone : 082286959955 <br>
        Email : momakanpraktisgembira@gmail.com
        </p>

        <p class="pull-left" style="margin-left: 7.5px; margin-top: -10px"> To. <br>
          <b>{{$invoice->employee->user->name}}</b><br>
        {{ $contract->corporate->name }} <br>
        {{ $contract->corporate->address }} <br>
        Phone : {{ $contract->corporate->telp }} <br>
        Email : {{ $contract->corporate->user->email }}
        </p>

        <p class="pull-left" style="margin-left: 0px; margin-top: -10px"> Invoice #{{$invoice->nomor}} <br>
        {{$contract->contract_code}} <br>
        Month : {{ $invoice->month->format('F') . ' ' .$invoice->month->format('Y') }} 
        </p>
    </div>

    <div class="box-body">
      <table class="no-margin table table-hover" id="table">
          <thead>
          <tr>
            <th>Hari/Tanggal</th>
            <th>Pesanan</th>
            <th>Kuantitas</th>
            <th>Harga</th>
            <th>Subtotal</th>
          </tr>
          </thead>
        <tbody>
          @foreach($order as $dt)
            @foreach ($dt->order_detail_extra as $data)
              <tr>
                <td>{{ $dt->datetime->toDateString() }}</td> 
                <td>{{ $data->menu->name }}</td>
                <td>{{ $data->quantity }}</td>
                <td>{{ $data->price }}</td>   
                <td>{{ $data->sub_total }}</td>
              </tr>
              @php 
                $total += $data->sub_total
              @endphp
            @endforeach
          @endforeach
          @if($total > 0)
            <tr>
              <td colspan="3"></td>
              <td><b>Total</b></td>
              <td><b>{{ $total }}</b></td>
            </tr>
          @endif
      </tbody>
    </table>
    <br>
    <p class="" style="margin-top: -10px">
      Cara Pembayaran : <br>
      Transfer ke rek. mandiri <br>
      Norek : 
  </p> 
    </div>
</div>
@stop

@section('js')
<script>
  $(document).ready(function(){
    @if ($total > 0)
      window.print();
    @endif
  });
</script>


@stop