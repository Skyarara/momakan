@extends('adminlte::page')

@section('title', 'Momakan - Rencana Menu')

@section('content_header')

<h1>
    Halaman Rencana Menu Makanan
    <small>Menampilkan Rencana Menu Makanan</small>
</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
    <li><i class="fa fa-cutlery"></i> Menu </a>
    <li><a href="/menu_plan"><i class="fa fa-circle-o"></i> Menu Plan </a>
</ol>
@stop

@section('content')

<div class="form-group clearfix">
    <a href="/menu_plan/tambah">
        <button class="btn btn-danger pull-right" style="margin-right:10px;">
            <i class="glyphicon glyphicon-plus"></i> Tambah
        </button>
    </a>
    
    <form id="search" method="get" action="{{url()->current()}}">
        <select class="form-control pull-left" name="search" style="width:15.75%; margin-left:15px;">
                <option value="00">Pilih Sesuai Bulan</option>
                <option value="01">Januari</option>
                <option value="02">Februari</option>
                <option value="03">Maret</option>
                <option value="04">April</option>
                <option value="05">Mei</option>
                <option value="06">Juni</option>
                <option value="07">July</option>
                <option value="08">Agustus</option>
                <option value="09">September</option>
                <option value="10">Oktober</option>
                <option value="11">November</option>
                <option value="12">Desember</option>
        </select>
        <button class="btn btn-flat pull-left" id="filter" style="height:34px;"><i class="fa fa-search"></i>
        </button>
    </form>
</div>

@foreach($result->chunk(3) as $chunk)
    <div class="row">
        @foreach ($chunk as $dt)
            <div class="col-md-4">
                <div class="box box-danger">
                    <div class="box-header with-border">
                    <h3 class="box-title">{{ Carbon\Carbon::parse($dt['date'])->format('l, d F') }}</h3>
                        <div class="box-tools pull-right">
                        <a href="/menu_plan/ubah/{{ $dt['id'] }}"><button type="button" class="btn btn-box-tool"><i class="glyphicon glyphicon-pencil"></i></button></a>
                        <a href="#"><button type="button" class="btn btn-box-tool" onclick="delete_data({{ $dt['id'] }});"><i class="glyphicon glyphicon-trash"></i></button></a>
                        </div>
                    </div>
                    <div class="box-body">
                        @foreach ($dt['detail'] as $dts)
                        <li>
                        {{ DB::table('menu')->where('id', $dts['menu_id'])->first()->name}} (<b> @if( $dts['isDefault'] == 1) Default @elseif ($dts['isDefault'] == 2)Extra @else Alternatif @endif</b>)
                        </li>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach

<div>
    {{ $result->appends(Request::all())->links() }}
</div>

@stop

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('js/menu_plan/index.js') }}"></script>

{{-- Hapus aja jika mengganggu ~ RasyidMF --}}
<script>
@if(Session::has('error'))
    swal('Gagal!', '{{ Session::get("error") }}', 'error');
@endif
</script>
@stop