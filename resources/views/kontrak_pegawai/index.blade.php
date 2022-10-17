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
@section('title', 'Kontrak Pegawai')

@section('content_header')

<h4>Kontrak Pegawai</h4>
<p>Menampilkan Data Kontrak Pegawai</p>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
    <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a>
    <li><i class="fa fa-dashboard active"></i> Kontrak Pegawai
</ol>

@stop

@section('content')
<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title"> Daftar Menu Kontrak Pegawai </h1>
        <button class="btn btn-danger pull-right" id="btn-tambah">Tambah</button>
    </div>
    <table class="no-margin table table-bordered table-hover" id="#">
        <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Email</th> 
          <th>Nomor Hp</th>
          <th>Status</th>
          <th style="text-align:center">Aksi</th>
        </tr>
        </thead>
      <tbody id="myTable">
        @foreach($results as $dt)
          <tr id="dt{{ $dt['id'] }}">
            <td width="2%" class="text-center">{{ (($results->currentPage() - 1 ) * $results->perPage() ) + $loop->iteration }}</td>
            <td id="nd{{ $dt['id'] }}">{{ $dt['nama'] }}</td>
            <td>{{ $dt['email'] }}</td>
            <td>{{ $dt['nomor_hp'] }}</td>
            @if( $dt['isActive'] == true )
            <td id="status{{$dt['id']}}" style="text-align:center; vertical-align:middle;"><span id="isActive{{$dt['id']}}" class="label label-success">Aktif</span></td>
            @else
            <td id="status{{$dt['id']}}" style="text-align:center; vertical-align:middle;"><span id="isActive{{$dt['id']}}" class="label label-Danger">Tidak Aktif</span></td>
            @endif
            <td style="text-align:center">        
              <button id="#" class="btn btn-warning btn-sm" onclick="change('{{$dt['id']}}','{{$dt['nama']}}')"><i class="glyphicon glyphicon-pencil"></i></button>    
              <button id="#" class="btn btn-danger btn-sm" onclick="hapus({{ $dt['id'] }})"><i class="glyphicon glyphicon-trash"></i></button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="box-footer">
        {{ $results->appends(Request::all())->links() }}
    </div>
</div>
{{-- Modal Tambah --}}
<div class="modal fade" id="modal-tambah">
  <div class="modal-dialog"> 
    <div class="modal-content"> 
      <div class="modal-header">
          <h4 class="modal-title">Tambah Kontrak Pegawai<small> Menambahkan Data Kontrak Pegawai</small></h4>
      </div>
      <div class="modal-body">
        <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
        <input type="text" id="id" value="{{ $id }}" hidden>
        <input type="text" id="id_dk" value="{{ $id_dk }}" hidden>
        <div class="form-group">
        <label>Pilih Pegawai yang tersedia</label>
        <select class="form-control perusahaan" id="pegawai" data-placeholder="Pilih Pegawai" style="width: 100%">
          @foreach($pegawai_tersedia as $dt)
          <option value="{{ $dt['id'] }}">{{ $dt['name'] }}</option>                       
          @endforeach
        </select> 
      </div>
      <div class="form-group">
        <label>Status</label>
        <select class="form-control perusahaan" id="isActive" style="width: 100%">
          <option value="1">Aktif</option>
          <option value="0">Tidak Aktif</option>                       
        </select> 
      </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
        <button class="btn btn-danger pull-right" id="btn-tambah-action">Tambah</button>
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
<script src="{{ url('js/kontrak_pegawai/index.js') }}"></script>
@stop