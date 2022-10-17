@extends('adminlte::page')
@section('title', 'Kontrak Faktur')

@section('content_header')

  <h1>Menampilkan Faktur</h1>
  <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
      <li><a href="/kontrak"><i class="fa fa-dashboard"></i> Kontrak </a>
      <li><i class="fa fa-credit-card"></i> Faktur </li>
  </ol>

@stop

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
        <a class="btn btn-danger create-modal pull-right">
            <i class="glyphicon glyphicon-plus"></i> Tambah
          </a>
        <h1 class="box-title"> Daftar Faktur {{$contract->corporate->name}}</h1>    
    </div>
    <div class="box-body">
      <table class="no-margin table table-hover">
          <thead>
          <tr>
            <th>No</th>
            <th>Nomor Invoice</th>
            <th>Bulan</th>
            <th>Batas Tanggal Berakhir</th>
            <th>Total Pembayaran</th> 
            <th>Aksi</th>       
          </tr>
          </thead>
        <tbody>
          @foreach($invoice as $dt)
            @if($dt['employee_id'] == null)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $dt['nomor'] }}</td>
              <td>
                  @switch($dt['date'])   
                  @case($dt['year'].'-01') Januari @break;
                  @case($dt['year'].'-02') Februari @break;
                  @case($dt['year'].'-03') Maret @break;
                  @case($dt['year'].'-04') April @break;
                  @case($dt['year'].'-05') Mei @break;
                  @case($dt['year'].'-06') Juni @break;
                  @case($dt['year'].'-07') July @break;
                  @case($dt['year'].'-08') Agustus @break;
                  @case($dt['year'].'-09') September @break;
                  @case($dt['year'].'-10') Oktober @break;
                  @case($dt['year'].'-11') November @break;
                  @case($dt['year'].'-12') Desember @break;
                  @default Alfa
                  @endswitch       
              </td>
              <td>{{$dt['batas']}}</td>
              <td>RP. {{ number_format($dt['total']) }}</td>  
              <td>
              <button id="edit-modal" class="btn btn-warning btn-sm" data-id="{{$dt['id']}}" data-bulan="{{$dt['date']}}" data-batas="{{$dt['batas']}}" data-total="{{$dt['total']}}"><i class="fa fa-pencil"></i></button>
              <button id="delete-modal" class="btn btn-danger btn-sm" onclick="delete_data({{ $dt['id'] }});"><i class="fa fa-trash"></i></button>
              <button id="#" class="btn btn-info btn-sm" onclick="detail({{ $dt['id'] }})"><i class="fa fa-eye"></i></button>
              <a href ="/kontrak/faktur/print/{{ $dt['id'] }}"><button class="btn btn-primary btn-sm"><i class="fa fa-print"></i></button></a>
              <a onclick="sendEmail({{ $dt['id'] }})"><button class="btn btn-primary btn-sm"><i class="fa fa-envelope"></i></button></a>
              </td>
            </tr>
            @endif
          @endforeach
        </tbody>
      </table>    
    </div>
</div>

