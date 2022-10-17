@extends('adminlte::page')
@section('title', 'Momakan - Pembayaran')

@section('content_header')

  <h1>Halaman Pembayaran</h1>
  <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
      <li><i class="fa fa-money"></i> Pembayaran
  </ol>

@stop

@section('content')
<div class="box box-danger">
    <div class="box-header with-border">
        <a class="btn btn-danger create-modal pull-right">
            <i class="fa fa-plus"></i> Tambah
          </a>
        <h1 class="box-title">Daftar Pembayaran </h1>    
    </div>
    <div class="box-body">
      <table class="no-margin table table-hover">
          <thead>
          <tr>
            <th>No</th>
            <th>Nomor Pembayaran</th>
            <th>Jumlah Terbayar</th>
            <th>Tanggal</th> 
            <th>Bukti Pembayaran</th>
            <th>Terverifikasi</th>
            <th>Aksi</th>       
          </tr>
          </thead>
        <tbody>
          @foreach($payment as $dt)
          <tr>
          <td>{{$loop->iteration}}</td>
          <td>{{$dt->payment_Number}}</td>
          <td>{{ number_format($dt->paid_amount) }}</td>
          <td>{{$dt->date}}</td>  
          <td><img  src="{{ asset('storage/images/receipt_photo/' . $dt->receipt_photo) }}" class="img-thumbnail" width="75" height="75"></td>
          <td style="padding-top:1.25%;"><span class="label {{ $dt->is_verified ? 'label-success' : 'label-danger' }}">{{ $dt->is_verified ? 'Sudah' : 'Belum' }}</span></td>
          <td>
          <button id="edit-modal" class="btn btn-warning btn-sm" data-id="{{$dt->id}}" data-invoice_id="{{$dt->invoice_id}}" data-date="{{$dt->date}}" data-paid_amount="{{ $dt->paid_amount }}" data-receipt_photo="{{ asset('storage/images/receipt_photo/' . $dt->receipt_photo) }}" data-is_verified="{{ $dt->is_verified }}"><i class="fa fa-pencil"></i></button>
          <button id="delete-modal" class="btn btn-danger btn-sm" onclick="delete_data({{ $dt->id }});"><i class="fa fa-trash"></i></button>
          </td>
          </tr>
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
        </div>
        <div class="modal-body">
            <div class="box-error" id="box_error_modal_plus"></div>
              <form action="" method="post" id="form-add-payment" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="invoice_id">invoice_id :</label>
                  <div class="col-sm-10">
                    <select name="invoice_id" id="invoice_id" class="form-control">
                      @if($invoice_id->isEmpty())
                      <option value="0">Tidak Ada Invoice</option>
                      @else
                      <option value="0">Pilih Invoice id</option>
                      @endif
                        @foreach ($invoice_id as $dt)
                        <option value="{{$dt->id}}">{{$dt->id}}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="date">Tanggal :</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="date" name="date" placeholder="Tanggal">
                  </div>
                </div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="date">Total Pembayaran:</label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <div class="input-group-addon"><b>RP</b></div>
                        <input type="text" min="0" oninput="validity.valid||(value='');" class="form-control divide" id="paid_amount" name="paid_amount" placeholder="Total Pembayaran">
                      <div class="input-group-addon">.00</div>
                    </div>
                  </div>
                </div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="date">Bukti Pembayaran :</label>
                  <div class="col-sm-10">
                    <img id="image_receipt" src="{{ asset("images/receipt_blank.png") }}" alt="bukti pembayaran" width="161px" height="234px" style="margin-bottom:3px">
                    <input type="file" class="form-control" id="receipt_photo" name="receipt_photo" accept="image/*">
                  </div>
                </div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="date">Verifikasi:</label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <input type="radio" name="is_verified" id="is_verified" value="1"> Sudah
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="is_verified" id="is_verified" value="0"> Belum
                    </label>
                  </div>
                </div>
                
              </form>
                <div class="result_add">
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
      </div>
      <div class="modal-body">
            <div class="box-error"></div>
            <form action="" method="post" id="form-edit-payment" enctype="multipart/form-data">
              {{ csrf_field() }}
              <input type="hidden" id="old_id" name="old_id">
              <input type="hidden" id="old_invoice_id" name="old_invoice_id">
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="invoice_id">invoice_id :</label>
                  <div class="col-sm-10">
                    <select name="edit_invoice_id" id="edit_invoice_id" class="form-control">
                        @foreach ($invoice as $dt)
                        <option value="{{$dt->id}}">{{$dt->id}}</option>
                        @endforeach
                    </select>
                  </div>
                </div>
                <div class="form-group row add">
                    <label class="control-label col-sm-2" for="edit_date">Tanggal :</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="edit_date" name="edit_date" placeholder="Tanggal">
                    </div>
                  </div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="date">Total Pembayaran:</label>
                  <div class="col-sm-10">
                    <div class="input-group">
                      <div class="input-group-addon"><b>RP</b></div>
                        <input type="text" min="0" oninput="validity.valid||(value='');" class="form-control divide" id="edit_paid_amount" name="paid_amount" placeholder="Total Pembayaran">
                      <div class="input-group-addon">.00</div>
                    </div>
                  </div>
                </div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="date">Bukti Pembayaran :</label>
                  <div class="col-sm-10">
                    <img id="edit_image_receipt" src="{{ asset("images/receipt_blank.png") }}" alt="bukti pembayaran" width="161px" height="234px" style="margin-bottom:3px">
                    <input type="file" class="form-control" id="edit_receipt_photo" name="receipt_photo" accept="image/*">
                  </div>
                </div>
                <div class="form-group row add">
                  <label class="control-label col-sm-2" for="date">Verifikasi:</label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <input type="radio" name="is_verified" id="edit_is_verified_true" value="1"> Sudah
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="is_verified" id="edit_is_verified_false" value="0"> Belum
                    </label>
                  </div>
                </div>
              </form>
                <div class="result">
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
<script src="{{ url('js/number-divider.min.js') }}"></script>
<script src="{{ url('js/pembayaran/pembayaran.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link href="{{ asset('plugins/datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('plugins/datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> 
<script>
$('#date').datetimepicker({
  format: 'YYYY-MM-DD',
});
$('#edit_date').datetimepicker({
  format: 'YYYY-MM-DD',
});
$('#bulan').datetimepicker({
  format: 'YYYY-MM-DD',
});
</script>
@stop