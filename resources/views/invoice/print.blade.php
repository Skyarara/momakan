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
        Samarinda, Kalimantan Timur <br>
        Phone : 082286959955 <br>
        Email : momakanpraktisgembira@gmail.com
        </p>

        <p class="pull-left" style="margin-left: 7.5px; margin-top: -10px"> To. <br>
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
          @foreach($cd_order as $dt)
          @if($dt['total'] != 0)
          <tr>
            <td>{{$dt['date']}}</td> 
            <td>Makan Siang {{$dt['name']}}</td>
            <td>{{$dt['qty']}}</td>
            <td>{{$dt['budget']}}</td>
            <td>{{$dt['total']}}</td>
            @endif   
        </tr>
        @endforeach
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td><b>Total</b></td>
          <td><b>{{ $total }}</b></td>
        </tr>
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
  window.addEventListener("afterprint", function(){
    history.back();
  });
  $("#body_print").ready(function(){
    window.print();
  });
</script>


@stop