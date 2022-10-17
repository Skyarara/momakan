@extends('adminlte::page')
@section('title', 'Kontrak Pesanan')

@section('content_header')

  <h1>Menampilkan Semua Daftar Menu Sesuai Order</h1>
  <p>Mengambil Data Menu termasuk Gambar dan nama Menu</p>
  <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
      <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a>
      <li><i class="fa fa-dashboard"></i> Kontrak Pesanan</li>
      <li><i class="fa fa-dashboard active"></i> Detail Pesanan</li>
  </ol>

@stop

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title"> Daftar Menu Berdasarkan Order </h1>       
    </div>
    <div class="box-body">
      <table class="no-margin table table-hover">
          <thead>
          <tr>
            <th>Nomor</th>
            <th>Nama Makanan</th>
            <th>Catatan</th>
            <th>Harga</th>
            <th>Kuantitas</th>            
          </tr>
          </thead>
        <tbody>
          @foreach($order_detail as $dt)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $dt->menu->name }}</td>
            <td>{{ $dt->notes }}</td>
            <td>Rp. {{ number_format($dt->price) }}</td>
            <td>{{ number_format($dt->quantity) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>    
    </div>
</div>
@stop