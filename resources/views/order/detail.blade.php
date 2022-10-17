@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Detail Pesanan</h1>
    <p> Disini menampilkan Detail dari Pesanan </p>
    
      <br>
@stop

@section('content')

    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
          <th>No</th>
          <th>Nama Pegawai</th>
          <th>Nama Makanan</th> 
          <th>Catatan</th>
          <th>Kuantitas</th>
          <th>Harga</th> 
          <th>Status Ekstra</th>
        </tr>
        </thead>
      <tbody id="myTable" >
        @foreach($data as $dt)
        <tr class="data{{$dt->id}}">
        <td>{{$loop->iteration}}</td>  
        <td>{{ DB::table('users')->find(DB::table('employee')->where('id', $dt->employee_id)->first()->user_id)->name }}</td>
        <td>{{ DB::table('food')->where('id', $dt->food_id)->first()->name }}</td>
        <td>{{ $dt->notes }}</td>
        <td>{{ $dt->quantity }}</td>
        <td>Rp. {{number_format($dt->price) }}</td>
        <td> @if($dt->isExtra == 1) Ekstra
             @else Tidak Ekstra </td>
             @endif
        </tr>
        @endforeach
      </tbody>
@stop