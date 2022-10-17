@extends('adminlte::page')

@section('title', 'Dashboard')
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
@section('content_header')
    <h1>Kontrak</h1>


@stop

@section('content')
    <p>Selamat datang di halaman Data Kontrak</p>
        
    <div class="input-group-btn  pull-right">
      <button type="button" id="status" class="pull-right btn btn-success" data-toggle="dropdown" aria-expanded="true"> Status
        <span class="fa fa-caret-down"></span></button>
      <ul class="dropdown-menu">
      <li><a id="active" href="{{ url('contract?active') }}">Aktif</a></li>
        <li><a id="inactive" href="{{url('contract?inactive') }}">Tidak Aktif</a></li>
      </ul>
    </div>
      
    <button type="button" id="plus" class="create-modal btn btn-info  pull-left" style="margin-right: 5px" data-toggle="modal">
      <span class="glyphicon glyphicon-plus"></span> Tambah</button>

      <a href="/contract" id="reset" hidden><button type="button" class="btn btn-pinterest  pull-left" data-toggle="modal">Reset</button></a>

    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
          <th>No </th>
          <th>Nama Perusahaan</th>
          <th>Kode Kontrak</th> 
          <th>Nama Makanan</td>
          <th>Status Kontrak</th>
          <th style="text-align: justify">Aksi</th>
          <th></th>
        </tr>
        </thead>
      <tbody id="myTable" >
        @foreach($contract as $data)
          <tr class="data{{$data->id}}">
          <td>{{ $loop->iteration }}</td>  
          <td>{{ $data->corporate->name }}</td>
          <td>{{ $data->contract_code }}</td>  
          <td>{{ $data->food->name }}</td>
          <td> @if( $time >= $data->date_start && $time <= $data->date_end )Aktif @else Tidak Aktif @endif </td>
          <td>
              <a href="/contract/order/{{ $data->corporate->id }}" id="Ord" class="btn btn-success btn-sm"><i class="glyphicon glyphicon-list-alt"></i><a>
              <a href="#" id="detail{{$data->id}}" class="show-modal btn btn-info btn-sm" data-id="{{$data->id}}" data-name="{{$data->corporate->name}}" data-code="{{ $data->contract_code }}" data-food="{{$data->food->name}}" 
                data-budget="Rp. {{ number_format($data->budget_max_order)}}" data-start="{{$data->date_start}}" data-end="{{$data->date_end}}"  >
              <i class="fa fa-eye"></i>
            </a>
          <a href="#" id="edit{{ $data->id }}" class="edit-modal btn btn-warning btn-sm" data-id="{{$data->id}}" data-name="{{$data->corporate->id}}" data-code="{{ $data->contract_code }}" data-food="{{ $data->food->id }}"
              data-budget="{{$data->budget_max_order}}" data-start="{{$data->date_start}}" data-end="{{$data->date_end}}"  >
                <i class="glyphicon glyphicon-pencil"></i>
              </a>
            <a href="#" id="delete{{ $data->id }}" class="delete-modal btn btn-danger btn-sm" data-id="{{$data->id}}" data-name="{{$data->corporate->name}}">
                  <i class="glyphicon glyphicon-trash"></i>
                </a>
                </td>
          </tr>
        @endforeach

   
        </tbody>

        <!-- Modal Form Create Post  -->
