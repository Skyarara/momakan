<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Momakan</title>

    <style>
        body{
            margin-top: 40px;
        }
        .hr-red{
            background-color: red;
            height: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <hr class="hr-red">
        <div class="row">
            <div class="col-md-6">
                <p class="pull-left" style="margin-top: -10px"> From. <br>
                    Momakan <br>
                    Jl. Ki Hajar Dewantara No 1 <br>
                    Samarinda, Kalimantan Timur <br>
                    Phone : 082286959955 <br>
                    Email : momakanpraktisgembira@gmail.com
                    </p>        
            </div>
            <div class="col-md-4">
                <p class="pull-left" style="margin-left: 7.5px; margin-top: -10px"> To. <br>
                    {{ $contract->corporate->name }} <br>
                    {{ $contract->corporate->address }} <br>
                    Phone : {{ $contract->corporate->telp }} <br>
                    Email : {{ $contract->corporate->user->email }}
                    </p>
            </div>
            <div class="col-md-2">
                    <p class="pull-left" style="margin-left: 0px; margin-top: -10px"> Invoice # 00{{$invoice->id}} <br>
                        {{$contract->contract_code}} <br>
                        Month : {{$invoice->month->format('F')}} 
                        </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                    <div class="box box-danger" style="margin-top:20px">
                            <div class="box-body">
                                <table class="no-margin table table-hover" id="table">
                                    <thead>
                                    <tr>
                                    <th>Hari/Tanggal</th>
                                    <th>Pesanan</th>
                                    <th>Kuantitas</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th> 
                                    </tr>
                                    </thead>
                                <tbody>
                                    @foreach($cd_order as $dt)
                                    @if($dt['total'] != 0)
                                    <tr>
                                    <td>{{$dt['date']}}</td> 
                                    <td>Makan Siang {{$dt['name']}}</td>
                                    <td>{{$dt['qty']}}</td>
                                    <td>{{$dt['budget']}}</td>
                                    <td>{{$dt['total']}}</td>
                                    @endif   
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><b>Total</b></td>
                                    <td><b>{{ $total }}</b></td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <p class="" style="margin-top: -10px">
                                Cara Pembayaran : <br>
                                Transfer ke rek. mandiri <br>
                                Norek : 
                            </p> 
                            </div>
                        </div>
                    </div>
            </div>
            
        
    </div>
</body>
</html>