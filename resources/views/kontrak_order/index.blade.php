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
@section('title', 'Momakan - Kontrak Pesanan')

@section('content_header')

<h1>Kontrak Pesanan</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
    <li><a href="/kontrak"><i class="fa fa-fax"></i> Kontrak </a>
    <li><i class="fa fa-cart-plus"></i> Kontrak Pesanan</li>
</ol>

@stop

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title"> Daftar Kontrak Pesanan </h1>
        <button class="btn btn-danger pull-right" id="btn-tambah"><i class="fa fa-plus"></i> Tambah</button>
    </div>
    <div class="box-body">
      <table class="no-margin table table-hover">
          <thead>
          <tr>
            <th>No.</th>
            <th>Tanggal</th> 
            <th>Total Pembayaran</th>
            <th style="text-align:center">Aksi</th>
          </tr>
          </thead>
        <tbody id="myTable">
          @foreach($result as $dt)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date_format(date_create($dt['tanggal']), 'd F Y') }}</td>
            <td>Rp. {{ number_format($dt['total_pembayaran'] ) }}</td>
            <td style="text-align:center">            
              <a href="{{url("/kontrak/order/$id/detail",$dt['tanggal'])}}">
                <button id="#" class="btn btn-info btn-sm"><i class="fa fa-eye"></i>
                </button>
              </a>
              <button id="#" class="btn btn-danger btn-sm" onclick="hapus({{ $dt['tanggal']->format('Y') }}, {{ $dt['tanggal']->format('m') }},{{ $dt['tanggal']->format('d') }})"><i class="glyphicon glyphicon-trash"></i></button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>    
    </div>
</div>
{{-- Loading --}}
<div class="modal-ajax" id="modal-action"></div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ url('js/number-divider.min.js') }}"></script>
<script src="{{ url('js/kontrak_order/index.js') }}"></script>
<script>
@if(Session::has('result'))
  @if(Session::has('danger'))
    swal('Peringatan!', '{{ Session::get("result") }}', 'error');
  @else
    swal('Berhasil', '{{ Session::get("result") }}', 'success');
  @endif
@endif
</script>
@stop