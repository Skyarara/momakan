@extends('adminlte::page')

@section('title', 'Momakan - Pegawai Perusahaan')

@section('content_header')
    <h1>Halaman Pegawai {{ $corporate->name }}</h1>
    <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
          <li><a href="/corporate"><i class="fa fa-industry"></i> Perusahaan </a>
            <li><a href="#"><i class="fa fa-users"></i> Pegawai Perusahaan  {{ $corporate->name }}</a>
  </ol>
@stop

@section('content')
@if((session('info')))
<div class="alert alert-success alert-dismissible fade in col-sm-8" style=padding-top:10px;padding-bottom:10px;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  {{session('info')}}
</div>
@endif 

@if(count($errors)>0)
@foreach ($errors->all() as $error)
<div class="alert alert-dismissible fade in col-sm-7" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{$error}}
  </div>
    @endforeach
@endif
<div class="row">
    <div class="col-xs-12">
      <div class="box box-danger">
        <div class="box-header">
            <h4 class="box-title">Daftar Pegawai Perusahaan</h4>   
            <button type="button" id="tbh" class="create-modal btn btn-danger pull-right" data-toggle="modal" >
                <span class="glyphicon glyphicon-plus"></span> Tambah
              </button>
            <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#excelmodal" style="margin-right: 5px;">
              <span class="glyphicon glyphicon-plus"></span> Import
            </button>
                  {{-- Modal Import --}}
            <div class="modal fade" id="excelmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">              
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>                  
                      <h4 class="modal-title" id="exampleModalLabel">Import</h4>              
                    </div>                 
                    <div class="modal-body">            
                    <form method="POST" action="{{ route('contract.import') }}" class="form-horizontal" data-toggle="validator" enctype="multipart/form-data">
                      {{ csrf_field() }}                 
                      <input type="text" name="idk" value="{{ $corporate->id }}" for="idk" hidden>
                      <label for="file" class="control-label">Pilih File :</label>              
                      <input type="file" accept=".xlsx" name="file" id="file" class="form-control" autofocus required>
                      <span class="help-block with-errors">               
                      <p>Contoh File : <u><b><a href="{{ url('/document/contoh.xlsx') }}">contoh.xlsx</a></b></u></p>
                    </div>
                      <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Tutup</button> --}}
                        <button type="submit" class="btn btn-primary">Import</button>
                      </div>
                    </form>
                  </div>
                </div>
            </div>                  
        </div>            
        <div class="box-body">                
            <div class="box-body table-responsive no-padding">
    <table class="table table-hover " id="employeeTable">
        <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Nomor Telpon</th> 
          <th>Email</th>
        </tr>
        </thead>
      <tbody id="myTable" >
        @foreach($data as $dt)
        {{-- {{dd($dt)}} --}}
        <tr class="data{{$dt->id}}">
          <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>
          <td>{{ $dt->user->name}}</td>
          <td>{{ $dt->user->phone_number}}</td>  
          <td>{{ $dt->user->email}}</td>
          <td>

          {{-- {{dd($data)}} --}}
          <a href="/employeefood/{{$dt->id}}" id="employee-menu{{ $loop->iteration }}" class="btn btn-primary btn-sm"><i class="fa fa-cutlery"></i></a>
            <a href="#" class="show-modal btn btn-success btn-sm" data-id="{{$dt->id}}" data-name="{{$dt->user->name}}"  data-phone_number="{{$dt->user->phone_number}}" data-email="{{$dt->user->email}}">
                <i class="fa fa-eye"></i>
              </a>
              <a href="#" id="edit-modal{{ $loop->iteration }}" class="edit-modal btn btn-warning btn-sm" data-id="{{$dt->user->id}}" data-name="{{$dt->user->name}}"  data-phone_number="{{$dt->user->phone_number}}" data-email="{{$dt->user->email}}">
                  <i class="glyphicon glyphicon-pencil"></i>
                </a>
                <a href="#" id="delete-modal{{ $loop->iteration }}" class="delete-modal btn btn-danger btn-sm" data-id="{{$dt->user->id}}" data-name="{{$dt->user->name}}"  data-phone_number="{{$dt->user->phone_number}}" data-email="{{$dt->user->email}}">
                    <i class="glyphicon glyphicon-trash"></i>
                  </a>
        </td>  
        </tr>
        @endforeach
      </tbody>                  
    </table>
    <div class="box-footer" style="text-align:center;">
      {{$data->links()}}
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

        <label class="control-label col-sm-2" id="main" for="id">ID</label>
     
            <div class="box-error"></div>
        <form class="form-horizontal" role="form" id="mdform">
          <div class="form-group row add">
            <label class="control-label col-sm-2" for="name">Name :</label>
            <div class="col-sm-10">
              <input type="name" class="form-control" id="name" name="name"
              placeholder="Nama Karyawan" required>
              <span id="er1" hidden><b><small><font color="red">Nama Karyawan Masih Kosong</font></b></small></span>
            </div>
          </div>
          
          <div class="form-group row add">
            <label class="control-label col-sm-2" for="phone_number">Telp :</label>
            <div class="col-sm-10">
              <input type="number" class="form-control" id="phone_number" name="phone_number"
              placeholder="Telp" required>
              <span id="er2" hidden><b><small><font color="red">Telp Masih Kosong</font></b></small></span>
            </div>
          </div>

          <div class="form-group row add">
              <label class="control-label col-sm-2" for="email">Email :</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="email" name="email"
                placeholder="Email" required>
                <span id="er3" hidden><b><small><font color="red">Email Masih Kosong</font></b></small></span>
                <span id="eremail" hidden><b><small><font color="red">Format Email tidak cocok</font></b></small></span>
              </div>
            </div>
            
            <div class="form-group row add">
                <label class="control-label col-sm-2" for="password">Password :</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password" name="password"
                  placeholder="Password" required>
                  <span id="er4" hidden><b><small><font color="red">Password Masih Kosong</font></b></small></span>
                </div>
              </div>

              <div class="form-group row add">
                <label class="control-label col-sm-2" for="password">Konfirmasi Password :</label>
                <div class="col-sm-10">
                  <input type="password" class="form-control" id="password2" name="password2"
                  placeholder="Konfirmasi Password" required>
                </div>
              </div>

        </form>
      </div>
          <div class="modal-footer">
            <button class="btn btn-info" type="submit" id="add" >
              <span class="glyphicon glyphicon-plus"></span>Tambah
            </button>
            {{-- <button class="btn btn-secondary" type="button" data-dismiss="modal">
              <span class="glyphicon glyphicon-remobe"></span>Tutup
            </button> --}}
          </div>
    </div>
  </div>