<div class="box box-danger">
  <div class="box-header with-border">
      <h1 class="box-title"> Daftar Faktur Pegawai {{$contract->corporate->name}}</h1>    
  </div>
  <div class="box-body">
    <table class="no-margin table table-hover">
        <thead>
        <tr>
          <th>No</th>
          <th>Nomor Invoice</th>
          <th>Nama Pegawai</th>
          <th>Bulan</th>
          <th>Batas Tanggal Berakhir</th>
          <th>Total Pembayaran</th> 
          <th>Aksi</th>       
        </tr>
        </thead>
      <tbody>
        @foreach($invoice_pegawai as $data)
          @if($data['employee_id'] != null)
          <tr>
            <td>{{ $loop->iteration}}</td>
            <td>{{ $data['nomor'] }}</td>
            <td>{{$data['employee_id']}}</td>
            <td>
                @switch($data['date'])   
                @case($data['year'].'-01') Januari @break;
                @case($data['year'].'-02') Februari @break;
                @case($data['year'].'-03') Maret @break;
                @case($data['year'].'-04') April @break;
                @case($data['year'].'-05') Mei @break;
                @case($data['year'].'-06') Juni @break;
                @case($data['year'].'-07') July @break;
                @case($data['year'].'-08') Agustus @break;
                @case($data['year'].'-09') September @break;
                @case($data['year'].'-10') Oktober @break;
                @case($data['year'].'-11') November @break;
                @case($data['year'].'-12') Desember @break;
                @default Alfa
                @endswitch       
            </td>
            <td>{{$data['batas']}}</td>
            <td>RP. {{ number_format($data['total']) }}</td>  
            <td>
            <button id="edit-modal" class="btn btn-warning btn-sm" data-id="{{$data['id']}}" data-bulan="{{$data['date']}}" data-batas="{{$data['batas']}}" data-total="{{$data['total']}}"><i class="fa fa-pencil"></i></button>
            <button id="delete-modal" class="btn btn-danger btn-sm" onclick="delete_data({{ $data['id'] }});"><i class="fa fa-trash"></i></button>
            <button id="#" class="btn btn-info btn-sm" onclick="detail({{ $data['id'] }})"><i class="fa fa-eye"></i></button>
            <a href ="/kontrak/faktur/print_pegawai/{{ $data['id'] }}"><button class="btn btn-primary btn-sm"><i class="fa fa-print"></i></button></a>
            </td>
          </tr>
          @endif
          @endforeach
      </tbody>
    </table>    
  </div>
</div>

{{-- Modal Tambah --}}
<div id="plus" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close btn-sm" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
          <input type="hidden" id="id" name="id" value="{{$id}}">
        </div>
        <div class="modal-body">
            <div class="box-error"></div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="bulan">Bulan :</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="bulan" name="bulan"placeholder="bulan">
                  </div>
                </div>
                <div class="form-group row add">
                        <label class="control-label col-sm-2" for="tanggal_akhir">Batas Tanggal :</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" id="tanggal_akhir" name="tanggal_akhir" placeholder="batas tanggal">
                        </div>
                      </div>
            </div>
                <div class="modal-footer">
                  <button class="btn btn-success" type="submit" id="add" >
                    <span class="glyphicon glyphicon-plus"></span> Tambah 
                  </button>
                  <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup
                  </button>
                </div>
          </div>
        </div>
      </div>
{{-- /modal tambah --}}
{{-- modal edit --}}
<div id="edit" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close btn-sm" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
        <input type="hidden" id="old_id" name="id">
        <input type="hidden" id="id" name="id" value="{{$id}}">
      </div>
      <div class="modal-body">
          <div class="box-error"></div>
              <div class="form-group row add">
                <label class="control-label col-sm-2" for="bulan">Bulan :</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="bulan_edit" name="bulan_edit" placeholder="bulan" disabled>
                </div>
              </div>
              <div class="form-group row add">
                      <label class="control-label col-sm-2" for="tanggal_akhir">Batas Tanggal :</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="tanggal_akhir_edit" name="tanggal_akhir_edit" placeholder="batas tanggal">
                      </div>
                    </div>
          </div>
              <div class="modal-footer">
                <button class="btn btn-warning" type="submit" id="update" >
                  <span class="glyphicon glyphicon-pencil"></span> Edit 
                </button>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup
                </button>
              </div>
        </div>
      </div>
    </div>
{{-- /modal edit --}}

@stop

@section('js')
<script src="{{ url('js/invoice/invoice.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link href="{{ asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
<script>
$('#bulan').datetimepicker({
  format: 'YYYY-MM',
});
$('#tanggal_akhir').datetimepicker({
  format: 'YYYY-MM-DD',
});
$('#bulan_edit').datetimepicker({
  format: 'YYYY-MM',
});
$('#tanggal_akhir_edit').datetimepicker({
  format: 'YYYY-MM-DD',
});
</script>
@stop