@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Tambah Rencana Menu</h1>
@stop

@section('content')
    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title">Tambah Rencana Menu</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form method="post" action="{{ route('tambah.menu_plan')}}" role="form" enctype="multipart/form-data" id="form-submit">
        
        {{ csrf_field() }}
        <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
        <input type="text" id="list_kategori" value="{{ $list_kategori }}" hidden>


        <div class="box-body">
          <div class="form-group">
            <label for="makanan">Tanggal</label>
            <input type="text" name="date" class="form-control" id="date" placeholder="Tanggal" required>
          </div>
          <div class="form-group">
            <label for="Kategori">Nama Rencana Menu</label>
          <input type="text" name="name" class="form-control" placeholder="Nama" id="nama" required>
          </div>
          <label>Pilih Makanan :</label><br>

          {{-- Old Version Please Let it --}}
          {{-- <input type="button" class="btn btn-danger btn-sm" id="tambah_menu_makanan" value="Tambah Menu">
          <input type="button" class="btn btn-danger btn-sm" id="hapus_semua_menu" value="Hapus Semua">
          
          <div class="row" id="menu_makanan">

          </div> --}}
          <input type="text" name="list_menu" id="list_menu" hidden>
          @foreach ($new_result as $chunk)
            <div class="row">
              @foreach($chunk as $dt)
                @if($dt['makanan'] != null)
                <input type="text" id="ls_c{{ $dt['id_kategori'] }}" value="@foreach($dt['makanan'] as $dts){{ $dts['id'] }}|{{ $dts['nama_makanan'] }}|{{ $dts['harga'] }}|{{ $dts['harga_asli'] }};@endforeach" hidden>
                <div class="col-md-6">
                  <div class="input-group">
                    <b>
                    <h4>{{ $dt['nama_kategori'] }} <span type="button" class="btn btn-danger btn-xs fa fa-plus" style="margin-left: 15px" onclick="add({{ $dt['id_kategori'] }})"></span></h4>
                    </b>
                  </div>
                  <div id="ct{{ $dt['id_kategori'] }}">
                  
                  </div>
                </div>
                @endif
              @endforeach
            </div>
          @endforeach


          
        </div>
        <div class="box-footer">
            <input type="button" class="btn btn-danger btn-md pull-right" id="btn-tambah" value="Tambah" >
            <span class="pull-right info-total-harga">Rp. <span id="total-harga">0</span></span>
        </div>
          </form>
      </form>
@stop

@section('css')
<link rel="stylesheet" href="{{ url('css/tambah-menu.css') }}">
@stop

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ url('js/menu_plan/tambah.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/number-divider.min.js') }}"></script>
{{-- Menu Plan Java Script --}}
@stop