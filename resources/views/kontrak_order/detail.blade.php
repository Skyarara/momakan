@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Detail Order</h1>
    <p>Menampilkan Data Order dan Data-data yang bersangkutan didalamnya</p>
    <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
      <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a>
      <li><i class="fa fa-dashboard"></i> Kontrak Pesanan</li>
      <li><i class="fa fa-dashboard active"></i> Detail Kontrak Pesanan</li>
  </ol>
@stop

@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="box box-danger">
      <div class="box-header with-border">
        <h1 class="box-title">Daftar Menu <small>Menampilkan semua data daftar menu yang bersangkutan di data ini</small></h1>
      </div>
      <div class="box-body">
        <table class="no-margin table table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>                         
              <th>Jumlah</th>
            </tr>
          </thead>
          <tbody id="table_menu">
            @foreach($data_menu as $dt)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $dt['nama'] }}</td>
                <td>{{ $dt['jumlah'] }} Porsi</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="col-md-8">
    <div class="box box-danger">
      <div class="box-header with-border">
        <h1 class="box-title">Daftar Pegawai <small>Menampilkan semua data daftar pegawai yang bersangkutan di data ini</small></h1>
      </div>
      <div class="box-body">
        <table class="no-margin table table-hover">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>                         
              <th>Jumlah</th>
              <th>Ekstra</th>
              <th>Detail</th>
            </tr>
          </thead>
          <tbody id="table_pegawai">
            @foreach($data_pegawai as $dt)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $dt['nama'] }}</td>
              <td>Rp. {{ number_format($dt['jumlah']) }}</td>
              <td>Rp. @if($dt['ekstra'] < 0)0 @else{{ number_format($dt['ekstra']) }}@endif</td>
              <td style="text-align:center">            
                <a href="{{ url('kontrak/order/' . $id . '/detail/pegawai/' . $dt['id']) }}"><button id="#" class="btn btn-danger btn-sm"><i class="fa fa-eye"></i></button></a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>                      
      </div>
    </div>
  </div>
</div>
@stop