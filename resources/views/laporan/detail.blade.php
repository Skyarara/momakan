@extends('adminlte::page')

@section('title', 'Mencetak Kontrak')

@section('content_header')
  @stop
  @section('content')
  <div id="body_print">
    <img src={{asset('images/logo.png')}} class="center" style="display: block; margin-left:auto; margin-right:auto; width:300px;"> 
    <h4 style="text-align:center;">Alamat : Sempaja Lestari Indah</h4>
    <h4 style="text-align:center;">Telp : 0812 5341 1212</h4>
    <hr>
    <h3 style="text-align:center;"><b>Laporan Informasi Perusahan</b></h3>  
    <table border="0">
      <tbody>
          <tr>
              <td><h4>Nama Perusahaan </h4></td>
              <td style="width: 30%;"><h4> : </h4></td>
              <td style="width: 30%;"><h4>{{$data->corporate->name}}</h4></td>
          </tr>
    
          <tr>
              <td><h4>Kode Kontrak <h4></td>
              <td style="width: 30%;"><h4> : </h4></td>
              <td style="width: 30%;"><h4>{{$data->contract_code}}</h4></td>
          </tr>
    
          <tr>
              <td><h4>Tanggal Tagihan </h4></td>
              <td style="width: 30%;"><h4> : </h4></td>
              <td style="width: 30%;">{{ $date_start }} - {{ $date_end }}</h4></td>
          </tr>
    
          <tr>
              <td><h4>Total Tagihan </h4></td>
              <td style="width: 30%;"><h4> : </h4></td>
              <td style="width: 30%;"><h4>Rp. {{ number_format($total_semua) }}</h4></td>
          </tr>
    
      </tbody>
    </table>
    <hr>
    @if($config_laporan)<h4 style="margin-bottom: 5px;">{{ $config_laporan->letter_text }}</h4>@endif
    <h3 style="text-align:center;"><b>Laporan Pengeluaran</b></h3>     
    <table class="table table-bordered table-hover">
      <thead>
      <tr>
        <th>Total Perusahaan</th>
        <th>Total Pegawai</th> 
        <th>Total Semua</th>    
      </tr>
      </thead>
    <tbody id="myTable">  
      <tr>
      <td>Rp. {{ number_format($total_perusahaan) }}</td>
      <td>Rp. @if($total_pegawai < 0)0 @else{{ number_format($total_pegawai) }}@endif</td>
      <td>Rp. {{ number_format($total_semua) }}</td>  
      </tr>
    </table>
    <h3 style="text-align:center;"><b>Laporan Pengeluaran Pegawai</b></h3>
    <table class="table table-bordered table-hover">
      <thead>
      <tr>
        <th>No </th>
        <th>Nama Pegawai</th>
        <th>Tanggal</th>
        <th>Total</th>    
      </tr>
      </thead>
    <tbody id="myTable">
      @if($isMoreThanBudget == true)
      <?php $no = 0; ?>
      @foreach($result as $dt)
      @if($dt['total'] > 0)
        <?php $no++; ?>
        <tr>
        <td>{{ $no }}</td>  
        <td>{{ $dt['nama_pegawai'] }}</td> 
        <td>{{ $dt['tanggal'] }}</td>
        <td>Rp. {{ number_format($dt['total']) }}</td>
        </tr>
      @endif
    @endforeach
    @endif
    </table>
</div>

@stop

@section('css')
<style>
@page { size: auto;  margin: 0mm; }
hr {
    display: block;
    height: 1px;
    background: transparent;
    width: 100%;
    border: none;
    border-top: solid 1px #aaa;
}
</style>
  
@stop

@section('js')
<script>
  window.addEventListener("afterprint", function(){
    window.location = '/laporan';
  });
  $("#body_print").ready(function(){
    window.print();
  });
</script>
@stop
