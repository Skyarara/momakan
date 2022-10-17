@extends('adminlte::page')

@section('title', 'Momakan - Perusahaan')

@section('content_header')
<h1>
    Halaman Perusahaan
    <small>Menampilkan Daftar Perusahaan Momakan</small>
</h1>
  <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
          <li><a href="/corporate"><i class="fa fa-industry"></i> Perusahaan </a>
  </ol>
@stop
@section('content') 
<div class="row">
    <div class="col-xs-12">
      <div class="box box-danger">
        <div class="box-header">
            <h3 class="box-title">Daftar Perusahaan</h3>   
            <button type="button" id="tbh" class="create-modal btn btn-danger float-right pull-right" data-toggle="modal">
                <span class="glyphicon glyphicon-plus"></span> Tambah
              </button>
              <br>           
        </div>   
        <div class="box-body"> 
    <table class="table table-hover">
        <thead>
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Nomor Telpon</th> 
          <th>Alamat</th>
          <th>Aksi</th>  
          <th>Karyawan</th>     
        </tr>
        </thead>
      <tbody id="myTable" >
        @foreach($data as $dt)
        <tr class="data{{$dt->id}}">
        <td>{{ (($data->currentPage() - 1 ) * $data->perPage() ) + $loop->iteration }}</td>  
        <td>{{$dt->name}}</td>
        <td>{{$dt->telp}}</td>  
        <td>{{$dt->address}}</td>
        <td>
            <a href="#" class="show-modal btn btn-success btn-sm" data-id="{{$dt->id}}" data-name="{{$dt->name}}" data-telp="{{$dt->telp}}" data-address="{{$dt->address}}">
                <i class="fa fa-eye"></i>
              </a>
              <a href="#" id="edit{{$dt->id}}" class="edit-modal btn btn-warning btn-sm" data-id="{{$dt->id}}" data-name="{{$dt->name}}" data-telp="{{$dt->telp}}" data-address="{{$dt->address}}">
                  <i class="glyphicon glyphicon-pencil"></i>
                </a>
                <a href="#" id="delete{{$dt->id}}" class="delete-modal btn btn-danger btn-sm" data-id="{{$dt->id}}" data-name="{{$dt->name}}" data-telp="{{$dt->telp}}" data-address="{{$dt->address}}">
                    <i class="glyphicon glyphicon-trash"></i>
                  </a>
        </td>  
        <td><a href='/employee/data/{{ $dt->id }}' id="employee" class="btn btn-danger"><span class="fa fa-list"></span> List Karyawan </a> 
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
    <div class="box-error"></div>
 <label class="control-label col-sm-2" id="main" for="id">ID</label>
           
        <form class="form-horizontal" role="form" id="mdform">
          <div class="form-group row add">
            <label class="control-label col-sm-2" for="name">Nama :</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="name" name="name"
              placeholder="Name Perusahaan" required>
              <span id="er1" hidden><b><small><font color="red">Field Nama Kosong</font></b></small></span>
            </div>
          </div>

          
          
          <div class="form-group row add">
            <label class="control-label col-sm-2" for="telp">telp :</label>
            <div class="col-sm-10">
              <input type="number" class="form-control" id="telp" name="telp"
              placeholder="Nomor Telpon" required>
              <span id="er2" hidden><b><small><font color="red">Field Telp Kosong</font></b></small></span>
            </div>
          </div>
          
          <div class="form-group row add">
            <label class="control-label col-sm-2" for="address">Alamat :</label>
            <div class="col-sm-10">
              <textarea type="name" class="form-control" id="address" name="address"
              placeholder="Alamat" required></textarea>
              <span id="er3" hidden><b><small><font color="red">Field Alamat Kosong</font></b></small></span>
            </div>
          </div>

          <div class="form-group row add">
            <label class="control-label col-sm-2" for="email">Email :</label>
            <div class="col-sm-10">
              <input type="email" class="form-control" id="email" name="email"
              placeholder="email" required>
              <span id="er4" hidden><b><small><font color="red">Field Email Kosong</font></b></small></span>
            </div>
          </div>
          
          <div class="form-group row add">
            <label class="control-label col-sm-2" for="pass">Password</label>
            <div class="col-sm-10">
                  <input type="password" class="form-control" id="pass" name="pass"
                  placeholder="Password" required>
                  <span id="erpass" hidden><b><small><font color="red">Field Password Kosong</font></b></small></span>
                </div>
              </div>
            
              <div class="form-group row add">
                  <label class="control-label col-sm-2" for="pass2">Konfirmasi Password :</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" id="pass2" name="pass2"
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
                        <textarea type="nama" class="form-control" id="nma" disabled ></textarea>
                     
                      <span id="desc" hidden><h3><b>Anda Yakin Mau Hapus  ?</h3></b></small></span>
                    </div>
                  </div>
                
                  <div class="form-group row add">
                      <label class="control-label col-sm-2" id="lb2" for="name">Nomor Telpon :</label>
                      <div class="col-sm-10">
                          <input type="number" class="form-control" name="telp" id="tp" required></input>
                          <textarea type="name" class="form-control" id="tpa" disabled></textarea>
                      </div>
                    </div>
    
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" id="lb3" for="name">Alamat :</label>
                        <div class="col-sm-10">
                            <textarea type="name" class="form-control" name="address" id="as" required></textarea>
                            <textarea type="name" class="form-control" id="asa" disabled ></textarea>
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
    
            <button type="button" id="update" class="btn actionBtn " data-dismiss="modal" >
              <span id="footer_action_button" ></span>
            </button>
    
            {{-- <button type="button" class="btn btn-warning brn-sm" data-dismiss="modal">
              <span class="glyphicon glyphicon"></span>Tutup
            </button> --}}
    
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

