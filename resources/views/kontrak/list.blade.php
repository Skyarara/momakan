@extends('adminlte::page')
<meta Http-Equiv="Cache-Control" Content="no-cache">
<meta Http-Equiv="Pragma" Content="no-cache">
<meta Http-Equiv="Expires" Content="0"> 
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
@section('title', 'Dashboard')

@section('content_header')


<h4>List Detail Kontrak</h4>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
    <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a>
    <li><i class="fa fa-dashboard active"></i> List Kontrak
</ol>

@stop

@section('content')
@if(Session::has('errors'))
<div class="alert alert-danger alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-check"></i> Peringatan!</h4>
  {{ Session::get('errors') }}
</div>
@elseif(Session::has('success'))
<div class="alert alert-success alert-dismissible">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <h4><i class="icon fa fa-check"></i> Sukses!</h4>
  {{ Session::get('success') }}
</div>
@endif
<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title"> Daftar Menu Detail Kontrak </h1>  
        <button class="btn btn-danger pull-right" id="btn-tambah">Tambah</button>      
    </div>
    <table class="no-margin table table-hover" id="#">
        <thead>
        <tr>
          <th>Nama Detail</th>
          <th>Budget</th> 
          <th style="text-align:center">Aksi</th>
        </tr>
        </thead>
      <tbody id="myTable">
        @foreach($data as $dt)
        <tr id="dt{{ $dt->id }}">
          <td id="nd{{ $dt->id }}">{{ $dt->contract_detail_name }}</td>
          <td id="bd{{ $dt->id }}">Rp. {{ number_format($dt->budget) }}</td>
          <td style="text-align:center"> 
            <button id="#" class="btn btn-primary btn-sm" onclick="pegawai({{ $dt->id }})"><i class="fa fa-male"></i></button>
            <button id="#" class="btn btn-success btn-sm" onclick="detail({{ $dt->id }})"><i class="fa fa-eye"></i></button>
            <button id="#" class="btn btn-warning btn-sm" onclick="ubah({{ $dt->id }})"><i class="glyphicon glyphicon-pencil"></i></button>
            <button id="#" class="btn btn-danger btn-sm" onclick="hapus({{ $dt->id }})"><i class="glyphicon glyphicon-trash"></i></button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="box-footer">
    </div>
</div>
{{-- Modal Tambah --}}
<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog"> 
    <div class="modal-content"> 
      <div class="modal-header">
          <h4 class="modal-title">Tambah Detail Kontrak<small> Menambahkan Data Detail Kontrak</small></h4>
      </div>
      <div class="modal-body">
        <input type="text" id="_token" value="{{ csrf_token() }}" hidden>        
        <label>Nama Detail Kontrak</label>
        <input type="name" class="form-control" name="detail_kontrak" id="detail_kontrak" required placeholder="Nama Detail Kontrak">
        <label>Budget</label>
        <div class="input-group">
          <div class="input-group-addon"><b>RP</b></div>
            <input type="text" min="0" oninput="validity.valid||(value='');" class="form-control divide" id="budget" name="budget" placeholder="Masukan Budget Anda Disini">
          <div class="input-group-addon">.00</div>
        </div>        
      </div>
      <div class="modal-footer">
        <button class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
        <button class="btn btn-danger pull-right" id="btn-tambah-action">Tambah</button>
      </div>
    </div>
  </div>
</div>
{{-- Modal Detail --}}
<div class="modal fade" id="modal-detail">
  <div class="modal-dialog"> 
    <div class="modal-content"> 
      <div class="modal-header">
          <h4 class="modal-title">Detail Kontrak<small> Menampilkan Data Detail Kontrak</small></h4>
      </div>
      <div class="modal-body">        
        <label>Nama Detail Kontrak</label>
        <input type="name" class="form-control" id="detail_kontrak_detail" readonly>
        <label>Budget</label>
        <div class="input-group">
          <div class="input-group-addon"><b>RP</b></div>
            <input type="text" min="0" class="form-control" id="budget_detail" readonly>
          <div class="input-group-addon">.00</div>
        </div>
        <div style="margin-top: 5px;">
          <!-- <label>Daftar Menu :</label>
          <div id="menu_makanan"> -->
            
          </div>
          <label>Daftar Pegawai :</label>
          <div id="daftar_pegawai">
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default pull-right" data-dismiss="modal">Tutup</button>        
      </div>
    </div>
  </div>
</div>
{{-- Modal Ubah --}}
<div class="modal fade" id="modal-ubah">
  <div class="modal-dialog"> 
    <div class="modal-content"> 
      <div class="modal-header">
          <h4 class="modal-title">Ubah Detail Kontrak<small> Mengubah Data Detail Kontrak</small></h4>
      </div>
      <div class="modal-body">        
        <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
        <input type="text" id="id" hidden>
        <label>Nama Detail Kontrak</label>
        <input type="name" class="form-control" name="detail_kontrak_ubah" id="detail_kontrak_ubah" required placeholder="Nama Detail Kontrak">
        <label>Budget</label>
        <div class="input-group">
          <div class="input-group-addon"><b>RP</b></div>
          <input type="text" min="0" oninput="validity.valid||(value='');" class="form-control divide" id="budget_ubah" name="budget_ubah" placeholder="Masukan Budget Anda Disini">
          <div class="input-group-addon">.00</div>
        </div>        
      </div>
      <div class="modal-footer">
        <button class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
        <button class="btn btn-danger pull-right" id="btn-ubah-action">Ubah</button>    
      </div>
    </div>
  </div>
</div>
{{-- Loading --}}
<div class="modal-ajax" id="modal-action"></div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ url('js/number-divider.min.js') }}"></script>
<script src="{{ url('js/list_detail_kontrak.js') }}"></script>
@stop