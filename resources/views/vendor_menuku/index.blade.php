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
@section('title', 'Kontrak Pesanan')

@section('content_header')

<h4>Daftar Menuku</h4>
<p>Menampilkan Daftar Menuku</p>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
    <li><a href="/menuku"><i class="fa fa-dashboard"></i> Menuku </a>    
</ol>

@stop

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title"> Daftar Menuku</h1><br>
        <small>Menampilkan Semua Daftar Menu makanan Sesuai dengan akun yang anda login ini</small>
            
    </div>
    <table class="no-margin table table-bordered table-hover" id="#">
        <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama Makanan</th> 
          <th>Deskripsi</th>
          <th>Harga</th>          
        </tr>
        </thead>
      <tbody id="myTable">
        @foreach($menu as $dt)
        {{-- {{dd($dt['id'])}}; --}}
        <tr id="dt{{ $dt->id }}">
          <td>
              <img src="{{ asset('storage/images/' . $dt->image) }}" class="img-thumbnail" width="65" style="height: 55px"></img>
          </td>
          <td>{{ $dt->name }}</td>
          <td>{{ $dt->description }}</td>
          <td>Rp. {{ number_format($dt->price) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>    
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
swal('Berhasil', '{{ Session::get("result") }}', 'success');
@endif
</script>
@stop