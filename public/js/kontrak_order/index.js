$("#btn-tambah").on('click', function(){
    window.location =  window.location.pathname + "/tambah";
});

$('#form-order').submit(function () {
    let temp = false;
    if ($(this).find('#tanggal').val() !== '') {
        temp = true;
    } else {
        swal("Peringatan!","Tanggal order belum diisi!", { icon: "warning", })
    }
    return temp;
});

$("#tanggal").datetimepicker({
    format: 'DD-MM-YYYY'
});

function hapus(tahun, bulan, tanggal) {

    swal({
        title: "Menghapus Data",
        text: "Apakah anda ingin menghapus Pesanan ini",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $("#modal-action").fadeIn("slow");
                $.ajax({
                    url: "/kontrak/order/{id}/hapus",
                    type: "POST",
                    data: '_token=' + $('input[name=_token]').val() + '&' + 'tahun=' + tahun + '&' + 'bulan=' + bulan + '&' + 'tanggal=' + tanggal,
                    success: function (data) {
                        if(data.status) {
                            $("#modal-action").fadeOut("slow");
                            document.getElementById('body-t');
                            swal(data.pesan, { icon: "success" }).then(function(){ location.reload(); });
                        } else {
                            $("#modal-action").fadeOut("slow");
                            swal(data.pesan, { icon: "error", });
                        }
                    },
                    error: function (data) {
                        $("#modal-action").fadeOut("slow");
                        swal("Gagal Menghapus data! Harap coba lagi", { icon: "error", });
                    }
                });
            } else { }
        });
}