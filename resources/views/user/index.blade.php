@extends('adminlte::page')

@section('title', 'Momakan - Pengguna')

@section('content_header')
    <h1>Halaman Pengguna</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
        <li><i class="fa fa-users"></i> Pengguna </a>
    </ol>
@stop

@section('content')
    {{-- Table User --}}
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Daftar Pengguna</h3>                 
            </div>    
    <table class="table table-bordered table-hover bg.white" id="table_id" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Original</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user as $data)
                <tr>
                <td>{{ $loop->iteration}}</td>
                <td> @switch($data->role_id)    
                      @case(1) {{$data->name}} @break;
                      @case(2) {{$data->name}} @break;
                      @case(3) {{$data->corporate->name}}  @break;
                      @endswitch                 
                </td>
                <td>{{ $data->email }}</td>
                <td>{{ $data->name }}</td>
                {{-- Menampilkan Role  --}}
                <td> 
                      @switch($data->role_id)    
                      @case(1) Admin @break;
                      @case(2) Pegawai @break;
                      @case(3) Perusahaan @break;
                      @endswitch                 
                </td>
                      <td><a href="#" id="edit{{ $data->id }}" class="edit-modal btn btn-warning btn-sm" data-id="{{$data->id}}" data-name="{{$data->name}}" data-email="{{$data->email}}" data-phone_number="{{$data->phone_number}}" ><i class="glyphicon glyphicon-pencil"></i></a>
                          {{-- <a href="#" id="gantipassword" class="password-modal btn btn-danger btn-sm" data-id="{{$data->id}}" ><i class="glyphicon glyphicon-lock"></i></a> --}}
                      </td>  
                </tr>
                @endforeach
            </tbody>
        </table>
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
                     <label class="control-label col-sm-2" id="main" name="id" for="id">ID</label>
                            <div class="form-group row add">
                              <label class="control-label col-sm-2" for="name">Nama :</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name"
                                placeholder="Nama" required>
                              </div>
                            </div>
                            <div class="form-group row add">
                                    <label class="control-label col-sm-2" for="Email">Email  :</label>
                                    <div class="col-sm-10">
                                      <input type="text" class="form-control" id="email" name="email"
                                      placeholder="Email" required>
                                    </div>
                                  </div>
                                  <div class="form-group row add">
                                        <label class="control-label col-sm-2" for="phone number">Nomor Hp :</label>
                                        <div class="col-sm-10">
                                          <input type="number" class="form-control" id="phone_number" name="phone_number"
                                          placeholder="Nomor Hp" required>
                                        </div>
                                      </div>
                          </form>
                        </div>
                            <div class="modal-footer">
                              <button class="btn btn-warning" type="submit" id="add" >
                                <span class="glyphicon glyphicon-plus"></span>Ubah
                              </button>
                              <button class="btn btn-secondary" type="button" data-dismiss="modal">
                                <span class="glyphicon glyphicon-remobe"></span>Tutup
                              </button>
                            </div>
                      </div>
                    </div>
                  </div>
                </div>
        {{-- Modal Ganti Password
        <div id="gantipass" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close btn-sm" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
                  <div class="box-error"></div>
               <label class="control-label col-sm-2" id="main" name="id" for="id">ID</label>
                      <div class="form-group row add">
                        <label class="control-label col-sm-2" for="name">Nama :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="name" name="name"
                          placeholder="Nama" required>
                        </div>
                      </div>
                      <div class="form-group row add">
                              <label class="control-label col-sm-2" for="Email">Email  :</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" id="email" name="email"
                                placeholder="Email" required>
                              </div>
                            </div>
                            <div class="form-group row add">
                                  <label class="control-label col-sm-2" for="phone number">Nomor Hp :</label>
                                  <div class="col-sm-10">
                                    <input type="number" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="Nomor Hp" required>
                                  </div>
                                </div>
                    </form>
                  </div>
                      <div class="modal-footer">
                        <button class="btn btn-warning" type="submit" id="add" >
                          <span class="glyphicon glyphicon-plus"></span>Ganti Password
                        </button>
                      </div>
                  </div>
                </div>
              </div>
            </div> --}}
@stop

@section('css')
        {{-- Mangil CSS Datatables --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
@stop

@section('js')
 {{-- CDN Datatables --}}
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
{{-- CDN sweetalert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
    <script>
      // hide column
     var v = 4;
    $('#table_id tr > *:nth-child('+v+')').toggle();
      // Mengunakan JS Data Tables
    $(document).ready( function () {
        $('#table_id').DataTable();
    } );
      // function Edit POST
  //Isi Modal User
$(document).on('click', '.edit-modal', function() {
$('#edit').modal('show');
$('.form-horizontal').show();
$('.modal-title').text('Ubah User');
$('#main').hide();
$('#main').val($(this).data('id'));
$('#name').val($(this).data('name'));
$('#email').val($(this).data('email'));
$('#phone_number').val($(this).data('phone_number'));
});
//Fungsi Tombol Ubah Pada Modal edit
$('.modal-footer').on('click', '#add', function() {
  $('#add').attr("disabled", true);
//Post Data Edit
  $.ajax({
      type: 'POST',
      url: '/user/edituser',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': $("#main").val(),
        'name': $("#name").val(),
        'email': $("#email").val(),
        'phone_number': $("#phone_number").val(),
      },
      success: function(data) {
        //Menampilkan Error
          if(data.error.length > 0)
        {
          $('#add').attr("disabled", false);
          var error_html = '';
          for(var count = 0; count < data.error.length; count++)
          {
            error_html += '<div class="alert alert-dismissible fade in" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +data.error[count]+ '</div>';
          }
          $('#edit').find('.box-error').html(error_html);
        }
        //Fungsi Berhasil
        else {
            $('#edit').modal('hide');
            swal({
    title: "Berhasil Mengubah Data",
    text: "",
    icon: "success",
    })
.then(function(){ 
   location.reload()
   }
);
        }
      },
    });

  });

    </script>
@stop