@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Detail Makanan Paket</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $old_data->name}}</h3>
        <div class="form-group">
          <div class="modal-body" id="body-detail">       
            <img src="{{ asset('storage/images/' . $old_data->image) }}" class="img-thumbnail" width="400" height="400">             
          </div>

      </div>
      <!-- /.box-header -->
      <!-- form start -->
          <div class="form-group">
            <label for="makanan">Nama Makanan</label>
          <input type="text" name="name" class="form-control" id="food" placeholder="Nama Makanan" value="{{ $old_data->name }}" readonly>
          </div>
          <div class="form-group">
            <label for="Kategori">Kategori</label>
          <input type="text" readonly name="name" class="form-control" id="food" placeholder="Nama Makanan" value="Paket">
          </div>
          <div class="form-group">
            <label>Deskripsi</label>
          <textarea class="form-control" readonly rows="3" name="deskripsi" placeholder="Deskripsi">{{ $old_data->description }}</textarea>
          </div>
          <div class="form-group">
            <label for="Harga"> Harga</label>
            <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga" value="{{ number_format($old_data->price) }}" readonly>
          </div>
          <div class="form-group">
            <label for="penyedia">Penyedia</label>  
                <input type="text" class="form-control" name="penyedia" id="penyedia" placeholder="penyedia" value="{{ $old_data->vendor->name  }}" readonly>
        </div>
        <div class="form-group">
            <label for="Paket Makanan">Paket Makanan</label><br>
            <ul class="rw">
            @foreach ($paket as $dt)
            <li><label name="Paket" id="Paket">{{ $dt->paket->name }}</label></li>
            @endforeach
            </ul>
          </div>
          <hr>
        </div>
          <a href="{{route('home.paket')}}"><button class="btn btn-primary">Back</button></a>
      </div>
    </div>
    </div>
     
    </div>
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

.rw {
  display: flex;
  flex-direction: column;
  flex-wrap: wrap;
  height: 10em;
}

</style>
@stop

@section('js')
@stop