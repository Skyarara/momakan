@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Tambah Detail Kontrak</h1>
    <p>Menambahkan Data Detail Kontrak</p>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a></li>
        <li><a href="/kontrak"><i class="fa fa-dashboard active"></i> Kontrak </a></li>
        <li><i class="fa fa-dashboard active"></i> Tambah Kontrak Detail</li>
    </ol>
@stop

@section('content')
<form method="POST">
  <div class="box box-danger">
    <div class="box-header with-border">
      <span class="box-title">Menambahkan Data Detail Kontrak</span>
      <p class="text-muted">Semua atribut harap diisi!</p>   
    </div>
    <div class="box-body">    
      {{ csrf_field() }}  
      <input type="text" name="id" value="{{ $id }}" hidden>
      <div class="row">
        <div class="col-sm-4">
          <label>Budget :</label>
          <div class="input-group">
            <div class="input-group-addon"><b>RP</b></div>
              <input type="text" min="0" oninput="validity.valid||(value='');" class="form-control divide" id="budget" name="budget" placeholder="Masukan Budget Anda Disini" required>
            <div class="input-group-addon">.00</div>
          </div>
        </div>
        <div class="col-sm-8">
          <label>Nama Detail Kontrak :</label>
          <input type="text" id="name" name="name" class="form-control" required placeholder="Masukan Nama Detail Kontrak Anda Disini">
      </div>
    </div>
    <!-- <div class="row" style="margin-top: 5px;">
      <div class="col-sm-12">
        <label>Pilih Beberapa Makanan :</label>
        <input type="text" id="makanan" name="makanan" hidden>        
        <input type="text" id="total_harga_tf" name="total_harga_tf" hidden>        
        <div class="row">
        <?php $no = 0; ?>
        @foreach($result as $dt)
          <?php $no++; ?>
          @if($no == 5)
            </div>
            <div class="row">
          @endif
          <div class="col-sm-3">
            <b><h4>{{ $dt['nama_kategori'] }}</h4></b>
            @foreach($dt['makanan'] as $dts)
            <div class="checkbox">
              <label>
              <input type="checkbox" id="mk{{ $dts['id'] }}" value="{{ $dts['id'] }}|{{ $dts['harga_asli'] }}">{{ $dts['nama_makanan'] }} (Rp. <b>{{ $dts['harga'] }}</b>)
            </label></div>
            @endforeach
          </div>
        @endforeach
        </div>           
      </div>
    </div>  
  </div> -->
  <div class="box-footer">
    <!-- <div class="pull-left">Total Harga : <span id="total_harga"><b>Rp. 0</b></span></div> -->
    <input type="submit" class="btn btn-danger btn-sm pull-right" value="Tambah" id="btn-tambah">
  </div> 
</form>
<div class="modal-ajax" id="modal-action"></div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ url('js/number-divider.min.js') }}"></script>
<script src="{{ url('js/kontrak_detail/tambah.js') }}"></script>
@stop