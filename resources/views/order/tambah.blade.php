@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Tambah Daftar Pesanan</h1>
    <p>Menambahkan Data Pesanan</p>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-header">
            Menambahkan dan Menampilkan Data Pesanan<br>
            <small class="text-muted">Tambahkan sesuai kebutuhan anda</small>
        </div>
        <div class="box-body">
            <table class="table table-hover" id="table-data">
                <tr>
                    <th>Nama Pegawai</th>
                    <th>Nama Makanan</th>
                    <th>Catatan</th>
                    <th>Jumlah</th>
                </tr>
                @foreach($data as $dt)
                <tr>
                    <td>{{ $dt['nama_pegawai'] }}</td>
                    <td>{{ $dt['nama_makanan'] }}</td>
                    <td>{{ $dt['notes'] }}</td>
                    <td>{{ $dt['qty'] }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        <div class="box-footer">
            <form method="POST">
                 {{ csrf_field() }}
                 <input type="text" name="name" value="{{ $id }}" hidden>
                <input type="submit" id="tbho" class="btn btn-primary btn-sm" value="Tambah Order">
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function() {              
            $('.pekerjaan').select2();
        });
    </script>
@stop