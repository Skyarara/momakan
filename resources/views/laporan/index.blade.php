@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Laporan</h1>
    <p>Menampilkan Data Kontrak</p>
@stop

@section('content')
  <div class="box box-danger">
      <div class="box-header">
          <label>Mencetak Laporan Kontrak</label><br><small>Dan mengambil semua data kontrak yang bersangkutan</small>
          <a href="/laporan/config"><button type="button" class="btn btn-danger pull-right ">
            <i class="fa fa-fw fa-pencil-square-o"></i>Konfigurasi
          </button></a>
      </div>
      <div class="box-body">
          <div class="box-body table-responsive no-padding">
              <table class="table table-hover" id="table-data">
                <tbody>
                <tr>
                  <th>No</th>
                  <th>Nama Perusahaan</th>
                  <th>Kode Kontrak</th>
                  <th>Total Pembayaran</th>                  
                  <th>Aksi</th>
                </tr> 
                <?php $no = 0; ?>
                @foreach($data as $dt)
                <?php $no++; ?>
                <tr>
                  <td>{{ $no }}</td>
                  <td id="namecp{{ $dt->id }}">{{ DB::table('corporate')->find($dt->corporate_id)->name }}</td>
                  <td>DKC/{{ $dt->corporate_id }}/{{$dt->id}}</td>
                  <td>Rp. {{ number_format(DB::table('order')->where('contract_id', $dt->id)->get()->sum('total_cost')) }}</td>
                  <td><button class="btn btn-primary btn-sm" onclick="prints({{ $dt->id }})"><i class="fa fa-print"></i></button></td>
                </tr>
                @endforeach
                </tbody>                  
              </table>
            </div> 
      </div>
  </div>
  <div class="modal fade" id="print">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Cetak <small>Mencetak Laporan</small></h4>
        </div>
        <div class="modal-body">                
          <input type="text" id="id" hidden>
          Kontrak yang akan di cetak : <b><span id="print_name"></span></b>     
          <hr>   
          <div id="filter">
            <div class="form-group">              
              <div class="col">
                Pilih Bulan :<br>        
                <input type="name" id="akhir_bulan" class="form-control" value={{ date('Y-m') }}>
              </div>
            </div>          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="btn-print">Cetak</button>
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
  $('#akhir_bulan').datetimepicker({
        format: 'YYYY-MM',
  });
  
  function prints(id)
  {
      document.getElementById('print_name').innerHTML = document.getElementById('namecp' + id).innerHTML;
      document.getElementById('id').value = id;
      $("#print").modal('show');
  }  
  $(document).ready(function(){
    @if(Session::has('message'))
    swal('Gagal!', '{{ Session::get("message") }}', 'error');
    @endif
  });
  $('#btn-print').on('click', function(){              
      var tb = document.getElementById('akhir_bulan').value;
      if(tb == "") return swal('Gagal!', 'Akhir Bulan masih kosong!', 'error');         
      return window.location = '/laporan/detail/' + document.getElementById('id').value + '?ab=' + tb;     
  });
  </script>
@stop