// /*
// -> Dusk Function
//    Hanya tersedia untuk pengetestan 
// */ 
// var NewNumData = document.getElementById('corporateTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr').length;
// function punchNewData()
// {
//     $("#delete-modal" + NewNumData).click();
// }
// // End

  function validateEmail(email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(String(email).toLowerCase());
  }
  // {{-- ajax Form Add Post--}}
  $(document).on('click','.create-modal', function() {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Perusahaan Baru');
    $('#fid').hide();
    $('#main').hide();
  });
  //add new coperate
 
  $("#name").keydown(function(){
    document.getElementById('er1').hidden=true;
});
$("#email").keydown(function(){
    document.getElementById('er4').hidden=true;
});
$("#address").keydown(function(){
    document.getElementById('er3').hidden=true;
});
$("#telp").keydown(function(){
    document.getElementById('er2').hidden=true;
});
$("#pass").keydown(function(){
    document.getElementById('erpass').hidden=true;
});
  $("#add").click(function() {
    document.getElementById('add').disabled = true;
    var name = document.getElementById('name').value;
    var telp = document.getElementById('telp').value;
    var address = document.getElementById('address').value;
    var email = document.getElementById('email').value;
    var pass = document.getElementById('pass').value;  
    var a = document.getElementById("pass").value;
    var b = document.getElementById("pass2").value;  

    //Rasyid Function
    if(name == null || name == "" ||
       telp == null || telp == "" ||
       address == null || address == "" ||
       email == null || email == "" ||
       pass == null || pass == "")
    {
      if(name == null || name == ""){
        document.getElementById('er1').hidden = false;
      }
      if(telp == null || telp == ""){
        document.getElementById('er2').hidden = false;
      }
      if(address == null || address == ""){
        document.getElementById('er3').hidden = false;
      }
      if(email == null || email == ""){
        document.getElementById('er4').hidden = false;
        document.getElementById('er4').getElementsByTagName('font')[0].innerHTML = "Email Belum Diisi";
      }
      if(pass == null || pass == "")
      {
        document.getElementById('erpass').hidden = false;
      }
      document.getElementById('add').disabled = false;
      return;
    }
    if(!validateEmail(email))
    {
        document.getElementById('er4').hidden = false; 
        document.getElementById('add').disabled = false;   
        return document.getElementById('er4').getElementsByTagName('font')[0].innerHTML = "Email Blum valid";
    }
    //End
    document.getElementById('er1').hidden = true;
    document.getElementById('er2').hidden = true;
    document.getElementById('er3').hidden = true;
    document.getElementById('er4').hidden = true;
    document.getElementById('pass').hidden = true;
  
    $.ajax({
      type: 'POST',
      url: 'corporate/addCorporate',
      data: {
        '_token': $('input[name=_token]').val(),
        'name': $('input[name=name]').val(),
        'telp': $('input[name=telp]').val(),
        'address': $('textarea[name=address]').val(),
        'email': $('input[name=email]').val(),
        'pass': $('input[name=pass]').val(),
        'pass2': $('input[name=pass2]').val(),
      },
      success: function(data){
        document.getElementById('add').disabled = false;
        //richard function
        if(data.error.length > 0)
        {
          var error_html = '';
          for(var count = 0; count < data.error.length; count++)
          {
            error_html += '<div class="alert alert-dismissible fade in" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +data.error[count]+ '</div>';
          }
          $('#create').find('.box-error').html(error_html);
        }
           else {
            $('#create').modal('hide');
          swal({
          title: "Berhasil Menambahkan Data",
          text: "",
          icon: "success",
          })
          .then(function(){ 
          location.reload();
          }
);
            var lengTable = document.getElementById('corporateTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr').length + 1;
            NewNumData = lengTable;
        }
      },
    });    
  });