<div id="create" class="modal fade" role="dialog">
    <div class="modal-ajax" id="modal-action"></div>  
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
                      <label class="control-label col-sm-2" for="name">Nama Perusahaan :</label>
                      <div class="col-sm-10">
                        <select class="form-control name" name="name" id="name" data-placeholder="Nama Perusahaan" style="width:100%;">
                          @foreach($corporate as $dt)
                          <option value="{{ $dt->id }}">{{ $dt->name}}</option>                       
                          @endforeach
                      </select>  
                    </div>
                  </div>
                    
                    {{-- <div class="form-group row add">
                      <label class="control-label col-sm-2" for="Contract Code">Kode Kontrak :</label>
                      <div class="col-sm-10">
                          @foreach($contract as $dt)
                        <input type="text" class="form-control" id="contract_code" name="contract_code"
                        placeholder="Kode Kontrak" value="DKC/{{ $dt->corporate_id }}/{{ $dt->id }}" disabled>
                        @endforeach
                        <span id="er2" hidden><b><small><font color="red">Kode Kontrak Masih Kosong</font></b></small></span>
                      </div>
                    </div> --}}

                    <div class="form-group row add">
                      <label class="control-label col-sm-2" for="Order Budget">Anggaran Pemesanan :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control divide" id="order_budget" name="order_budget"
                          placeholder="Anggaran Pemesanan" required>
                          <span id="er3" hidden><b><small><font color="red">Anggaran Pemesanan Masih Kosong</font></b></small></span>
                        </div>
                      </div>
                    <div class="form-group row add">
                        <label class="control-label col-sm-2" for="food">Makanan :</label>
                        <div class="col-sm-10">
                          <select class="form-control food" name="food" id="food" data-placeholder="Makanan" style="width:100%;">
                            {{-- @foreach($food as $dt)
                            <option value="{{ $dt->id }}">{{ $dt->name}}</option>                       
                            @endforeach --}}
                        </select>  
                      </div>
                    </div>              

                <div class="form-group row add">
                        <label class="control-label col-sm-2" for="date_start">Tanggal Mulai :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="date_start" name="date_start"
                          placeholder="Tanggal Mulai" required>
                          <span id="er4" hidden><b><small><font color="red">Tanggal Mulai Masih Kosong</font></b></small></span>
                        </div>
                      </div>


                <div class="form-group row add">
                        <label class="control-label col-sm-2" for="date_end">Tanggal Berakhir :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="date_end" name="date_end"
                          placeholder="Tanggal Berakhir" required>
                          <span id="er5" hidden><b><small><font color="red">Tanggal Berakhir Masih Kosong</font></b></small></span>
                        </div>
                 
                  </form>
                </div>
                

                    <div class="modal-footer">
                      <button class="btn btn-info" type="submit" id="add" >
                        <span class="glyphicon glyphicon-plus"></span>Tambah
                      </button>
                      <button class="btn btn-secondary" id="close_modal" type="button" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remobe"></span>Tutup
                      </button>
                    </div>
              </div>
            </div></div>
          </div>     
      
            {{-- edit --}}
          <div id="show" class="modal fade" role="dialog">
              <div class="modal-ajax" id="modal-action2"></div> 
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
                              <label class="control-label col-sm-2" id="lb1" for="name">Nama Perusahan :</label>
                              <div class="col-sm-10">
                                <div class="editname">
                                  <select class="form-control nameedit" name="nameedit" id="nmr" disabled data-placeholder="Nama Perusahaan" style="width:100%;">
                                      @foreach($corporate as $dt)
                                      <option id="valcor" value="{{ $dt->id }}">{{ $dt->name}}</option>                       
                                      @endforeach
                                  </select>  
                                  </div>
                                  <textarea type="name" class="form-control" id="nma" disabled ></textarea>
                                <span id="desc" hidden><b>Apakah anda yakin ingin menghapus data ini ?</b></small></span>
                              </div>
                            </div>
                          
                            <div class="form-group row add">
                                <label class="control-label col-sm-2" id="lb2" for="name">Kode Kontrak : </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="telp" id="tp" required disabled></input>
                                    <textarea type="name" class="form-control" id="tpa" disabled></textarea>
                                </div>
                              </div>
              
                              
                              <div class="form-group row add">
                                <label class="control-label col-sm-2" id="lb5" for="name">Anggaran Pemesanan :</label>
                                <div class="col-sm-10">
                                  <div class="input-group ">
                                    <div class="input-group-addon"><b>RP</b></div>
                                    <input type="text" class="form-control divide" name="hargaedit" id="hargaedit" required>
                                    <textarea type="name" class="form-control" id="asa3" disabled ></textarea>
                                  </div>
                                </div>
                              </div>

                              <div class="form-group row add">
                                  <label class="control-label col-sm-2" id="lb3" for="name">Makanan :</label>
                                    <div class="col-sm-10">
                                     <div class="editfood">
                                      <select class="form-control foodedit" name="foodedit" dusk="food_edit" id="fdo" data-placeholder="Nama Makanan" style="width:100%;">
                                        @foreach($food as $dt)
                                          <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
                                         @endforeach
                                      </select>  
                                     </div>
                                    <textarea type="name" class="form-control" id="showfood" disabled ></textarea>
                                  </div>
                                </div>
                    
                                    <div class="form-group row add">
                                        <label class="control-label col-sm-2" id="lb6" for="name">Tanggal Mulai :</label>
                                        <div class="col-sm-10">
                                            <input type="name" class="form-control" name="address" id="ds2" required></input>
                                            <textarea type="name" class="form-control" id="asa4" disabled ></textarea>
      
                                        </div>
                                      </div>

                                      <div class="form-group row add">
                                          <label class="control-label col-sm-2" id="lb7" for="name">Tanggal Berakhir :</label>
                                          <div class="col-sm-10">
                                              <input type="name" class="form-control" name="address" id="de" required></input>
                                              <textarea type="name" class="form-control" id="asa5" disabled ></textarea>
        
                                          </div>
                                        </div>
                      </form>
                           <!-- Form Delete -->
                      <div class="deleteContent">
                        <span id="iddel" class="hidden id"></span>
                      </div>
                      <b type="name" id="descname" style="font-size:20px;"></b>
                    </div>
                    <div class="modal-footer">
              
                      <button type="button" id="update" class="btn actionBtn " data-dismiss="modal" >
                        <span id="footer_action_button" ></span>
                      </button>
              
                      <button id="close_modal2" dusk="close_modal2" type="button" class="btn btn-warning" data-dismiss="modal">
                        <span class="glyphicon glyphicon"></span>Tutup
                      </button>
              
                    </div>
                  </div>
                </div>
              </table>
              {{$contract->links()}}
