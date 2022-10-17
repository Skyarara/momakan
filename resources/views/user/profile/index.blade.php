@extends('adminlte::page')

@section('title', 'Dapur Bunda - Makanan')

@section('content_header')
     <h1>Profil {{$profile->corporate->name}}</h1>
      <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i> Home</a></li>
        <li><a href="{{$id}}"><i class="fa fa-user"></i> Profile</a></li>
      </ol>
@stop
@section('content')
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">{{$profile->corporate->name}}</h3>    
                  <button type="button" id="editan" class="btn btn-warning pull-right"><i class="fa fa-edit"></i> Ubah</button></a>                             
            </div>            
            <div class="box-body">                
                <div class="box-body table-responsive no-padding">
                    <div class="form-group">
                        <label for="email">email</label>
                        <input type="email" name="email" class="form-control" value="{{$profile->email}}" readonly> 
                    </div>
                    <div class="form-group">
                            <label for="Corporate_phone">Nomor telepon Perusahaan</label>
                            <input type="text" name="Corporate_phone" class="form-control" value="{{$profile->corporate->telp}}" readonly>
                    </div>
                    <div class="form-group">
                            <label for="phone">Alamat Perusahaan</label>
                            <input type="text" name="corporate address" class="form-control" value="{{$profile->corporate->address}}" readonly>
                    </div>  
               </div>
                  <div class="box-footer">
                  </div>
                </div>                                  
            </div>
        </div>
    </div>
  </div>
            {{-- Modal Edit --}}
            <div id="edit" class="modal fade" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close btn-sm" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="box-error"></div>
                        <input type="hidden" name="corporate_id" id="corporate_id" value="{{$profile->corporate->id}}">
                        <input type="hidden" name="id" id="id" value="{{$profile->id}}">
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                            <div class="form-group row add">
                              <label class="control-label col-sm-2" for="name">Email :</label>
                              <div class="col-sm-10">
                                <input type="email" class="form-control" id="email_edit" name="email_edit"
                                placeholder="email" value="{{$profile->email}}">
                              </div>
                            </div>
                                  <div class="form-group row add">
                                        <label class="control-label col-sm-2" for="phone number">Nomor Telepon :</label>
                                        <div class="col-sm-10">
                                          <input type="number" class="form-control" id="phone_edit" name="phone_edit"
                                          placeholder="Nomor Telepon Perusahaan" value="{{$profile->corporate->telp}}">
                                        </div>
                                      </div>
                                      <div class="form-group row add">
                                          <label class="control-label col-sm-2" for="phone number">Alamat :</label>
                                          <div class="col-sm-10">
                                            <input type="text" class="form-control" id="address_edit" name="address_edit"
                                            placeholder="Alamat Perusahaan" value="{{$profile->corporate->address}}">
                                          </div>
                                        </div>
                          </form>
                        </div>
                            <div class="modal-footer">
                              <button class="btn btn-warning" type="submit" id="update" >
                                <span class="glyphicon glyphicon-edit"></span>Ubah
                              </button>
                              <button class="btn btn-secondary" type="button" data-dismiss="modal">
                                <span class="glyphicon glyphicon-remobe"></span>Tutup
                              </button>
                            </div>
                      </div>
                    </div></div>
                  </div>

@stop

@section('css')
<style>
  </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('js/profile.js') }}"></script>
@stop
  