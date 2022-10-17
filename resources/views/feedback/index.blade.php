@extends('adminlte::page')
@section('title', 'Momakan - Feedback')

@section('content_header')

  <h1>Halaman Feedback</h1>
  <ol class="breadcrumb">
      <li><a href="/home"><i class="fa fa-dashboard active"></i> Dasbor </a>
      <li><a href="/feedback"><i class="fa fa-rss"></i> Feedback </a>
  </ol>

@stop

@section('content')

<div class="box box-danger">
    <div class="box-header with-border">
    <div class="box-body">
      <table class="no-margin table table-hover">
          <thead>
          <tr>
            <th>No.</th>
            <th>Rating</th>
            <th>Order ID</th>
            <th>Deskripsi</th>
            <th>Karyawan</th> 
            {{--        <th>  Aksi  </th>       --}}
          </tr>
          </thead>
        <tbody>
          @foreach($feedback as $dt)
          <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $dt->rating }}</td>
          <td>{{ $dt->order_id }}</td>
          <td>{{ $dt->description }}</td>
          <td>{{ $dt->employee->user->name }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div>
@stop