@stop


@section('css')
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.css"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="js/number-divider.min.js"></script>
<script>

$('.name').select2();
$('.food').select2();
$('.nameedit').select2();
$('.foodedit').select2();
      $('#date_start').datetimepicker({
        format: 'YYYY-MM-DD',
      });
      $('#date_end').datetimepicker({
        format: 'YYYY-MM-DD',
      });
      $('#ds2').datetimepicker({
        format: 'YYYY-MM-DD',
      });
      $('#de').datetimepicker({
        format: 'YYYY-MM-DD',
      });
    $("#order_budget").divide({
      delimiter:',',
      divideThousand:true
    });
    $("#hargaedit").divide({
      delimiter:',',
      divideThousand:true
    });


  //button reset 
  
  if (window.location.search.indexOf('active') > -1) {
    $('#reset').show();
}
if (window.location.search.indexOf('inactive') > -1) {
    $('#reset').show();
}
else {}

  //add new 
$(document).on('click','.create-modal', function() {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Tambah Kontrak');
    $('#fid').hide();
    $('#main').hide();
  });


//   $("#contract_code").keydown(function(){
//     document.getElementById('er2').hidden=true;
// });
$("#order_budget").keydown(function(){
    document.getElementById('er3').hidden=true;
});
$("#date_start").click(function(){
    document.getElementById('er4').hidden=true;
});
$("#date_end").click(function(){
    document.getElementById('er5').hidden=true;
});
  $("#add").click(function() {
    var name = document.getElementById('name').value;
    // var contract_code = document.getElementById('contract_code').value;
    var order_budget = document.getElementById('order_budget').value;
    var food = document.getElementById('food').value;
    var date_start = document.getElementById('date_start').value;
    var date_end = document.getElementById('date_end').value;
    document.getElementById('add').disabled = true;

    $.ajax({

      type: 'POST',
      url: '/contract/addContract',
      data: {
        '_token': $('input[name=_token]').val(),
        'name': name,
        // 'contract_code': $('input[name=contract_code]').val(),
        'order_budget': $('input[name=order_budget]').val(),
        'food': food,
        'date_start': $('input[name=date_start]').val(),
        'date_end': $('input[name=date_end]').val()
      },
      success: function(data){
        if(data == 0) { 
          $('#create').modal('hide'); 
          return swal('Gagal', 'Gagal membuat kontrak! Ada kontrak yang masih aktif', 'error'); 
          document.getElementById('add').disabled = false;
        }
        if (data.errors){        
          if(order_budget == null || order_budget == ""){
            document.getElementById('er3').hidden=false;
          }
          if(date_start == null || date_start == ""){
            document.getElementById('er4').hidden=false;
          }
          if(date_end == null || date_end == ""){
            document.getElementById('er5').hidden=false;
          }
          document.getElementById('add').disabled = false;
        } else {
    
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
$(document).on('click', '.edit-modal', function() {
  var foodid = $(this).data('food');  
  $('#fdo').attr("disabled", true);
  $('.editfood').show();
  $('.editname').show();
  $('.input-group-addon').show();
$('#footer_action_button').text("Ubah");
$('.actionBtn').addClass('btn-success');
$('.actionBtn').removeClass('delete');
$('.actionBtn').removeClass('btn-danger');
$('.actionBtn').addClass('edit');
$('.modal-title').text('Ubah Kontrak');
$('.deleteContent').hide();
$('.form-horizontal').show();
$('#descname').hide();
$('#nma').hide();
$('#tpa').hide();
$('#showfood').hide();
$('#asa2').hide();
$('#asa3').hide();
$('#asa4').hide();
$('#asa5').hide();
$('#nm').show();
$('#tp').show();
$('#main').hide();
$('#as').show();
$('.nameedit').val($(this).data('name')).trigger('change');
$('#id').val($(this).data('id'));
$('#tp').val($(this).data('code'));
$('#hargaedit').val($(this).data('budget'));
$('#ds2').val($(this).data('start'));
$('#de').val($(this).data('end'));
$('#show').modal('show');
$('#update').show();
$('#desc').hide();
$('#as2').show();
$('#hargaedit').show();
$('#ds2').show();
$('#de').show();
$('#lb1').show();
$('#lb2').show();
$('#lb3').show();
$('#lb4').show();
$('#lb5').show();
$('#lb6').show();
$('#lb7').show();
document.getElementById('desc').hidden=true;
document.getElementById('descname').hidden=true;

$.ajax({
      url: '/contract/getFood',
      type: 'POST',
      data: '_token={{ csrf_token() }}&budget=' + $("#hargaedit").val(),
      success: function(data){
        document.getElementById('fdo').innerHTML = data;
        document.getElementById('fdo').disabled = false;   
        
        // Select Makanan
        $("#fdo").val(foodid).change();    
    }});

});

$('.modal-footer').on('click', '.edit', function() {
  $.ajax({
      type: 'POST',
      url: '/contract/editContract',
      data: {
        '_token': $('input[name=_token]').val(),
        'id': $("#id").val(),
        'name': $("#nmr").val(),
        // 'contract_code': $('#tp').val(),
        'order_budget': $('#hargaedit').val(),
        'food': $("#fdo").val(),
        'date_start': $('#ds2').val(),
        'date_end': $('#de').val()
      },
      success: function(data) {
        if (data.errors){
          swal("Gagal Mengubah Data", "Terdapat Field Kosong" , "warning");
        } else if (data == 0)
        {
          swal("Gagal Mengubah Data", "Kontrak ini tidak valid/ada", "warning");
        } else if (data == 1) {
          swal("Gagal Mengubah Data", "Makanan Pegawai atau Pegawai tidak ada/kosong", "warning");
        } else {
            swal({
            title: "Berhasil Mengubah Data",            
            icon: "success",
            })
            .then(function(){ 
              location.reload();
            });
        }
      },
    });

  });

  // form Delete function
  $(document).on('click', '.delete-modal', function() {
    $('.editfood').hide();
    $('.editname').hide();
    $('.input-group-addon').hide();
  $('#nm').hide();
  $('#tp').hide();
  $('#as').hide();
  $('#as2').hide();
  $('#hargaedit').hide();
  $('#ds2').hide();
  $('#lb1').hide();
  $('#lb2').hide();
  $('#lb3').hide();
  $('#lb4').hide();
  $('#lb5').hide();
  $('#lb6').hide();
  $('#lb7').hide();
  $('#update').show();
  $('#nma').hide();
  $('#tpa').hide();
  $('#asa').hide();
  $('#showfood').hide();
  $('#asa2').hide();
  $('#asa3').hide();
  $('#asa4').hide();
  $('#asa5').hide();
  $('#da').hide();
  $('#de').hide();
  $('#descname').show();
  $('#desc').show();

$('#footer_action_button').text(" Hapus");
$('.actionBtn').removeClass('btn-success');
$('.actionBtn').removeClass('edit');
$('.actionBtn').addClass('btn-danger');
$('.actionBtn').addClass('delete');
$('.modal-title').text('Delete Contract');
$('.id').text($(this).data('id'));
$('.deleteContent').show();
$('.form-horizontal').hide();
$('#descname').text($(this).data('name'));
$('#show').modal('show');
});
$('.modal-footer').on('click', '.delete', function(){
  $.ajax({
    type: 'POST',
    url: '/contract/deleteContract',
    data: {
      '_token': $('input[name=_token]').val(),
      'id': $('.id').text()
    },
    success: function(data){
      if(data == 0){
        return swal("Gagal Menghapus Data", "Data tidak bisa dihapus karena status masih aktif", "error");
      } else {
        swal("Berhasil Menghapus Data", "", "success");
        $('.data' + $('.id').text()).remove();
      }
       
    },
    error: function(data){
      swal("Gagal Menghapus Data", "Data tidak bisa dihapus karena status masih aktif", "error");
       
    }
  });
});

  // Show function
  $(document).on('click', '.show-modal', function() {
    $('.input-group-addon').hide();
    $('.editfood').hide();
    $('.editname').hide();
  $('#show').modal('show');
  $('#nma').text($(this).data('name'));
  $('#tpa').text($(this).data('code'));
  $('#showfood').text($(this).data('food'));
  $('#asa3').text($(this).data('budget'));
  $('#asa4').text($(this).data('start'));
  $('#asa5').text($(this).data('end'));
  $('#asa2').show();
  $('#asa3').show();
  $('#asa4').show();
  $('#asa5').show();
  $('#showfood').show();
  $('#nm').hide();
  $('#tp').hide();
  $('#as').hide();
  $('#as2').hide();
  $('#hargaedit').hide();
  $('#ds2').hide();
  $('#de').hide();
  $('.modal-title').text($(this).data('name'));
  $('#nma').show();
  $('#tpa').show();
  $('#asa').show();
  $('#lb1').show();
  $('#lb2').show();
  $('#lb3').show();
  $('#lb4').show();
  $('#lb5').show();
  $('#lb6').show();
  $('#lb7').show();
  $('#descname').hide();
  $('#update').hide();
  $('#desc').hide();
  });

  $("#order_budget").on('change', function()
  {
    //$("#modal-action").fadeIn("slow");
    document.getElementById('food').disabled = true;
    $.ajax({
      url: '/contract/getFood',
      type: 'POST',
      data: '_token={{ csrf_token() }}&budget=' + $(this).val(),
      success: function(data){
        document.getElementById('food').innerHTML = data;
        document.getElementById('food').disabled = false;
      }
    });
  });
  var now = 0;
  var req = $.ajax({});
  $("#order_budget").on('keydown', function(event) {  
    $('#food').attr("disabled", false);      
    if ( event.keyCode == 46 || event.keyCode == 8 ) {            
    } else {            
        if (event.keyCode < 48 || event.keyCode > 57 ) {
            event.preventDefault(); 
        }   
    }
    if(now != $("#order_budget").val() && $("#order_budget").val() != 0)
    {
      
      setTimeout(function() { 
        req.abort();
        document.getElementById('food').disabled = true;
        req = $.ajax({
        url: '/contract/getFood',
        type: 'POST',
        data: '_token={{ csrf_token() }}&budget=' + $("#order_budget").val(),
        success: function(data){
          document.getElementById('food').innerHTML = data;
          document.getElementById('food').disabled = false;        
          }
        });
      }, 200);
      now = $("#order_budget").val();
    }
  });  
  // hargaedit
  $("#hargaedit").on('keydown', function(event) {  

    if ( event.keyCode == 46 || event.keyCode == 8 ) {            
    }
    else {            
        if (event.keyCode < 48 || event.keyCode > 57 ) {
            event.preventDefault(); 
        }   
    }
    if(now != $("#hargaedit").val() && $("#hargaedit").val() != 0)
    {
      
      setTimeout(function() { 
        req.abort();
        document.getElementById('fdo').disabled = true;
        req = $.ajax({
        url: '/contract/getFood',
        type: 'POST',
        data: '_token={{ csrf_token() }}&budget=' + $("#hargaedit").val(),
        success: function(data){
          document.getElementById('fdo').innerHTML = data;
          document.getElementById('fdo').disabled = false;        
          }
        });
      }, 200);
      now = $("#hargaedit").val();
    }
  });

  </script>
@stop