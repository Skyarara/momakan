@extends('adminlte::page')
<style>

.bg-maroon
{
  position: relative;
  bottom: 60.6px;
  left: 330px;
}
#search
{
  position: relative;
  bottom: 15px;
}

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

.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}
</style>
@section('title', 'Momakan - Makanan')

@section('content_header')
     <h1>
       Halaman Menu Makanan
        <small>Menampilkan Menu Makanan</small>
      </h1>
      <ol class="breadcrumb">
          <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
          <li><i class="fa fa-cutlery"></i> Menu </a>
          <li><a href="/menu_makanan"><i class="fa fa-circle-o"></i> Menu Makanan </a>
      </ol>
@stop

@section('content')
{{-- <div class="callout callout-info">
  <h4>
    <i class="fa fa-info"></i>
        Note :
  </h4>
      Status Aktif menandakan bahwa makanan akan tersedia pada keesokan harinya.
</div> --}}
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title pull-left">Daftar Menu Makanan</h3>  
                <button type="button" id="tbh" class="btn btn-danger pull-right" data-toggle="modal" data-target="#modal-default"><i class="fa fa-plus"></i> Tambah</button>
                {{-- Filter Kategori --}}
                <form id="kategori" method="get" action="{{url()->current()}}">
                <select class="form-control pull-right" name="kategori" style="width:15.75%; margin-right: 5px">
                  <option value="#">Pilih Kategori</option>  
                  @foreach($menu_category as $dt)
                  <option value="{{ $dt->id }}">{{ $dt->name }}</option>  
                  @endforeach                       
                </select>
                <button class="btn btn-flat pull-right" style="height:34px"><i class="fa fa-search"></i>
                </button>
                </form>     
                {{-- /Filter Kategori --}}
                {{-- <button type="button" id="mbb" class="btn btn-danger pull-right" style="margin-right: 5px"><i class="fa fa-bell"></i> Menu Buat Besok</button> --}}
                {{-- Filter Makanan --}}
                <div class="col-xs-4">
                    <form id="search" method="get" action="{{url()->current()}}" class="sidebar-form" style="margin-top:-0.5px; margin-left:-10px;">
                      <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search Makanan...">
                            <span class="input-group-btn">
                              <button id="filter" class="btn btn-flat"><i class="fa fa-search"></i>
                              </button>
                            </span>
                      </div>
                    </form> 
                    {{-- <a id="reset" href="{{url()->current()}}" hidden><button class="btn bg-maroon btn-md">Reset</button></a> --}}
                  </div></div>                   
            <div class="box-body">                
                <div class="box-body table-responsive no-padding">
                  <table class="table table-hover" id="table-data">
                    <tbody id="body-t">
                    <tr>
                      <th>No</th>
                      <th>Gambar</th>
                      <th>Nama Makanan</th>
                      <th style="width: 25%">Deskripsi</th>
                      <th>Harga</th>
                      {{-- <th>Status</th> --}}
                      <th>Aksi</th>
                    </tr>     
                    <?php $i = ($menu_makanan->currentpage()-1)* $menu_makanan->perpage() + 1;?>
                    @foreach($menu_makanan as $dt)
                    <tr>
                      <td>{{$i++}}</td>
                      <td id="image{{ $dt->id }}"><img src="{{ url('storage/images/' . $dt->image) }}" class="img-thumbnail" width="90px" style="height: 75px"></td>
                      <td id="name{{ $dt->id }}">{{ $dt->name }}</td>
                      <td id="desc{{ $dt->id }}">{{ $dt->description }}</td>
                      <td id="price{{ $dt->id }}">Rp. {{ number_format($dt->price) }}</td>
                      {{-- <td>
                        <label class="switch">
                          <input type="checkbox" id="check" @if($dt->isActive >= 1) onclick="isActive({{ $dt->id }})" checked @else onclick="isActive({{ $dt->id }})"@endif><span class="slider"></span>
                        </label>
                      </td> --}}
                      <td>
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary" onclick="view_data({{ $dt->id }});"><i class="fa fa-eye"></i></button>
                          <button type="button" id="edit{{$dt->id}}" class="btn btn-warning" onclick="edit_data({{ $dt->id }});" data-price="{{ $dt->price }}" ><i class="fa fa-edit"></i></button>
                          <button type="button" id="delete{{$dt->id}}" class="btn btn-danger" onclick="delete_data({{ $dt->id }});"><i class="fa fa-remove"></i></button>
                        </div>
                      </td>
                  </tr>
                    @endforeach
                    </tbody>                  
                  </table>
                  <div class="box-footer">
                      <div style="margin-bottom: 7.5%">{{ $menu_makanan ->appends(request()->all())->links()}}</div>
                  </div>
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
          <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
          <div class="alert alert-danger alert-dismissible" id="alert" hidden>
            <button type="button" class="close" onclick="document.getElementById('alert').hidden = true;">×</button>
            <h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
            <span id="error_text"></span>
          </div>
          <label>Kategori Makanan :</label>
          <select class="form-control kategori-makanan" name="kategori_makanan" id="kategori_makanan" data-placeholder="Pilih Kategori Makanan" style="width: 100%">
              @foreach($menu_category as $dt)
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
          <input type="text" id="_token" value="{{ csrf_token() }}" hidden>
          <div class="alert alert-danger alert-dismissible" id="alert_edit" hidden>
            <button type="button" class="close" onclick="document.getElementById('alert_edit').hidden=true;">×</button>
            <h4><i class="icon fa fa-ban"></i> Alert!</h4>
            <span id="error_text_edit"></span>
          </div>
          <label>Kategori Makanan :</label>
          <select class="form-control kategori-makanan" name="kategori_makanan_edit" id="kategori_makanan_edit" data-placeholder="Pilih Kategori Makanan" style="width: 100%">
              @foreach($menu_category as $dt)
              <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
              @endforeach
          </select>  
          <label>Nama Makanan :</label>
          <input type="text" name="nama_makanan_edit" id="nama_makanan_edit" class="form-control" placeholder="Nama Makanan">
          <div id="er1_edit"><p><font color="red"><b>Nama Makanan harus Ada!</b></font></p></div>
          <label>Deskripsi :</label>
          <input type="text" class="form-control" name="deskripsi_edit" id="deskripsi_edit" placeholder="Deskripsi" style="resize: vertical;">
          <div id="er2_edit"><p><font color="red"><b>Deskripsi harus Ada!</b></font></p></div>
          <label>Harga :</label>
          <div class="input-group">
            <div class="input-group-addon"><b>RP</b></div>
          <input type="text" min="0" oninput="validity.valid||(value='');" class="form-control" id="harga_edit" name="harga_edit">
            <div class="input-group-addon">.00</div>
          </div>
          <div id="er3_edit"><p><font color="red"><b>Harga harus Ada!</b></font></p></div>
          <label>Gambar Lama :</label>
          <div id="gambar_lama"></div>
          <label>Gambar :</label>
          <input type="file" name="gambar_edit" id="gambar_edit" accept=".png, .jpg, .jpeg" class="form-control">
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
          <img src="{{ url('/storage/images/' . $dt->image) }}" class="img-thumbnail" width="400" height="400">
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
  @stop
  @section('js')
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="js/number-divider.min.js"></script>
  <script src="{{ url('js/menu.js') }}"></script>
  <script>
    if (window.location.search.indexOf('search') > -1) {
      $('#reset').show();
  }
  </script>
  <link rel="stylesheet" href="{{ url('css/chip.css') }}">
  @stop