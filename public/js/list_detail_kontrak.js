// Mungkin bisa Mencegah penggantinya ID saat ingin ditambah
function getIdContract()
{
    var wlp = window.location.pathname;
    var wlpsp = wlp.split('/');
    if(wlpsp[wlpsp.length - 1] != "") { return wlpsp[wlpsp.length - 1]; }
    else {
        return wlpsp[wlpsp.length - 2];
    }
}
// End


// Button Tambah
$("#btn-tambah").on('click', function(){
    window.location = '/kontrak/detail/' + getIdContract() + '/tambah';
});

// Button Ubah Action
/*$("#btn-ubah-action").on('click', function(){
    var id = getIdContract();
    var id_detail = $("#id").val();
    var nama = $("#detail_kontrak_ubah").val();
    var budget = $("#budget_ubah").val();
    if(id_detail == "" || nama == "" || budget == "") { return swal("Gagal!", "Terdeteksi bahwa ada field yang kosong", "warning"); }

    $("#modal-action").fadeIn('slow');
    $("#modal-ubah").modal('hide');

    $.ajax({
        url: '/kontrak/detail/' + id + '/ubah',
        type: 'POST',
        data: {
            '_token': $("#_token").val(),
            'id': id,
            'id_detail': id_detail,
            'nama': nama,
            'budget': budget
        },
        success: function(data){
            if(data.errors)
            {
                $("#modal-action").fadeOut('slow');                    
                swal("Gagal", data.errors, "error");
            } else {
                $("#modal-action").fadeOut('slow');                   
                swal("Berhasil", data.result, "success");

                $("#nd" + data.id).text(data.nama);
                $("#bd" + data.id).text("Rp. " + data.budget);
            }
        },
        error: function(error_data){
            $("#modal-action").fadeOut('slow');
            swal("Gagal", "Gagal Mengubah Data", "error"); 
        }
    })
});*/

// Number Divide
$("#budget").divide({
    delimiter:',',
    divideThousand:true
});
$("#budget_ubah").divide({
    delimiter:',',
    divideThousand:true
});

// Cegah Budget Diisi dengan Huruf
$("#budget").on('keypress', function(keys){    
    if(keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
        keys.preventDefault();    
    }
});
$("#budget_ubah").on('keypress', function(keys){    
    if(keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
        keys.preventDefault();    
    }
});

// Function Detail
function detail(id)
{
    if($("#nd" + id).length != 0 && $("#bd" + id).length != 0)
    {
        $("#modal-detail").modal('show');
        $("#detail_kontrak_detail").val($("#nd" + id).text());
        $("#budget_detail").val($("#bd" + id).text().replace('Rp. ', ''));

        $("#menu_makanan").html('<b><i>Mengambil...</i></b>');
        $("#daftar_pegawai").html('<b><i>Mengambil...</i></b>');
        $.ajax({
           url: '/kontrak/detail/' + getIdContract() + '/detail',
           type: 'POST',
           data: {
                '_token': $("#_token").val(),
                'id': id,
                'idc': getIdContract()
           },
           success: function(data){
               if(data.errors)
               {
                    $("#menu_makanan").html('<font color="red"><b><i>' + data.errors + '</i></b></font>');
                    $("#daftar_pegawai").html('<font color="red"><b><i>' + data.errors + '</i></b></font>');
               } else {
                    $("#menu_makanan").html('');
                    if(data.result.length == 0)
                    {
                        $("#menu_makanan").html('<b><i>Data Kosong!</i></b>');
                    } else {
                        for(i = 0; i < data.result.length; i++)
                        {
                            $("#menu_makanan").append('<li>' + data.result[i].nama + '</li>');
                        }
                    }                

                    $("#daftar_pegawai").html('');
                    if(data.pegawai.length == 0)
                    {
                        $("#daftar_pegawai").html('<b><i>Data Kosong!</i></b>');
                    } else {
                        for(i = 0; i < data.pegawai.length; i++)
                        {
                            $("#daftar_pegawai").append('<li>' + data.pegawai[i].nama + '</li>');
                        }
                    }
               }
           }
        });
    } else return swal("Gagal", "Data yang ingin ditampilkan tidak ada", "warning");
}

// Function Ubah
function ubah(id)
{
    window.location = '/kontrak/detail/' + getIdContract() + '/' + id + '/ubah';
    /*if($("#nd" + id).length != 0 && $("#bd" + id).length != 0)
    {
        $("#modal-ubah").modal('show');
        $("#id").val(id);
        $("#detail_kontrak_ubah").val($("#nd" + id).text());
        $("#budget_ubah").val($("#bd" + id).text().replace('Rp. ', ''));
    } else return swal("Gagal", "Data yang ingin diubah tidak ada", "warning");*/
}

// Function Hapus
function hapus(id)
{
    if($("#nd" + id).length != 0 && $("#bd" + id).length != 0)
    {
        swal({
            title: "Menghapus Data",
            text: "Apakah anda ingin menghapus data id ini [" + id + "]",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $("#modal-action").fadeIn('slow');
                var id_contract = getIdContract();
                $.ajax({
                    url: '/kontrak/detail/' + id + '/hapus',
                    type: 'POST',
                    data: {
                        '_token': $("#_token").val(),
                        'id': id,
                        'id_contract': id_contract,
                    },
                    success: function(data){
                        if(data.errors)
                        {
                            $("#modal-action").fadeOut('slow');                    
                            swal("Gagal", data.errors, "error");
                        } else {
                            $("#modal-action").fadeOut('slow');                   
                            swal("Berhasil", data.result, "success");
            
                            $("#dt" + id).remove();                            
                        }
                    },
                    error: function(error_data){
                        $("#modal-action").fadeOut('slow');
                        swal("Gagal", "Gagal Menghapus Data", "error"); 
                    }
                });
              }
            });
    } else return swal("Gagal", "Data yang ingin dihapus tidak ada", "warning");
}

// Function Pegawai
function pegawai(id)
{
    window.location = '/kontrak/detail/' + getIdContract() + '/' + id + '/pegawai';
}