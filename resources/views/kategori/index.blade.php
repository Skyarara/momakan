{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Momakan - Kategori Makanan')

@section('content_header')
    <h1>Halaman Kategori</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
        <li><i class="fa fa-cutlery"></i> Menu </a>
        <li><a href="/menu_plan"><i class="fa fa-circle-o"></i> Kategori </a>
    </ol>
@stop

@section('content')
          <div class="row">
              <div class="col-xs-12">
                <div class="box box-danger">
                  <div class="box-header">
                      <a class="btn btn-danger create-modal btn-sm pull-right">
                          <i class="glyphicon glyphicon-plus"></i> Tambah Kategori
                        </a>
                      <h3 class="box-title"> Daftar Kategori Menu</h3> 
                        <div class="row">               
                        </div>                 
                  </div>            
                  <div class="box-body">                
                      <div class="box-body table-responsive no-padding">
<table class="table table-hover" id="table">
      <tr>
        <th>No</th>
        <th>Nama Kategori</th>
        <th>Aksi</th>
      </tr>
      {{ csrf_field() }}
      @foreach ($data as $dt)
        <tr class="data{{$dt->id}}">
          <td>{{ $loop->iteration }}</td>
          <td>{{ $dt->name }}</td>
          <td>
          <a href="#" id="edit{{ $dt->id }}" class="edit-modal btn btn-warning btn-sm" data-id="{{$dt->id}}" data-name="{{$dt->name}}">
              <i class="glyphicon glyphicon-pencil"></i>
            </a>
          <a href="#" id="delete{{ $dt->id }}" class="delete-modal btn btn-danger btn-sm" data-id="{{$dt->id}}" data-name="{{$dt->name}}">
              <i class="glyphicon glyphicon-trash"></i>
            </a>
          </td>
        </tr>
      @endforeach
    </table>
  </div>                                  
</div>
</div>
</div>
    {{$data->links()}}
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
      
                <div class="form-group">
                    <label class="control-label col-sm-2" id="main" for="id">ID</label>
                    <div class="col-sm-10">
                   
                    </div>
                  </div>
      
              <form class="form-horizontal" role="form" id="mdform">
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="name">Nama :</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name"
                    placeholder="Nama Kategori" required>
                    <span id="er1" hidden><b><small><font color="red">Kategori Masih Kosong</font></b></small></span>
                  </div>
                </div>

              </form>
            </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-info" id="add" >
                    Tambah
                  </button>
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remobe"></span>Tutup
                  </button>
                </div>
          </div>
        </div>
      </div></div>
      <div id="show" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                  </div>
                  <div class="modal-body">
                    <form class="form-horizontal" role="modal">
                        <span name="id" id="id"></span>
                      
                        <div class="form-group row add">
                            <label class="control-label col-sm-2" id="lb1" for="name">Nama :</label>
                            <div class="col-sm-10">
                                <input type="name" class="form-control" name="name" id="nm" required></input>
                              <span id="er4" hidden><b><small><font color="red">Nama Kategori Masih Kosong</font></b></small></span>
                              <span id="desc" hidden><b>Apakah Anda ingin menghapus data ini ?</b></small></span>
                            </div>
                          </div>
                        
                    </form>
                            {{-- Form Delete Post --}}
                    <div class="deleteContent">
                      <span id="iddel" class="hidden id"></span>
                    </div>
                    <b type="name" id="descname" style="font-size:20px;"></b>
                  </div>
                  <div class="modal-footer">
            
                    <button type="button" id="update" class="btn actionBtn " >
                      <span id="footer_action_button" ></span>
                    </button>
            
                    {{-- <button type="button" class="btn btn-warning brn-sm" data-dismiss="modal">
                      <span class="glyphicon glyphicon"></span>Tutup
                    </button> --}}
            
                  </div>
                </div>
              </div>
            </div>

    </div>




@stop

@section('css')
  
@stop

@section('js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
  //enter function
  var input = document.getElementById("name");
input.addEventListener("keypress", function(event) {
  if (event.keyCode === 13) {
   document.getElementById("add").click();
  }
});
 
//modal create
$(document).on('click','.create-modal', function() {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Kategori');
    $('#main').hide();
  });
  //add new kategori
 
  $("#name").keydown(function(){
    document.getElementById('er1').hidden=true;
});
  $("#add").click(function() {
    var name = document.getElementById('name').value;
    document.getElementById('er1').hidden=true;
    $('#add').attr("disabled", true);
    
    $.ajax({
      type: 'POST',
      url: 'addKategori',
      data: {
        '_token': $('input[name=_token]').val(),
        'name': $('input[name=name]').val()
      },
      success: function(data){
        if (data.errors) ;
          if(name == null || name == ""){
            document.getElementById('er1').hidden=false;
          $('#add').attr("disabled", false);
          }
           else {
          $('#create').modal('hide');
            swal({
    title: "Berhasil Menambahkan Data",
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
  // function Edit POST
  $("#nm").keydown(function(){
    $('#er4').hide();
});
$(document).on('click', '.edit-modal', function() {
$('#footer_action_button').text("Ubah");
$('.actionBtn').addClass('btn-success');
$('.actionBtn').removeClass('btn-danger');
$('.actionBtn').removeClass('delete');
$('.actionBtn').addClass('edit');
$('.modal-title').text('Ubah Kategori');
$('.deleteContent').hide();
$('.form-horizontal').show();
$('#descname').hide();
$('#nm').show();
$('#tp').show();
$('#as').show();
$('#id').val($(this).data('id'));
$('#nm').val($(this).data('name'));
$('#show').modal('show');
$('#update').show();
$('#desc').hide();
$('#lb1').show();
$('#lb2').show();
$('#lb3').show();
document.getElementById('desc').hidden=true;
document.getElementById('descname').hidden=true;


});

$('.modal-footer').on('click', '.edit', function() {
  $.ajax({
      type: 'POST',
      url: 'editKategori',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': $("#id").val(),
        'name': $("#nm").val()
      },
      success: function(data) {
        if (data == 1) {
          $('#er4').show();
        }else{
 swal({
            title: "Berhasil Mengubah Data",
            text: "",
            icon: "success",
            })
            .then(function(){ 
              location.reload();
              });
 $('#er4').hide();
 $('#show').modal('hide');
      }
    }
  });
});
  
  // form Delete function
$(document).on('click', '.delete-modal', function() {
 $('#nm').hide();
  $('#tp').hide();
  $('#as').hide();
  $('#lb1').hide();
  $('#lb2').hide();
  $('#lb3').hide();
  $('#update').show();
  $('#descname').show();
  $('.actionBtn').removeClass('edit');
  $('#desc').show();
$('#footer_action_button').text(" Hapus");
$('.actionBtn').removeClass('btn-success');
$('.actionBtn').addClass('btn-danger');
$('.actionBtn').addClass('delete');
$('.modal-title').text('Hapus Kategori');
$('.id').text($(this).data('id'));
$('.deleteContent').show();
$('.form-horizontal').hide();
$('#descname').text($(this).data('name'));
$('#show').modal('show');
});
$('.modal-footer').on('click', '.delete', function(){
  $.ajax({
    type: 'POST',
    url: 'deleteKategori',
    data: {
      '_token': $('input[name=_token]').val(),
      'id': $('.id').text()
    },
    success: function(data){
      if(data == 0){
        return swal("Gagal Menghapus Data", "Data tidak bisa dihapus dikarenakan relasi dengan data lain", "warning");
      } else {
        swal({
            title: "Berhasil Menghapus Data",
            text: "",
            icon: "success",
            })
            .then(function(){ 
              location.reload();
              });
        $('#show').modal('hide');
        $('.data' + $('.id').text()).remove();
      }
       
    },
    error: function(data){
      swal("Gagal Menghapus Data", "Data tidak bisa dihapus dikarenakan relasi dengan data lain", "warning");
      $('#show').modal('hide');
    }
  });
});


</script>
@stop