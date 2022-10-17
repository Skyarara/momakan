@extends('adminlte::page')
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
@section('title', 'Dapur Bunda - Makanan')

@section('content_header')
     <h1>
        Makanan
        <small>Dapur Bunda</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i> Home</a> > <a href="/makanan"><b>Makanan</b>          
        </a></li>
      </ol>

      
@stop

@section('content')

    <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Data Makanan</h3>
                <p class="text-muted">Selamat datang di Halaman Data Makanan</p>   
                         <a href="/makanan/kategori" class="btn pull-right bg-maroon" style="border-radius:12px; margin-top:-50px;">
                    <i class="fa fa-edit"></i> Kategori
                  </a>      
                  <div class="row">
                    <div class="col-sm-1">
                        <button type="button" id="tbh" class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i> Tambah</button>
                    </div>                   
                  </div>                 
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
                      <th>Harga</th>
                      <th>Aksi</th>
                    </tr> 
                    <?php $no = 0; ?>       
                    @foreach($food as $dt)                   
                    <?php $no++; ?>
                    <tr>
                      <td>{{ $no }}</td>
                      <td id="image{{ $dt->id }}"><img src="{{ asset('storage/images/' . $dt->image) }}" class="img-thumbnail" width="75" height="75"></img></td>
                      <td id="name{{ $dt->id }}">{{ $dt->name }}</td>
                      <td id="desc{{ $dt->id }}">{{ $dt->description }}</td>
                      <td id="price{{ $dt->id }}">Rp. {{ number_format($dt->price) }}</td>
                      <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary" onclick="view_data({{ $dt->id }});"><i class="fa fa-eye"></i></button>
                          <button type="button" id="edit{{$dt->id}}" class="btn btn-warning" onclick="edit_data({{ $dt->id }});"><i class="fa fa-edit"></i></button>
                          <button type="button" id="delete{{$dt->id}}" class="btn btn-danger" onclick="delete_data({{ $dt->id }});"><i class="fa fa-remove"></i></button>
                        </div>
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
  <form enctype="multipart/form-data" id="add_form">
  {{ csrf_field() }}
  <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">      
          <h4 class="modal-title">Dapur Bunda <small>Tambah Data Makanan</small></h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger alert-dismissible" id="alert" hidden>
            <button type="button" class="close" onclick="document.getElementById('alert').hidden = true;">×</button>
            <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
            <span id="error_text"></span>
          </div>
          <label>Kategori Makanan :</label>
          <select class="form-control kategori-makanan" name="kategori_makanan" id="kategori_makanan" data-placeholder="Pilih Kategori Makanan" style="width: 100%">
              @foreach($food_category as $dt)
              <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
              @endforeach
          </select>  
          <label>Penyedia :</label>
          <select class="form-control vendor" name="vendor" id="vendor" data-placeholder="Pilih Penyedia" style="width: 100%">
              @foreach($vendor as $dt)
              <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
              @endforeach
          </select>  
          <label>Nama Makanan :</label>
          <input type="text" name="nama_makanan" id="nama_makanan" class="form-control" placeholder="Nama Makanan">
          <div id="er1"><p><font color="red"><b>Nama Makanan harus Ada!</b></font></p></div>
          <label>Deskripsi :</label>
          <textarea class="form-control" name="deskripsi" id="deskripsi" placeholder="Deskripsi" style="resize: vertical;"></textarea>
          <div id="er2"><p><font color="red"><b>Deskripsi harus Ada!</b></font></p></div>
          <label>Harga :</label>
          <div class="input-group">
            <div class="input-group-addon"><b>RP</b></div>
            <input type="text" min="0" oninput="validity.valid||(value='');" class="form-control divide" id="harga" name="harga">
            <div class="input-group-addon">.00</div>
          </div>
          <div id="er3"><p><font color="red"><b>Harga harus Ada!</b></font></p></div>
          <label>Gambar :</label>
          <input type="file" name="gambar" id="gambar" accept=".png, .jpg, .jpeg" class="form-control">              
          <div id="er4"><p><font color="red"><b>Gambar harus ada!</b></font></p></div>
          <label>Status Paket :</label>
          <select class="form-control" name="paket" id="paket" data-placeholder="Pilih Status Paket" style="width: 100%">
            <option value="0">Bukan Paket</option>
            <option value="1">Paket</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="tambah-btn">Tambah</button>
        </div>
      </div>      
    </div>    
  </div>
  </form>
  {{-- Edit/Update Form --}}
  <form enctype="multipart/form-data" id="edit_form">
  {{ csrf_field() }}
  <input type="text" id="id" name="id" value="" hidden>
  <div class="modal fade" id="modal-default-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">      
          <h4 class="modal-title">Dapur Bunda <small>Ubah Data Makanan</small></h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-danger alert-dismissible" id="alert_edit" hidden>
            <button type="button" class="close" onclick="document.getElementById('alert_edit').hidden=true;">×</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
            <span id="error_text_edit"></span>
          </div>
          <label>Kategori Makanan :</label>
          <select class="form-control kategori-makanan" name="kategori_makanan_edit" id="kategori_makanan_edit" data-placeholder="Pilih Kategori Makanan" style="width: 100%">
              @foreach($food_category as $dt)
              <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
              @endforeach
          </select>  
          <label>Penyedia :</label>
          <select class="form-control vendor" name="vendor_edit" id="vendor_edit" data-placeholder="Pilih Penyedia" style="width: 100%">
              @foreach($vendor as $dt)
              <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
              @endforeach
          </select>  
          <label>Nama Makanan :</label>
          <input type="text" name="nama_makanan_edit" id="nama_makanan_edit" class="form-control" placeholder="Nama Makanan">
          <div id="er1_edit"><p><font color="red"><b>Nama Makanan harus Ada!</b></font></p></div>
          <label>Deskripsi :</label>
          <textarea class="form-control" name="deskripsi_edit" id="deskripsi_edit" placeholder="Deskripsi" style="resize: vertical;"></textarea>
          <div id="er2_edit"><p><font color="red"><b>Deskripsi harus Ada!</b></font></p></div>
          <label>Harga :</label>
          <div class="input-group">
            <div class="input-group-addon"><b>RP</b></div>
            <input type="number" min="0" oninput="validity.valid||(value='');" class="form-control" id="harga_edit" name="harga_edit">
            <div class="input-group-addon">.00</div>
          </div>
          <div id="er3_edit"><p><font color="red"><b>Harga harus Ada!</b></font></p></div>
          <label>Gambar Lama :</label>
          <div id="gambar_lama"></div>
          <label>Gambar :</label>
          <input type="file" name="gambar_edit" id="gambar_edit" accept=".png, .jpg, .jpeg" class="form-control">              
          <label>Status Paket :</label>
          <select class="form-control" name="paket_edit" id="paket_edit" data-placeholder="Pilih Status Paket" style="width: 100%">
            <option value="0">Bukan Paket</option>
            <option value="1">Paket</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" id="close_modal_edit" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-warning" id="ubah-btn">Ubah</button>
        </div>
      </div>      
    </div>    
  </div>
  </form>
  {{-- End --}}

  {{-- Detail Modal --}}
  <div class="modal fade" id="modal-default-detail">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">      
          <h4 class="modal-title">Dapur Bunda <small>Detail Data Makanan</small></h4>
        </div>
        <div class="modal-body" id="body-detail">       
          {{-- <center><img src="http://localhost:89/images/1550286166.jpg" class="img-thumbnail" width="400" height="400"></center> --}}             
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
        </div>
      </div>      
    </div>    
  </div>
  {{-- End --}}
  {{-- Edit Div --}}
  <div id="edit_div" hidden>

  </div>
  {{-- End --}}
  <div class="modal-ajax" id="modal-action"></div>  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="js/number-divider.min.js"></script>
  <script>
  

  $("#harga").divide({
      delimiter:',',
      divideThousand:true
    });
  $("#nama_makanan").keydown(function() { hid_er(1); });
  $("#deskripsi").keydown(function() { hid_er(2); });
  // $("#harga").change(function() { hid_er(3); });
  $("#gambar").change(function() { hid_er(4); });
  $("#tambah-btn").click(function(){
    document.getElementById('alert').hidden = true;
    if($("#nama_makanan").val() != "" &&
       $("#deskripsi").val() != "" &&
       $("#harga").val() != "" &&
       $("#gambar").val() != "")
    {
      $("#modal-default").modal('hide');
      $("#modal-action").fadeIn("slow");
      $.ajax({
          url: '/makanan/tambah',
          type: 'POST',
          processData: false,
          contentType: false,
          data: new FormData($("#add_form")[0]),
          success: function(data)
          {
            var cs = data.includes("success|");
            if(cs == true)
            {
              swal("Sukses!", "Data berhasil ditambah!", "success").then(function(){ 
              location.reload();
              }
              );
              $("#modal-action").fadeOut("slow");
              $("#modal-default").modal('hide');

              document.getElementById('nama_makanan').value = "";
              document.getElementById('deskripsi').value = "";
              document.getElementById('harga').value = "";
              document.getElementById('gambar').value = "";

              var sp = data.split('|');
              var no = sp[6];
              var row = document.getElementById('table-data').rows.length;
              document.getElementById('body-t').innerHTML += "<tr><td>" + row + "</td><td id='image" + no + "'><img src='{{ asset('storage/images/') }}/" + sp[1] + "' class='img-thumbnail' width='75' height='75'></img></td><td id='name" + no + "'>" + sp[2] + "</td><td id='desc" + no + "'>" + sp[3] + "</td><td id='price" + no + "'>" + sp[4] + "</td><td>" + sp[5] + "</td><td><div class='btn-group'><button type='button' onclick='view_data(" + no + ");' class='btn btn-primary'><i class='fa fa-eye'></i></button><button type='button' onclick='edit_data(" + no + ");' class='btn btn-warning'><i class='fa fa-edit'></i></button><button type='button' onclick='delete_data(" + no + ");' class='btn btn-danger'><i class='fa fa-remove'></i></button></div></td></tr>";

            } else {
              swal("Gagal!", data, "error");
              document.getElementById('alert').hidden = false;
              document.getElementById('error_text').innerHTML = data;
              $("#modal-default").modal('show');
              $("#modal-action").fadeOut("slow");
            }
          },
          error: function(data)
          {
              swal("Gagal!", data, "error");
              document.getElementById('alert').hidden = false;
              document.getElementById('error_text').innerHTML = "Error! Harap di cek data sebelum di tambahkan";
              $("#modal-default").modal('show');
              $("#modal-action").fadeOut("slow");
          }
      });    
    } else {
      if($("#nama_makanan").val() == "") document.getElementById('er1').hidden = false;
      if($("#deskripsi").val() == "") document.getElementById('er2').hidden = false;
      if($("#harga").val() == "") document.getElementById('er3').hidden = false;
      if($("#gambar").val() == "") document.getElementById('er4').hidden = false;
    }
  });

  $(document).ready(function() {  
    reset_er();
    $('.kategori-makanan').select2();
    $('.vendor').select2();
  });

  // Place Function in here
  function view_data(id)
  {
    $("#modal-action").fadeIn("slow");
    $.ajax({
      url: '/makanan/detail',
      type: 'POST',
      data: '_token={{ csrf_token() }}&id=' + id,
      success: function(data){
        data = data.split('|');
        if(data[0] == "1")
        {
          $("#modal-default-detail").modal('show');
          $("#modal-action").fadeOut("slow");
          document.getElementById('body-detail').innerHTML = data[1];
        } else {
          swal("Gagal!", data[1], "error");
          $("#modal-action").fadeOut("slow");
        }
      },
      error: function(data){
        swal("Gagal!", 'Gagal mengambil informasi data ini! Coba lagi', "error");
        $("#modal-action").fadeOut("slow");
      }
    });
    
  }
  function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
  }
  function edit_data(id)
  {
    document.getElementById('paket_edit').style.visibility = "hidden";
    $("#kategori_makanan_edit").select2().next().hide();
    $("#vendor_edit").select2().next().hide();

    $.ajax({
      url: '/makanan/detail',
      type: 'POST',
      data: '_token={{ csrf_token() }}&id=' + id,
      success: function(data)
      {
        var Cnt = data.split('|CNT|')[1];
        document.getElementById('edit_div').innerHTML = "";
        document.getElementById('edit_div').innerHTML = data;
        sleep(10);
        var KategoriMakanan = document.getElementById('kategori_makanan_detail_' + Cnt).value;
        console.log(KategoriMakanan);
        var Vendor = document.getElementById('vendor_detail_' + Cnt).value;
        var Paket = document.getElementById('paket_detail_' + Cnt).value;       

        $("#kategori_makanan_edit").val(KategoriMakanan).change();
        $("#vendor_edit").val(Vendor).change();
        $("#paket_edit").val(Paket).change();        

        document.getElementById('paket_edit').style.visibility = "visible";
        $("#kategori_makanan_edit").select2().next().show();
        $("#vendor_edit").select2().next().show();

        
      }
    });
    document.getElementById('nama_makanan_edit').value = document.getElementById('name' + id).innerHTML;
    document.getElementById('deskripsi_edit').value = document.getElementById('desc' + id).innerHTML;
    var price = document.getElementById('price' + id).innerHTML.split('Rp. ')[1];
    price = price.toString().replace(',', '');
    document.getElementById('harga_edit').value = price;
    document.getElementById('gambar_lama').innerHTML = document.getElementById('image' + id).innerHTML;    
    document.getElementById('id').value = id;
    $("#modal-default-edit").modal('show');
  }
  $("#nama_makanan_edit").keydown(function() { hid_er_edit(1); });
  $("#deskripsi_edit").keydown(function() { hid_er_edit(2); });
  $("#harga_edit").change(function() { hid_er_edit(3); });
  $("#ubah-btn").click(function(){
    document.getElementById('alert_edit').hidden = true;
    if($("#nama_makanan_edit").val() != "" &&
       $("#deskripsi_edit").val() != "" &&
       $("#harga_edit").val() != "")
    {
      swal({
      title: "Mengubah Data",
      text: "Apakah anda ingin mengubah data id ini ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
      })
      .then((willUpdate) => {
        if (willUpdate) {
          $("#modal-default-edit").modal('hide');
          $("#modal-action").fadeIn("slow");
          $.ajax({
            url: '/makanan/ubah',
            type: 'POST',
            processData: false,
            contentType: false,
            data: new FormData($('#edit_form')[0]),
            success: function(data){
              data = data.split('|');
              if(data[0] == 1)
              {
                swal("Sukses!", "Data berhasil diubah!", "success");
                $("#modal-default-edit").modal('hide');
                $("#modal-action").fadeOut("slow");
                document.getElementById('body-t').innerHTML = data[1];
              } else {
                swal("Gagal!", data[1], "error");
                document.getElementById('alert_edit').hidden = false;
                document.getElementById('error_text_edit').innerHTML = data[1];
                $("#modal-default-edit").modal('show');
                $("#modal-action").fadeOut("slow");
              }
            },
            error: function(data){
                swal("Gagal!", data, "error");
                document.getElementById('alert_edit').hidden = false;
                document.getElementById('error_text_edit').innerHTML = "Error! Harap di cek data sebelum di ubah";
                $("#modal-default-edit").modal('show');
                $("#modal-action").fadeOut("slow");
            }
          });
        
       
      }
      });
    } else {
      if($("#nama_makanan_edit").val() == "") document.getElementById('er1_edit').hidden = false;
      if($("#deskripsi_edit").val() == "") document.getElementById('er2_edit').hidden = false;
      if($("#harga_edit").val() == "") document.getElementById('er3_edit').hidden = false;      
    }
  });      
  function delete_data(id)
  {
    swal({
    title: "Menghapus Data",
    text: "Apakah anda ingin menghapus data id ini [" + id + "]",
    icon: "warning",
    buttons: true,
    dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        $("#modal-action").fadeIn("slow");
        $.ajax({
          url: "/makanan/hapus",
          type: "POST",
          data: '_token={{ csrf_token() }}&' + 'id=' + id,
          success: function(data){
            if(data == 0){
              $("#modal-action").fadeOut("slow");
              swal("Gagal Menghapus data! Harap coba lagi", {
              icon: "error",
              });
            } else if(data == 2) {
              $("#modal-action").fadeOut("slow");
              swal("Data Gagal di Hapus","Dikarenakan pada data Kontrak terdapat salah satu makanan ini yang sedang digunakan","warning");
            } else {
              $("#modal-action").fadeOut("slow");
              document.getElementById('body-t').innerHTML = data;
              swal("Data Berhasil di hapus", {
                icon: "success",
              });
            }            
          },
          error: function(data){
            $("#modal-action").fadeOut("slow");
            swal("Gagal Menghapus data! Harap coba lagi", {
              icon: "error",
            });
          }
        });        
      } else { }
    });
  }
  function hid_er(no)
  {
      document.getElementById('er' + no).hidden = true;
  }
  function hid_er_edit(no)
  {
    document.getElementById('er' + no + "_edit").hidden = true;
  }
  function reset_er()
  { for(i = 1; i <= 4; i++) { 
      document.getElementById('er' + i).hidden = true;
      if(i != 4) { 
      document.getElementById('er' + i + '_edit').hidden = true; } }
  }
  </script>
@stop