@extends('adminlte::page')

@section('title', 'Momakan - Notifikasi')

@section('content_header')
    <h1>Halaman Notifikasi</h1>
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
        <li><i class="fa fa-bell"></i> Notifikasi </a>
    </ol>
    <small>Memberikan Notifikasi untuk pengguna Dapur Bunda di Android</small>
@stop

@section('content')
  <div class="box box-danger">
      <div class="box-header">
          <label>Notifikasi</label><br><small>Mengirim semua notifikasi tentang informasi Menu makanan</small>
          <button type="button" id="tbh" class="create-modal pull-right btn btn-danger" style="margin-right:10px;" data-toggle="modal">
            <span class="glyphicon glyphicon-plus"></span> Tambah
          </button>
      </div>
      <div class="box-body">
        @if(Session::has('message'))
          <div class="alert alert-success">
            {{ Session::get('message') }}
          </div>
        @endif
        @if(Session::has('messages'))
          <div class="alert alert-danger">
              <i class="fa fa-info"></i>
            {{ Session::get('messages') }}
          </div>
        @endif
        <form method="POST" style="margin-top:5px;" id="form-data">
          {{ csrf_field() }}
          {{-- <label>Pilih Isi Notifikasi</label> --}}
          <select class="form-control" name="pesan" id="pesan" data-placeholder="Pilih Pemberitahuan">
            @if(count($configs) != 0)
              @foreach($configs as $dt)
              <option value="#">Pilih Pesan Notifikasi</option>
                <option value="{{ $dt->id }}">{{ $dt->letter_text }}</option>                       
              @endforeach
            @else
                <option value="NULL">Pesan Kosong! Tambahkan Pesan terlebih dahulu!</option>
            @endif
          </select>          
          <input type="button" value="Kirim Pemberitahuan" style="margin-top: 5px;" class="btn btn-danger" id="kirim">
        </form>
      </div>  
      <div class="box-footer">
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
  
            <div class="form-group">
                <label class="control-label col-sm-2" id="main" for="id">ID</label>
                <div class="col-sm-10">
               
                </div>
              </div>
  
          <form class="form-horizontal" role="form" id="mdform">
            <div class="form-group row add">
              {{-- <label class="control-label col-sm-2" for="isi">Surat :</label> --}}
              <div class="col-sm-12">
                <textarea rows="15" cols="15" type="text" class="form-control" id="isi" name="isi"
                placeholder="Isi Notifikasi" required></textarea>
                <span id="er1" hidden><b><small><font color="red">Isi Notifikasi Masih Kosong</font></b></small></span>
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
  </div>
</div>

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
                    <label class="control-label col-sm-2" id="lb1" for="name">Isi Pesan :</label>
                    <div class="col-sm-10">
                        <textarea rows="17" cols="20" type="name" class="form-control" name="isi_edit" id="isi_edit" required></textarea>
                      <span id="er4" hidden><b><small><font color="red">Isi Pesan Kosong</font></b></small></span>
                    </div> 
                  </div>
                
            </form>
                    {{-- Form Delete Post --}}
            <div class="deleteContent">
              <span id="iddel" class="hidden id"></span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="update" class="btn actionBtn " >
              <span id="footer_action_button" ></span>
            </button>
    
            <button type="button" class="btn btn-warning brn-sm" data-dismiss="modal">
              <span class="glyphicon glyphicon"></span>Tutup
            </button> 
          </div>
        </div>
      </div>
    </div>
</div>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <link href="{{ asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
  <script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>  
  <script>

    $(document).on('ready', function(){
      $("#pesan").select2();
    });

    $("#kirim").on('click', function(){
      if($("#pesan option:selected").val() == "NULL") {
        swal("Gagal!", "Tambahkan Pesan terlebih dahulu!", "error");
      } else {
        $("#form-data").submit();
      }
    })

    $(document).on('click','.create-modal', function() {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Pesan Notifikasi');
    $('#main').hide();
  });

  //add new 
  $("#isi").keydown(function(){
    document.getElementById('er1').hidden=true;
});
  $("#add").click(function() {
    var name = document.getElementById('isi').value;
    document.getElementById('er1').hidden=true;
    $('#add').attr("disabled", true);
    
    $.ajax({
      type: 'POST',
      url: '/notifikasi/addnotifikasi',
      data: {
        '_token': $('input[name=_token]').val(),
        'isi': $('textarea[name=isi]').val()
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
              location.reload();
              }
            );
        }
      },
    });
  });
  // function Edit POST
  $("#isi_edit").keydown(function(){
    $('#er4').hide();
});
$(document).on('click', '.edit-modal', function() {
$('#footer_action_button').text("Ubah");
$('.actionBtn').addClass('btn-success');
$('.actionBtn').removeClass('btn-danger');
$('.actionBtn').removeClass('delete');
$('.actionBtn').addClass('edit');
$('.modal-title').text('Ubah Isi Surat');
$('.deleteContent').hide();
$('.form-horizontal').show();
$('#isi_edit').show();
$('#tp').show();
$('#as').show();
$('#id').val($(this).data('id'));
$('#isi_edit').val($(this).data('isi'));
$('#show').modal('show');
$('#update').show();
$('#lb1').show();
$('#lb2').show();
$('#lb3').show();
});

$('.modal-footer').on('click', '.edit', function() {

  if (isi_edit == null || isi_edit == "") {
          document.getElementById('er4').hidden = false;
      }
  $.ajax({
      type: 'POST',
      url: '/notifikasi/editnotifikasi',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': $("#id").val(),
        'isi_edit': $("textarea[name=isi_edit]").val()
      },
      success: function(data) {
        if(data.errors){
          swal({
            title: "Gagal Mengubah Data",
            text: "Isi Surat Masih Kosong !",
            icon: "warning",
            })
        }
        else{
        $('#show').modal('hide');
            swal({
            title: "Berhasil Mengubah Data",
            text: "",
            icon: "success",
            })
            .then(function(){ 
              location.reload();
              }
            );
        }
        }
      },
    );
  });
  
  // form Delete function
$(document).on('click', '.delete-modal', function() {
 $('#isi_edit').hide();
  $('#tp').hide();
  $('#as').hide();
  $('#lb1').hide();
  $('#lb2').hide();
  $('#lb3').hide();
  $('#update').show();
  $('.actionBtn').removeClass('edit');
$('#footer_action_button').text(" Hapus");
$('.actionBtn').removeClass('btn-success');
$('.actionBtn').addClass('btn-danger');
$('.actionBtn').addClass('delete');
$('.modal-title').text('Hapus Surat');
$('.id').text($(this).data('id'));
$('.deleteContent').show();
$('.form-horizontal').hide();
$('#show').modal('show');
});
$('.modal-footer').on('click', '.delete', function(){
  $.ajax({
    type: 'POST',
    url: '/notifikasi/deletenotifikasi',
    data: {
      '_token': $('input[name=_token]').val(),
      'id': $('.id').text()
    },
    success: function(data){
        $('#show').modal('hide');
            swal({
            title: "Berhasil Menghapus Data",
            text: "",
            icon: "success",
            })
            .then(function(){ 
              location.reload();
              }
            );
        }
      },
    );
  });
  </script>
@stop