</div></div>
{{-- Modal Form Show POST --}}
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
                        <textarea type="name" class="form-control" id="nma" disabled ></textarea>
                      
                      <span id="desc" hidden><b>Apakah Anda ingin menghapus data ini ?</b></small></span>
                    </div>
                  </div>
                
                  <div class="form-group row add">
                      <label class="control-label col-sm-2" id="lb2" for="name">Telp :</label>
                      <div class="col-sm-10">
                          <input type="number" class="form-control" name="phone_number" id="tp" required></input>
                          <textarea type="name" class="form-control" id="tpa" disabled></textarea>
                        
                      </div>
                    </div>
    
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" id="lb3" for="name">Email :</label>
                        <div class="col-sm-10">
                            <input type="name" class="form-control" name="email" id="as" required></input>
                            <textarea type="name" class="form-control" id="asa" disabled ></textarea>
                          
                        </div>
                      </div>
                    {{-- Form Delete Post --}}
            <div class="deleteContent">
              <span id="iddel" class="hidden id"></span>
            </div>
          </div>
          <b id="descname" style="font-size:20px; margin-left:2%;"></b>
          <div class="modal-footer">
            <button type="button" id="update" class="btn actionBtn " data-dismiss="modal" >
              <span id="footer_action_button" ></span>
            </button>
    
          </div>
        </div>
      </div>
    </div>
    </table>
@stop



@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">

/*
-> Dusk Function
   Hanya tersedia untuk pengetestan 
*/ 
var NewNumData = document.getElementById('employeeTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr').length;

function punchNewDatas()
{
    $("#edit-modal" + NewNumData).click();
} 

function punchNewData()
{
    $("#delete-modal" + NewNumData).click();
}
// End

  $(document).on('click','.create-modal', function() {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Tambah Karyawan');
    $('#fid').hide();
    $('#main').hide();
  });
  //add new coperate
 
  $("#name").keydown(function(){
    document.getElementById('er1').hidden=true;
});
$("#phone_number").keydown(function(){
    document.getElementById('er2').hidden=true;
});
$("#email").keydown(function(){
    document.getElementById('er3').hidden=true;
});
$("#password").keydown(function(){
    document.getElementById('er4').hidden=true;
});
  function validateEmail(email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(String(email).toLowerCase());
  }
  $("#add").click(function() {

    //Rasyid Function
    var name = document.getElementById('name').value;
    var phone_number = document.getElementById('phone_number').value;
    var email = document.getElementById('email').value;
    var password = document.getElementById('password').value;
    var a = document.getElementById("password").value;
    var b = document.getElementById("password2").value;

    var err = false;

    if(name == null || name == ""){
        document.getElementById('er1').hidden = false;
        err = true;
    }
    if(phone_number == null || phone_number == ""){
        document.getElementById('er2').hidden = false;
        err = true;
    }
    if(email == null || email == ""){
      document.getElementById('er3').hidden = false;
      err = true;
    } else if(validateEmail(email) != true ){    
      document.getElementById('eremail').hidden = false;
      err = true;
    }
    if(password == null || password == ""){
      document.getElementById('er4').hidden = false;   
      err = true;      
    }          

    document.getElementById('er1').hidden=true;
    document.getElementById('er2').hidden=true;
    document.getElementById('er3').hidden=true;
    document.getElementById('er4').hidden=true;
    
    document.getElementById('add').disabled = true;
    $.ajax({
      type: 'POST',
      url: '/employee/addEmployee',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': {{ $id_corporate }},
        'name': $('input[name=name]').val(),
        'phone_number': $('input[name=phone_number]').val(),
        'email': $('input[name=email]').val(),
        'password': $('input[name=password]').val(),
        'password2': $('input[name=password2]').val()
      },
      success: function(data){
        if(data.error.length > 0)
        {
              $('#eremail').hide();
          var error_html = '';
          for(var count = 0; count < data.error.length; count++)
          {
            error_html += '<div class="alert alert-dismissible fade in" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +data.error[count]+ '</div>';
          }
          $('#create').find('.box-error').html(error_html);
        
           document.getElementById('add').disabled = false;
          } else {
            swal({
            title: "Berhasil Menambahkan Data",
            text: "",
            icon: "success",
            })
            .then(function(){ 
              location.reload();
              }
            );
          $('#create').modal('hide');
        }      
      }
    });

  });
