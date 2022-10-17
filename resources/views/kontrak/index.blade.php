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
@section('title', 'Momakan - Kontrak')

@section('content_header')
<h1>Halaman Kontrak</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
        <li><a href="/kontrak"><i class="fa fa-fax"></i> Kontrak </a>
</ol>
@stop
@section('content')
<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title">Daftar Kontrak </h1>
        <button class="btn btn-danger pull-right" id="btn-tambah">
          <span class="glyphicon glyphicon-plus"></span> Tambah
        </button>
    </div>
    <table class="no-margin table table-hover" id="#">
        <thead>
        <tr>
          <th>Kode Kontrak</th>
          <th>Nama Perusahaan</th>
          <th style="text-align:center">Status</th> 
          <th style="text-align:center">Aksi</th>
        </tr>
        </thead>
      <tbody id="myTable" >
        @if(auth()->user()->role_id == 3) 
        @foreach($corporate_contract as $dt)
        <tr id="dt{{ $dt->id }}">
            <td id="cc{{ $dt->id }}">{{ $dt->contract_code }}</td>
            <td id="cp{{ $dt->id }}">{{ $dt->corporate->name }}</td>
            <td style="padding-top:1.25%; text-align:center" id="st{{ $dt->id }}">
              @if( $dt->status == true )<span class="label label-success">Aktif</span>
              @else <span class="label label-danger">Nonaktif</span>
              @endif
            </td>
            <td style="text-align:center">
              <button id="#" class="btn btn-primary btn-sm" onclick="order({{ $dt->id }})"><i class="fa fa-cart-plus"></i></button>
              <button id="#" class="btn btn-success btn-sm" onclick="detail({{ $dt->id }})"><i class="fa fa-eye"></i></button>              
            </td>
          </tr>
          @endforeach
          @else 
        @foreach($data as $dt)
        <tr id="dt{{ $dt->id }}">
          <td id="cc{{ $dt->id }}"><b>{{ $dt->contract_code }}</b></td>
          <td id="cp{{ $dt->id }}">{{ $dt->corporate->name }}</td>
          <td style="padding-top:1.25%; text-align:center" id="st{{ $dt->id }}">@if( $dt->status == true )<span class="label label-success">Aktif</span> @else <span class="label label-danger">Nonaktif</span> @endif</td>
          <td style="text-align:center">
            <button id="#" class="btn btn-info btn-sm" onclick="invoice({{ $dt->id }})"><i class="fa fa-credit-card"></i></button>
            <button id="#" class="btn btn-primary btn-sm" onclick="order({{ $dt->id }})"><i class="fa fa-cart-plus"></i></button>
            <button id="#" class="btn btn-success btn-sm" onclick="detail({{ $dt->id }})"><i class="fa fa-eye"></i></button>
            <button id="btn_status{{ $dt->id }}" class="btn btn-warning btn-sm" onclick="{{ $dt->status? 'batalkan('.$dt->id.',"'.$dt->corporate->name.'")': 'aktifkan('.$dt->id.')' }}"><i class="fa {{ $dt->status? 'fa-hand-stop-o': 'fa-sign-in' }}"></i></button>            
            <button id="btn_hapus{{ $dt->id }}" class="btn btn-danger btn-sm" onclick="hapus({{ $dt->id }})" {{ $dt->status?'disabled':'' }}><i class="glyphicon glyphicon-trash"></i></button>
          </td>
        </tr>
        @endforeach
        @endif
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
          <h4 class="modal-title">Tambah Kontrak<small> Menambahkan Data Kontrak</small></h4>
      </div>
      <div class="modal-body">
        <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
        <label>Perusahaan</label>
        <select class="form-control perusahaan" name="perusahaan" id="perusahaan" data-placeholder="Pilih Perusahaan" style="width: 100%">
          @foreach($corporate as $dt)
          <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
          @endforeach
        </select>
        {{-- <label>Tanggal Mulai</label>
        <input type="name" class="form-control" name="tanggal_mulai" id="tanggal_mulai" required placeholder="Tanggal Mulai">
        <label>Tanggal Berakhir</label>
        <input type="name" class="form-control" name="tanggal_berakhir" id="tanggal_berakhir" required placeholder="Tanggal Berakhir"> --}}
      </div>
      <div class="modal-footer">
        <button class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
        <button class="btn btn-danger pull-right" id="btn-tambah-action">Tambah</button>
      </div>
    </div>
  </div>
</div>
{{-- Modal Ubah --}}
<div class="modal fade" id="modal-ubah">
  <div class="modal-dialog"> 
    <div class="modal-content"> 
      <div class="modal-header">
          <h4 class="modal-title">Ubah Kontrak<small> Mengubah Data Kontrak</small></h4>
      </div>
      <div class="modal-body" id="body-ubah">  
        <input type="text" id="id" hidden>
        <label>Perusahaan</label>
        <select class="form-control perusahaan" name="perusahaan_ubah" id="perusahaan_ubah" data-placeholder="Pilih Perusahaan" style="width: 100%">
          @foreach($corporate as $dt)
          <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
          @endforeach
        </select>
        {{-- <label>Tanggal Mulai</label>
        <input type="name" class="form-control" name="tanggal_mulai_ubah" id="tanggal_mulai_ubah" disabled>
        <label>Tanggal Berakhir</label>
        <input type="name" class="form-control" name="tanggal_berakhir_ubah" id="tanggal_berakhir_ubah" disabled> --}}
      </div>
      <div class="modal-footer">
        <button class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
        <button class="btn btn-danger pull-right" id="btn-ubah-action" disabled>Ubah</button>
      </div>
    </div>
  </div>
</div>
{{-- Laravel Dusk --}}
<input type="text" value="{{ $id_terbaru }}" id="id_terbaru" hidden>
{{-- Loading --}}
<div class="modal-ajax" id="modal-action"></div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ url('js/kontrak.js') }}"></script>
@stop