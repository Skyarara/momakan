@extends('adminlte::page')

@section('title', 'Dapur Bunda - Makanan')

@section('content_header')
     <h1>
        Makanan
        <small>Dapur Bunda</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i><b>Home</b></a> -> <b>Menu</b> -> <a href="/makanan/paket"><b>Makanan Paket</b>          
        </a></li>
      </ol>
      <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
@stop

@section('content')
<div class="callout callout-info">
    <h4>
      <i class="fa fa-info"></i>
          Note :
    </h4>
        Status Aktif menandakan bahwa makanan tersebut akan menjadi menu default pada keesokan harinya.
  </div>
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Daftar Makanan Paket</h3>    
                    <a class="pull-right margin-right:24px;" href="{{ route('tambah.paket') }}"><button type="button" class="btn btn-danger"><i class="fa fa-plus"></i>Tambah</button></a>                             
            </div>          
            <meta name="csrf-token" content="{{ csrf_token() }}">  
            <div class="box-body">                
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="table-data">
                    <tbody id="body-t">
                    <tr>
                      <th>No</th>
                      <th>Gambar</th>
                      <th>Nama Makanan</th>
                      <th style="width: 30%">Deskripsi</th>
                      <th>Harga</th>
                      <th>Status</th>
                      <th>Aksi</th>
                    </tr>  
                    @foreach($menu as $dt)                   
                    <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><img src="{{ asset('storage/images/' . $dt->menu->image) }}" class="img-thumbnail" style="width:100px;height:100px"></td>
                    <td>{{ $dt->menu->name }}</td>
                    <td>{{ $dt->menu->description }}</td>
                    <td>Rp. {{ number_format($dt->menu->price) }}</td>
                    <td>
                        <form id="myForm" name="myForm" action="/makanan_paket/change" method="post"> 
                    <label class="switch">
                        <input type="checkbox" id="check" @if($dt->menu->isActive == 1) onclick="isActive({{ $dt->parent_id }})" checked @else onclick="isActive({{ $dt->parent_id }})"@endif>
                      <span class="slider round"></span>
                          {{-- <small class="txt" style="positi"></small> --}}
                    </label>
                        </form>
                    </td>
                      <td>
                        <div class="btn-group">
                          <a href="{{ "/makanan_paket/read/$dt->parent_id" }}"><button type="button" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                          <a href="{{ "/makanan_paket/edit/$dt->parent_id" }}"><button id="edit{{ $dt->parent_id }}"type="button" class="btn btn-warning"><i class="fa fa-edit"></i></button>
                          <a><button type="button" class="btn btn-danger" id="delete{{$dt->parent_id}}" onclick="delete_data({{ $dt->parent_id }});"><i class="fa fa-remove"></i></button>
                        </div>
                      </td>
                  </tr>
                    @endforeach
                    </tbody>                  
                  </table>
                  <div class="box-footer">
                    </div>
                </div>                                  
            </div>
        </div>
    </div>
  </div>

@stop

@section('css')
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
  .switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 3px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}  m                                                                                                                                                                                                                                                                                                                                                                                                                                                       

.txt:before {
  content: "Inactive";
}

input:checked + .txt:after {
  content: "sljfdal";
}

input:checked + .slider {
  background-color:  #009933;
  content: "sljfdal";
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(13px);
  -ms-transform: translateX(13px);
  transform: translateX(13px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  position: absolute;
  top: 2px;
  border-radius: 50%;
  width: 20px;
  height: 20px;
}
  </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('js/menu_paket/menu_paket.js') }}"></script>
<script>
  $(document).ready(function(){
    @if(Session::has('errors'))
      swal('Gagal', "{{ Session::get('errors') }}", 'error');
    @elseif(Session::has('success'))
    swal("Sukses", "{{ Session::get('success') }}", "success").then(function(){
    location.reload();
});
    @endif
  });
  </script>
@stop
  