// function Edit POST
$(document).on('click', '.edit-modal', function() {
$('#footer_action_button').text("Ubah");
$('.actionBtn').addClass('btn-success');
$('.actionBtn').removeClass('btn-danger');
$('.actionBtn').addClass('edit');
$('.modal-title').text('Ubah Perusahaan');
$('.deleteContent').hide();
$('.form-horizontal').show();
$('#descname').hide();
$('#nma').hide();
$('#tpa').hide();
$('#asa').hide();
$('#nm').show();
$('#tp').show();
$('#as').show();
$('#id').val($(this).data('id'));
$('#nm').val($(this).data('name'));
$('#tp').val($(this).data('telp'));
$('#as').val($(this).data('address'));
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
      url: 'corporate/editCorporate',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': $("#id").val(),
        'name': $("#nm").val(),
        'telp': $('#tp').val(),
        'address': $('#as').val()
      },
      success: function(data) {
        if (data.errors){
        swal("Gagal Mengubah Data", "Terdapat Field Kosong" , "error");
        }
          else{
      $('.data' + data.id).replaceWith(" "+
      "<tr class='data" + data.id + "'>"+
      "<td>" + data.id + "</td>"+
      "<td>" + data.name + "</td>"+
      "<td>" + data.telp + "</td>"+
      "<td>" + data.address + "</td>"+
 "<td><button class='show-modal btn btn-success btn-sm' data-id='" + data.id + "' data-name='" + data.name + "' data-telp='" + data.telp + "'data-address='" + data.address + "'><span class='fa fa-eye'></span></button> <button class='edit-modal btn btn-warning btn-sm' data-id='" + data.id + "' data-name='" + data.name + "' data-telp='" + data.telp + "' data-address='" + data.address + "'><span class='glyphicon glyphicon-pencil'></span></button> <button class='delete-modal btn btn-danger btn-sm' data-id='" + data.id + "' data-name='" + data.name + "' data-telp='" + data.telp + "' data-address='" + data.address + "'><span class='glyphicon glyphicon-trash'></span></button></td><td><a href='/employee/data/" + data.id + "' class='btn btn-danger' data-id='" + data.id + "'><span class='fa fa-list'></span> List Karyawan</a></td>"+"</tr>");
 swal("Berhasil Mengubah Data", "" , "success");
 
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
$('#nma').hide();
$('#tpa').hide();
$('#asa').hide();
$('#descname').show();
$('#desc').show();
$('#footer_action_button').text(" Delete");
$('.actionBtn').removeClass('btn-success');
$('.actionBtn').addClass('btn-danger');
$('.actionBtn').addClass('delete');
$('.modal-title').text('Delete Corperate');
$('.id').text($(this).data('id'));
$('.deleteContent').show();
$('.form-horizontal').hide();
$('#descname').text($(this).data('name'));
$('#show').modal('show');
});
$('.modal-footer').on('click', '.delete', function(){
  $.ajax({
    type: 'POST',
    url: '/corporate/deleteCorporate',
    data: {
      '_token': $('input[name=_token]').val(),
      'id': $('.id').text()
    },
    success: function(data){
      if(data == 3)
      {
        swal("Gagal Menghapus Data", "Perusahaan Ini Memiliki Kontrak", "error");
      } 
      else 
      {
        swal("Berhasil Menghapus Data", "", "success");
        $('.data' + $('.id').text()).remove();
      }      
    }
  });
});

  // Show function
  $(document).on('click', '.show-modal', function() {
  $('#show').modal('show');
  $('#nma').text($(this).data('name'));
  $('#tpa').text($(this).data('telp'));
  $('#asa').text($(this).data('address'));
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
  $('#descname').hide();
  $('#update').hide();
  $('#desc').hide();
  
  
  

  });
    </script>
@stop