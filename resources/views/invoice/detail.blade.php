@extends('adminlte::page')
<style>
.modal-ajax {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url("{{ url('/gif/' . 'Preloader_3.gif') }}")
                50% 50% 
                no-repeat;
}
body.loading {
    overflow: hidden;   
}
body.loading .modal {
    display: block;
}
</style>
@section('title', 'Detail Faktur')

@section('content_header')

<h1>Detail Faktur {{$invoice->contract->corporate->name}}</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a></li>
    <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a></li>
<li><a href="/kontrak/faktur/{{$invoice->contract->id}}"><i class="fa fa-credit-card"></i> Faktur </a></li>
</ol>

@stop

@section('content')
@if($employee_name != null)
<div class="box box-danger">
    <div class="box-header with-border">
    <h1 class="box-title"> Daftar Faktur Karyawan {{$employee_name}}</h1>
    </div>
    <div class="box-body">
      <table class="no-margin table table-hover">
          <thead>
          <tr>
            <th>Nomor</th>
            <th>Tanggal</th> 
            <th>Total Pembayaran</th>
          </tr>
          </thead>
        <tbody id="myTable">
          @foreach($invoiceByDate as $invoice)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $invoice['date'] }}</td>
            <td>Rp. {{ number_format($invoice['total'] ) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>    
    </div>
</div>
@else
<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title"> Daftar Faktur {{$invoice->contract->corporate->name}} </h1>
    </div>
    <div class="box-body">
      <table class="no-margin table table-hover">
          <thead>
          <tr>
            <th>Nomor</th>
            <th>Tanggal</th> 
            <th>Total Pembayaran</th>
            <th>Total Order</th>
          </tr>
          </thead>
        <tbody id="myTable">
          @foreach($invoiceByDate as $invoice)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $invoice['date'] }}</td>
            <td>Rp. {{ number_format($invoice['total'] ) }}</td>
            <td>{{$invoice['qty']}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>    
    </div>
</div>
@endif

@stop