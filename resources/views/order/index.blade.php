@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Daftar Pesanan</h1>
    <p> Disini Menampilkan Daftar Pesanan </p>
    
      <br>
@stop

@section('content')
    
<a href="/contract/order/{{ $id }}/tambah" id="psn" class="btn btn-success btn-md">Buat Pesanan<a>

    <table class="table table-bordered table-hover table-striped" id="orderTable">
        <thead>
        <tr>
          <th>No</th>
          <th>Waktu dan Tanggal</th>
          <th>Total Harga</th> 
          <th>Aksi</th>
        </tr>
        </thead>
      <tbody id="myTable" >
        @foreach($data as $dt)
        <tr class="data{{$dt->id}}">
        <td>{{$loop->iteration}}</td>  
        <td>{{$dt->datetime}}</td>
        <td>Rp. {{number_format($dt->total_cost)}}</td>  
        <td> <a href="/contract/order/{{ $dt->id }}/detail" id="detil{{$loop->iteration}}" class="btn btn-info btn-md"><i class="fa fa-eye"></i><a> </td>
        </tr>
        @endforeach
      </tbody>
@stop

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">

/*
-> Dusk Function
   Hanya tersedia untuk pengetestan 
*/ 
var NewNumData = document.getElementById('orderTable');

function punchNewDatas()
{
    $("#detil" + NewNumData).click();
} 

</script>