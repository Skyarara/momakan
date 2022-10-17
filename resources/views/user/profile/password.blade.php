@extends('adminlte::page')

@section('title', 'Dapur Bunda - Makanan')

@section('content_header')
     <h1>Profil {{$profile->corporate->name}}</h1>
      <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-dashboard active"></i> Home</a>
        </a></li>
      </ol>
@stop
@section('content')
{{-- Alerts --}}
@if(count($errors)>0)
@foreach ($errors->all() as $error)
<div class="alert alert-dismissible fade in col-sm-7" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"><a class="close" data-dismiss="alert" aria-label="close">&times;</a>
    {{$error}}
  </div>
    @endforeach
@endif
@if((session('fail')))
<div class="alert alert-dismissible fade in col-sm-7" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"><a class="close" data-dismiss="alert" aria-label="close">&times;</a>
  {{session('fail')}}
</div>
@endif 
@if((session('success')))
<div class="alert alert-success alert-dismissible fade in col-sm-8" style=padding-top:10px;padding-bottom:10px;"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  {{session('success')}}
</div>
@endif 
{{-- /Alerts --}}
<form method="POST" action="{{ url("/profile/password/edit/$id") }}">
    <div class="row">
        <div class="col-xs-12">
          <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Ubah Password</h3>                              
            </div>            
            {{csrf_field()}}
            <div class="box-body">                
                <div class="box-body table-responsive no-padding">
                    <input type="hidden" name="old_old" class="form-control" value="{{$profile->password}}"> 
                    <div class="form-group">
                        <label for="email">Password Lama</label>
                        <input type="password" name="old_password" placeholder="Password Lama" class="form-control"> 
                    </div>
                    <div class="form-group">
                            <label for="Corporate_phone">Password Baru</label>
                            <input type="password" name="new_password" placeholder="Password Baru" class="form-control">
                    </div>
                    <div class="form-group">
                            <label for="phone">Konfirmasi Password</label>
                            <input type="password" name="confirm_password" placeholder="Konfirmasi Password" class="form-control">
                    </div>
               </div>
                  <div class="box-footer">
                    <button type="submit" class="btn btn-warning pull-right">Ubah</button>
                  </div>
                </div>                                  
            </div>
        </div>
    </div>
  </form>

@stop

@section('css')
<style>
  </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ url('js/profile.js') }}"></script>
@stop
  