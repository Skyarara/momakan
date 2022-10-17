@extends('adminlte::page')

@section('title', 'Momakan - Makanan Pegawai')

@section('content_header')
<h1>Halaman Makanan Pegawai</h1>
    <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
        <li><a href="/corporate"><i class="fa fa-industry"></i> Perusahaan </a>
          <li><a href="#"><i class="fa fa-users"></i> Pegawai </a>
            <li><a href="#"><i class="fa fa-cutlery"></i> Makanan </a>
  </ol>
@stop

@section('content')

<div class="row">
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header">
          <h2 class="box-title">Makanan Pegawai</h2>
          <button type="button" id="tbh" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i> Tambah</button>
          <br><br>
    <table class="table table-bordered table-hover table-striped" id="table-data">
      <tbody id="body-t" >
        <tr>
          <th>No</th>
          <th>Nama Makanan</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Status Ekstra</th>
          <th>Catatan</th>
          <th style="text-align:center">Aksi</th>
        </tr>
        @foreach($data as $dt)
          <tr class="data{{$dt->id}}">
          <td>{{$loop->iteration}}</td>  
          <td>{{ $dt->menu->name }}</td>
          <td>{{ $dt->menu->price }}</td>
          <td>{{ $dt->quantity }}</td>
          <td>@if( $dt->isExtra == 1 )Ya @else Tidak @endif</td>
          <td>{{ $dt->notes }}</td>
          <td style="text-align:center">
            <div class="btn-group">
              <button type="button" class="btn btn-primary" onclick="view_data({{ $dt->id }});"><i class="fa fa-eye"></i></button>
              <button type="button" id="edit{{$dt->id}}" class="btn btn-warning edit-modal" data-toggle="modal" data-target="#modal-edit-{{ $dt->id }}"><i class="fa fa-edit"></i></button>
              <div class="modal fade" id="modal-edit-{{ $dt->id }}" role="dialog" style="text-align:left;">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">      
                        <h4 class="modal-title">Dapur Bunda <small>Ubah Data Makanan Pegawai</small></h4>
                      </div>
                      <div class="modal-body">
                        <form enctype="multipart/form-data" id="edit_form">
                        @csrf
                        <input type="text" name="id" id="id" value="{{ $dt->id }}" hidden>
                        <div class="alert alert-danger alert-dismissible" id="alert_edit" hidden>
                          <button type="button" class="close" onclick="document.getElementById('alert_edit').hidden=true;">×</button>
                          <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                          <span id="error_text_edit"></span>
                        </div>
                        <label>Makanan :</label>
                        <select class="form-control menu_select2" name="menu_edit" id="menu_edit" data-placeholder="Pilih Makanan" style="width: 100%">
                          <option value="{{ $dt->menu_id }}" selected>{{ $dt->menu->name .' | '.  $dt->menu->price }}</option>                       
                          @foreach(collect($makanan)->where('categories_id', $dt->menu->menu_category_id)->where('price', $dt->menu->price) as $item)
                            <option value="{{ $item['menu_id'] }}">{{ $item['nama_makanan'] .' | '.  $item['price'] }}</option>                       
                          @endforeach
                        </select>
                        <label>Status Extra :</label>
                        <select class="form-control menu_select2 extra_select_edit" id="extra_edit" name="extra_edit" data-placeholder="Status Extra" style="width: 100%">
                            <option value=0 {{ $dt->isExtra == 0 ? 'selected' : '' }}>Tidak</option>
                            <option value=1 {{ $dt->isExtra == 1 ? 'selected' : '' }}>Iya</option>
                        </select>
                        <label>Jumlah :</label>
                        <input type="number" name="qty_edit" id="qty_edit" value="{{ $dt->quantity }}" class="form-control" placeholder="Jumlah Makanan" {{ $dt->isExtra == 0 ? 'readonly' : '' }}>
                        <label>Catatan :</label>
                        <textarea class="form-control" name="notes_edit" id="notes_edit" placeholder="Catatan" style="resize: vertical;">{{ $dt->notes }}</textarea>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-warning ubah-btn" id="ubah-btn">Ubah</button>
                      </form>
                      </div>
                    </div>      
                  </div>
                </div>
                <button type="button" id="delete{{$dt->id}}" class="btn btn-danger" onclick="delete_data({{ $dt->id }});"><i class="fa fa-remove"></i></button>
            </div>
          </td>
          </tr>
        @endforeach
      </tbody>
    </table>

{{-- Add Form --}}

<form enctype="multipart/form-data" id="add_form">
  {{ csrf_field() }}
    <div class="modal fade" id="modal-default">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">      
            <h4 class="modal-title">Dapur Bunda <small>Tambah Data Makanan Pegawai</small></h4>
          </div>
          <div class="modal-body">
            <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
            <input type="text" name="id" value="{{ $id }}" hidden>
            <div class="alert alert-danger alert-dismissible" id="alert" hidden>
              <button type="button" class="close" onclick="document.getElementById('alert').hidden = true;">×</button>
              <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
              <span id="error_text"></span>
            </div>
            <label>Makanan :</label>
            <select class="form-control menu_select2" name="menu" id="menu" data-placeholder="Pilih Makanan" style="width: 100%">
                @foreach($makanan as $item)
                  <option value="{{ $item['menu_id'] }}">{{ $item['nama_makanan'] .' | '.  $item['price']}}</option>                       
                @endforeach
            </select>
            <label>Status Extra :</label>
            <select class="form-control menu_select2 extra_select" name="extra" data-placeholder="Status Extra" style="width: 100%">
                <option value=0>Tidak</option>
                <option value=1>Iya</option>
            </select>
            <label>Jumlah :</label>
              <input type="number" name="qty" id="qty" value="1" class="form-control" readonly>
              <div id="er1" style="display:none;"><p style="color:red;  "><b>Jumlah makanan harus disertakan!</b></p></div>
            <label>Catatan :</label>
            <textarea class="form-control" name="notes" id="notes" placeholder="Catatan"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="tambah-btn">Tambah</button>
          </div>
        </div>      
      </div>    
    </div>
</form>

 {{-- Detail Modal --}}

 <div class="modal fade" id="modal-default-detail">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">      
        <h4 class="modal-title">Dapur Bunda <small>Detail Data Makanan</small></h4>
      </div>
      <div class="modal-body" id="body-detail">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
      </div>
    </div>      
  </div>    
</div>
@stop

@section('js')

<div class="modal-ajax" id="modal-action"></div>  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="{{ url('js/employeemenu.js') }}"></script>

@stop