// function Edit POST
$(document).on('click', '.edit-modal', function() {
$('#footer_action_button').text("Ubah");
$('.actionBtn').addClass('btn-success');
$('.actionBtn').removeClass('btn-danger');
$('.actionBtn').addClass('edit');
$('.actionBtn').removeClass('delete');
$('.modal-title').text('Ubah Karyawan');
$('.deleteContent').hide();
$('.form-horizontal').show();
$('#descname').hide();
$('#nma').hide();
$('#tpa').hide();
$('#asa').hide();
$('#nm').show();
$('#tp').show();
$('#as').show();
$('#aw').show();
$('#id').val($(this).data('id'));
$('#nm').val($(this).data('name'));
$('#tp').val($(this).data('phone_number'));
$('#as').val($(this).data('email'));
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
  var email = document.getElementById('as').value;
  if(!validateEmail(email))
  { 
    $("#show").modal('show');
    swal('Gagal Mengubah data', 'Email tidak valid! Harap coba lagi', 'error');
    return;}
  $.ajax({
      type: 'POST',
      url: '/employee/editEmployee',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': $("#id").val(),
        'name': $("#nm").val(),
        'phone_number': $('#tp').val(),
        'email': $('#as').val()
      },
      success: function(data) {
        if (data.errors){
        swal("Gagal Mengubah Data", "Terdapat Field Kosong" , "error");
        }
        else{
          swal({
            title: "Berhasil Mengubah Data",
            text: "",
            icon: "success",
            })
            .then(function(){ 
              location.reload();
              });
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
$('#lb3').hide();
$('#update').show();
$('#nma').hide();
$('#tpa').hide();
$('#asa').hide();
$('#aw').hide();
$('#descname').show();
$('#desc').show();
$('#footer_action_button').text("Hapus");
$('.actionBtn').addClass('btn-danger');
$('.actionBtn').removeClass('btn-success');
$('.actionBtn').addClass('delete');
$('.actionBtn').removeClass('edit');
$('.modal-title').text('Hapus Karyawan');
$('.id').text($(this).data('id'));
$('.deleteContent').show();
$('.form-horizontal').hide();
$('#descname').text($(this).data('name'));
$('#show').modal('show');
});
$('.modal-footer').on('click', '.delete', function(){
  $.ajax({
    type: 'POST',
    url: '/employee/deleteEmployee',
    data: {
      '_token': $('input[name=_token]').val(),
      'id': $('.id').text()
    },
    success: function(data){
      if(data == 3){
        swal({
            title: "Gagal Menghapus Data",
            text: "Data terhubung dengan kontrak pegawai",
            icon: "error",
            })
      }else{
        
      swal({
            title: "Berhasil Menghapus Data",
            text: "",
            icon: "success",
            })
            .then(function(){ 
              location.reload();
              });
      }
    }
  });
});

  // Show function
  $(document).on('click', '.show-modal', function() {
  $('#show').modal('show');
  $('#nma').text($(this).data('name'));
  $('#tpa').text($(this).data('phone_number'));
  $('#asa').text($(this).data('email'));
  $('#nm').hide();
  $('#tp').hide();
  $('#as').hide();
  $('.modal-title').text($(this).data('name'));
  $('#nma').show();
  $('#tpa').show();
  $('#asa').show();
  $('#lb1').show();
  $('#lb2').show();
  $('#lb3').show();
  document.getElementById('desc').hidden=true;
  // document.getElementById('descname').hidden=true;
  $('#descname').hide();
  $('#update').hide();
  $('#desc').hide();
  });
    </script>
@stop