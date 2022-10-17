@extends('adminlte::page')
@section('title', 'Kontrak Pesanan')

@section('content_header')

<h4>Tambah Pesanan</h4>
<p>Menambahkan Data Kontrak Pesanan</p>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
    <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a>
    <li><i class="fa fa-dashboard active"></i> Tambah Pesanan
</ol>

@stop

@section('css')
    <link href="{{ asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title"> Daftar Makanan </h1>        
    </div>
    <div class="box-body">
        <div class="row">
            @foreach($data_makanan as $dt)
            <div class="col-md-3">
                <div class="info-box">
                    @if($dt['photo'] != "")
                        @if(file_exists(public_path('storage/images/' . $dt['photo'])))                
                        <img src="{{ // $dt['photo'] 
                        url('storage/images/' . $dt['photo']) }}" class="info-box-icon bg-red img-thumbnail">
                        @else
                        <span class="info-box-icon bg-red"><i class="fa fa-image" style="margin-top: 23px"></i></span>
                        @endif
                    @else
                        <span class="info-box-icon bg-red"><i class="fa fa-image" style="margin-top: 23px"></i></span>
                    @endif
                    <div class="info-box-content">                        
                        <span class="info-box-text">{{ $dt['nama_makanan'] }}</span> 
                        <span class="info-box-number">{{ number_format($dt['porsi']) }} Porsi</span>                       
                    </div>                    
                </div>
            </div> 
            @endforeach       
        </div>    
    </div>
</div>
<div class="row">
    @foreach($data_pegawai as $dt)
    <div class="col-md-3">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">{{ $dt['nama_pegawai'] }}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse" data-target="#data1"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                @if(count($dt['makanan']) == 0)
                    <b><i>Data ini kosong</b></i>
                @else
                    @foreach($dt['makanan'] as $dts)
                    <li>{{ $dts['nama_makanan'] }} (<b>x{{ $dts['jumlah'] }}</b>)</li>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="box box-danger">
    <div class="box-footer">
      <form method="POST" id="form-order">
        {{ csrf_field() }}
        <input type="text" name="id" value="{{ $id }}" hidden>
        <div class="form-group">
            <label>Tanggal Order</label>
            <input type="text" name="tanggal" class="form-control" id="tanggal" placeholder="Tanggal order">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-md btn-danger btn-flat" id="btn-tambah-action" value="Tambah">
        </div>
      </form>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ url('js/number-divider.min.js') }}"></script>
<script src="{{ url('js/kontrak_order/index.js') }}"></script>
@stop