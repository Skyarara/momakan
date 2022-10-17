@extends('adminlte::page')
@section('title', 'Momakan - Rekening')
@section('content_header')
<h1>Rekening</h1>
<ol class="breadcrumb">
    <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
    <li><i class="fa fa-bank"></i> Rekening </a>
</ol>
<input type="text" id="_token" value="{{ csrf_token() }}" hidden>
@stop

@section('content')
<div class="box box-danger">
    <div class="box-header with-border">
        <h1 class="box-title">Daftar Rekening</h1>
        <button class="create-modal btn btn-danger pull-right" id="new"><span class="glyphicon glyphicon-plus"></span> Tambah</button>
    </div>

    <table class="no-margin table table-hover" id="#">
        <thead>
            <tr>
            <th>ID</th>
            <th>Logo Bank</th>
            <th>Nama Bank</th>
            <th>Nama Akun</th>
            <th>Nomor Akun</th>
            <th style="text-align:center">Aksi</th>
            </tr>
        </thead>
        <tbody id="myTable">
            @foreach($data as $dt)
            <tr id="data{{$dt->id}}">
                <td>{{ $loop->iteration }}</td>
                <td><img id="bank_logo{{$dt->id}}" src="{{ asset('storage/images/' . $dt->bank_logo) }}" class="img-thumbnail" style="width:100px;height:100px"></td>
                <td>{{$dt->bank_name}}</td>
                <td>{{$dt->account_name}}</td>
                <td>{{$dt->account_number}}</td>
                <td style="text-align:center">
                    <a href="#" dusk="{{$dt->id}}" id="see" class="edit-modal btn btn-primary btn-sm" data-id="{{$dt->id}}" data-img="{{ asset('storage/images/' . $dt->bank_logo) }}" data-bank_name="{{$dt->bank_name}}" data-account_name="{{$dt->account_name}}" data-account_number="{{$dt->account_number}}"><i class="fa fa-eye"></i></button></a>

                    <a href="#" dusk="{{$dt->id}}" id="edit" class="edit-modal btn btn-warning btn-sm" data-id="{{$dt->id}}" data-img="{{ asset('storage/images/' . $dt->bank_logo) }}" data-bank_name="{{$dt->bank_name}}" data-account_name="{{$dt->account_name}}" data-account_number="{{$dt->account_number}}"><i class="glyphicon glyphicon-pencil"></i></a>
                    
                    <a href="#" id="delete{{$dt->id}}" onclick="delete_data({{ $dt->id }});" class="delete-modal btn btn-danger btn-sm" data-id="{{$dt->id}}" data-img="{{ asset('storage/images/' . $dt->bank_logo) }}" data-bank_name="{{$dt->bank_name}}" data-account_name="{{$dt->account_name}}" data-account_number="{{$dt->account_number}}"><i class="glyphicon glyphicon-trash"></i></a>
                </td>
            </tr>
            @endforeach
      </tbody>
    </table>
    
    <div class="box-footer">
    </div>

</div>


{{-- Add --}}
<div id="create" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close btn-sm" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
        <form enctype="multipart/form-data" id="add_form">
            {{ csrf_field() }}
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="bank_logo">Logo Bank</label>
                <div class="col-sm-10">
                    <input type="file" id="bank_logo" name="bank_logo" accept=".png, .jpg, .jpeg" id="Gambar" required>
                    <span id="er1" hidden><b><small><font color="red">Logo Bank belum diisi</font></b></small></span>
                </div>
            </div>

            <div class="form-group row add">
                <label class="control-label col-sm-2" for="bank_name">Nama Bank</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Nama Bank" required>
                    <span id="er2" hidden><b><small><font color="red">Nama Bank belum diisi</font></b></small></span>
                </div>
            </div>
        
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="account_name">Nama Akun</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="account_name" name="account_name" placeholder="Nama Akun" required>
                    <span id="er3" hidden><b><small><font color="red">Nama Akun belum diisi</font></b></small></span>
                </div>
            </div>
        
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="account_number">Nomer Akun</label>
                <div class="col-sm-10">
                    <input type="number" class="form-control" id="account_number" name="account_number" placeholder="Nomer Akun" required>
                    <span id="er4" hidden><b><small><font color="red">Nomer Akun belum diisi</font></b></small></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-info" type="submit" id="add"><span class="glyphicon glyphicon-plus"></span> Tambah</button>
                {{-- <button class="btn btn-secondary" type="button" data-dismiss="modal"><span class="glyphicon glyphicon-remobe"></span>Tutup</button> --}}
            </div>
        </form>
        </div>
    </div>
    </div>
</div>

{{-- Edit --}}

<div id="show" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
        <form enctype="multipart/form-data" id="edit_form">
        {{ csrf_field() }}
            <input type="hidden" class="form-control" name="id" id="id">
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="bank_name">Nama Bank</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit_bank_name" name="edit_bank_name" required>
                </div>
            </div>
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="account_name">Nama Akun</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit_account_name" name="edit_account_name" placeholder="Nama Bank" required>
                </div>
            </div>
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="account_number">Nomer Akun</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="edit_account_number" name="edit_account_number" placeholder="Nomor Bank" required>
                </div>
            </div>
            <div class="form-group row add">
                <label class="control-label col-sm-2" id="img" for="Logo Bank Lama"></label>
                <div class="col-sm-10">
                    <img id="my_image" style="width:200px;height:200px">
                </div>
            </div>
            <div class="form-group row add" id="edit_gambar">
                <label class="control-label col-sm-2" for="Logo Bank Baru">Logo Bank Baru</label>
                <div class="col-sm-10">
                    <input style="margin-top:5px;" type="file" id="gambar_baru" name="gambar_baru" accept=".png, .jpg, .jpeg" required>
                    <small><b>Kosongkan Jika Tidak Ingin Diganti</b></small>
                </div>
            </div>
        </form>
        <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal"><span class="glyphicon glyphicon"></span>Tutup</button>
            <button type="button" id="update" class="btn btn-warning" data-dismiss="modal"><span id="footer_action_button"></span></button>
        </div>
    </div>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('js/rekening/rekening.js') }}"></script>
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
        background: rgba( 255, 255, 255, .8 ) url("{{ url('/gif/' . 'Preloader_3.gif') }}") 50% 50% no-repeat;
    }
    body.loading {
        overflow: hidden;
    }
    body.loading .modal {
        display: block;
    }
</style>
@stop

@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('js/rekening/rekening.js') }}"></script>
@stop
  