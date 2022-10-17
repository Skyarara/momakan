@extends('adminlte::page')

@section('title', 'Momakan - Promo')

@section('content_header')
     <h1>
        Halaman Promo Makanan
        <small>Dapur Bunda</small>
      </h1>
      <ol class="breadcrumb">
          <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
          <li><i class="fa fa-cutlery"></i> Menu </a>
          <li><a href="/menu_plan"><i class="fa fa-circle-o"></i> Promo </a>
      </ol>
      <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
@stop

@section('content')

    <div class="row">
        <div class="col-xs-12">
          <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Promo Makanan</h3>      
                    <button type="button" id="new" class="pull-right btn btn-danger"><i class="glyphicon glyphicon-plus"></i> Tambah</button>              
            </div>            
            <div class="box-body">                
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="table-data">
                    <tbody id="body-t">
                    <tr>
                      <th>No</th>
                      <th>Gambar</th>
                      <th>Nama Makanan</th>
                      <th style="width: 35%">Deskripsi</th>
                      <th>Aksi</th>
                    </tr>  
                    @foreach($promo as $dt)                   
                    <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td id="image{{$dt->id}}"><img id="image{{$dt->id}}" src="{{ asset('storage/images/' . $dt->image) }}" class="img-thumbnail" style="width:100px;height:100px"></td>
                    <td> {{ $dt->title }} </td>
                    <td> {{ $dt->description }} </td>
                      <td>
                        <div class="btn-group">
                          <a><button type="button" class="btn btn-primary" id="see" data-id="{{$dt->id}}" data-title="{{$dt->title}}" data-img= "{{ asset('storage/images/' . $dt->image) }}" data-desc="{{$dt->description}}"><i class="fa fa-eye"></i></button></a>
                        <a><button type="button" class="btn btn-warning" dusk="{{$dt->id}}" id="edit" data-id="{{$dt->id}}" data-title="{{$dt->title}}" data-img= "{{ asset('storage/images/' . $dt->image) }}" data-desc="{{$dt->description}}"><i class="fa fa-edit"></i></button></a>
                          <a><button type="button" class="btn btn-danger" id="delete{{$dt->id}}" onclick="delete_data({{ $dt->id }});"><i class="fa fa-remove"></i></button></a>
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
      {{-- Modal Form Create Post --}}
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
              <label class="control-label col-sm-2" for="title">Nama :</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="title" name="title"
                placeholder="Name" required>
                <span id="er1" hidden><b><small><font color="red">Nama Kosong</font></b></small></span>
              </div>
            </div>                
            <div class="form-group row add">
              <label class="control-label col-sm-2" for="deskripsi">Deskripsi :</label>
              <div class="col-sm-10">
                <textarea type="name" class="form-control" id="deskripsi" name="deskripsi"
                placeholder="deskripsi" required></textarea>
                <span id="er2" hidden><b><small><font color="red">Deskripsi Kosong</font></b></small></span>
              </div>
            </div>  
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="name">Gambar :</label>
                <div class="col-sm-10">
                    <input style="margin-top:5px;" type="file" name="gambar" id="gambar" accept=".png, .jpg, .jpeg" id="Gambar" required>
                  <span id="er3" hidden><b><small><font color="red">Gambar Kosong</font></b></small></span>
                </div>
              </div>     
            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-info" type="submit" id="add" >
              <span class="glyphicon glyphicon-plus"></span>Tambah
            </button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">
                <span class="glyphicon glyphicon-remobe"></span>Tutup
              </button>
            </div>
      </div>
    </div>
  </div></div>

  {{-- edit --}}
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
                        <label class="control-label col-sm-2" for="name">Nama :</label>
                        <div class="col-sm-10">
                            <input type="name" class="form-control" name="edit_title" id="edit_title" required>
                        </div>
                      </div>   
                        <div class="form-group row add">
                            <label class="control-label col-sm-2" for="Deskripsi">Deskripsi :</label>
                            <div class="col-sm-10">
                                <textarea type="name" class="form-control" name="edit_deskripsi" id="edit_deskripsi" required></textarea>
                            </div>
                          </div>
                    <div class="form-group row add">
                          <label class="control-label col-sm-2" id="img" for="gambar lama"></label>  
                          <div class="col-sm-10">
                          <img id="my_image" width="200px" height="200px">
                          </div>
                    </div>
                          <div class="form-group row add" id="edit_gambar">
                                <label class="control-label col-sm-2" for="gambar baru">Gambar Baru</label>  
                               <div class="col-sm-10">
                                    <input style="margin-top:5px;" type="file" name="gambar_baru" id="gambar_baru" accept=".png, .jpg, .jpeg" required>   
                                    <small><b>Kosongkan Jika Tidak Ingin Diganti</b></small>        
                                 </div>  
                         </div>
                </div>
                </form>
              <div class="modal-footer">   
                <button type="button" class="btn" data-dismiss="modal">
                  <span class="glyphicon glyphicon"></span>Tutup
                </button>      
                <button type="button" id="update" class="btn btn-warning" data-dismiss="modal" >
                  <span id="footer_action_button"></span>
                </button>       
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
  </style>
@stop

@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('js/promo/promo.js') }}"></script>
<script>

</script>
@stop
  