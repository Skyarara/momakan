@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Edit Rencana Menu</h1>
@stop

@section('content')
    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title">Edit Rencana Menu</h3>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <form method="post" action="{{ route('ubah.menu_plan')}}" role="form" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="box-body">
          <div class="form-group">
            <label for="makanan">Tanggal</label>
            <input type="text" name="date" class="form-control" id="date" placeholder="Tanggal" required>
          </div>
          <div class="form-group">
            <label for="Kategori">Nama Rencana Menu</label>
          <input type="text" name="name" class="form-control" placeholder="Nama" required>
          </div>
          <div class="form-group">
            <label for="status">Kondisi</label>  
          <select class="form-control" name="isDefault">
              <option value="1">Default</option>  
              <option value="0">Alternatif</option>                        
          </select>   
        </div> 
        <div class="row" style="margin-top: 5px;">
                <div class="col-sm-12">
                  <label>Pilih Makanan :</label>
                  <input type="text" id="makanan" name="makanan" hidden>           
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
                        <input type="checkbox" id="mk{{ $dts['id'] }}" value="{{ $dts['id'] }}|{{ $dts['harga_asli'] }}">{{ $dts['nama_makanan'] }} (Rp. <b>{{ $dts['harga'] }}</b>)
                      </label></div>
                      @endforeach
                    </div>
                  @endforeach
                  </div>           
                </div>
              </div>  
            </div>
            <div class="box-footer">
              <input type="submit" class="btn btn-danger btn-md pull-right" value="Edit">
            </div>
          </form>
      </form>
    <!-- /.box -->


@stop

@section('css')
<style>
</style>
@stop

@section('js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ url('js/menu_plan/edit.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('js/number-divider.min.js') }}"></script>
@stop