@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Edit Makanan Paket</h1>
@stop

@section('content')
    <div class="box box-primary">
      <div class="box-header with-border">
      <h3 class="box-title">{{ $old_data->name}}</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form method="post" action="{{ url('/update/paket',array($old_data->id)) }}" role="form" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="box-body">
          <div class="form-group">
            <label for="makanan">Nama Makanan</label>
          <input type="text" name="name" class="form-control" id="food" placeholder="Nama Makanan" value="{{ $old_data->name }}" required>
          </div>
          <div class="form-group">
            <label for="Kategori">Kategori</label>
          <input type="text" disabled name="name" class="form-control" id="food" placeholder="Nama Makanan" value="Paket">
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
          <textarea class="form-control" rows="3" name="deskripsi" placeholder="Deskripsi" required>{{ $old_data->description }}</textarea>
          </div>
          <div class="form-group">
            <label for="Harga"> Harga</label>
            <input type="text" class="form-control divide" name="harga" id="harga" placeholder="Harga" value="{{ $old_data->price }}" required>
          </div>
          <div class="form-group">
            <label for="penyedia">Penyedia</label>  
          <select class="form-control" name="vendor" id="vendor" data-placeholder="Pilih Penyedia" style="width: 100%">
              @foreach($vendor as $dt)
              <option value="{{ $dt->id }}">{{ $dt->name }}</option>                       
              @endforeach
          </select>   
        </div>
        <div class="form-group">
            <label for="gambar lama">Gambar Lama</label>  
          <div class="modal-body" id="body-detail">       
            <img src="{{ asset('storage/images/' . $old_data->image) }}" class="img-thumbnail" width="200" height="200">             
          </div>
          </div>
          <div class="form-group">
            <label for="Gambar">Gambar Baru</label>
            <input type="file" name="gambar_edit" id="gambar" accept=".png, .jpg, .jpeg" id="Gambar">
            <label for="Gambar"><h6><b>Jika Tidak Ingin diganti Kosongkan Saja</b></h6></label>
          </div>   
        <label for="Kategori Makanan">Pilihan Menu</label>
        <input type="text" id="makanan" value="{{ $list_makanan }}" name="makanan" hidden>  
        <div class="row" style="margin-top: 5px;">
          <div class="col-sm-12">          
              <div class="row">
              <?php $no = 0; ?>
              @foreach($result as $dt)
                <?php $no++; ?>
                @if($no == 5)
                  </div>
                  <div class="row">
                @endif
                <div class="col-sm-3">
                  <b><h4>{{ $dt['nama_kategori'] }}</h4></b>
                  @foreach($dt['makanan'] as $dts)
                  <div class="checkbox">
                    <label>
                        <input type="checkbox" name="checked" id="mk{{ $dts['id'] }}" value="{{ $dts['id'] }}">{{ $dts['nama_makanan'] }} (Rp. <b>{{ $dts['harga'] }}</b>)
                  </label></div>
                  @endforeach
                </div>
              @endforeach
              </div>           
            </div>
          </div>  
        </div>
        <div class="box-footer">
          <input type="submit" value="Ubah" class="btn btn-warning btn-md pull-left" >
        </div>
      </form>
    <!-- /.box -->


@stop

@section('css')
<style>
hr {
  border: 0;
  clear:both;
  display:block;
  width: 96%;               
  background-color:#000;
  height: 1px;
  width: 100%;
}

</style>
@stop

@section('js')
<script src="{{ url('js/number-divider.min.js') }}"></script>
<script src="{{ url('js/menu_paket/ubah_paket.js') }}"></script>
<script>
$("#kategori").val({{ $old_data->menu_category_id }}).trigger('change');
$("#vendor").val({{ $old_data->vendor_id }}).trigger('change');
</script>
@stop