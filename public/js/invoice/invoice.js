$(document).on('click', '.create-modal', function () {
    $('#plus').modal('show');
    $('.modal-title').text('Tambah Faktur');
});

$(document).on('click', '#edit-modal', function () {
    $('#edit').modal('show');
    $('.modal-title').text('Edit Faktur');
    $('#old_id').val($(this).data('id'));
    $('#bulan_edit').val($(this).data('bulan'));
    $('#tanggal_akhir_edit').val($(this).data('batas'));
});

function detail(id) {
    window.location = "/kontrak/faktur/detail/" + id;
}

//Tambah
$('.modal-footer').on('click', '#add', function () {
    // $('#add').attr("disabled", true);
    //Post Data Edit
    $.ajax({
        type: 'POST',
        url: '/faktur/tambah',
        data: {
            '_token': $('input[name=_token]').val(),
            'id': $("#id").val(),
            'bulan': $("#bulan").val(),
            'tanggal_akhir': $("#tanggal_akhir").val(),
        },
        success: function (data) {
            //Menampilkan Error
            if (data == 3) {
                $('#add').attr("disabled", false);
                var notice = '<div class="alert alert-danger alert-dismissible fade in" style="padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Invoice Sudah Dibuat Pada Bulan Tersebut </div>';
                $('#plus').find('.box-error').html(notice);
            }
            if (data == 1) {
                $('#add').attr("disabled", false);
                var notice = '<div class="alert alert-warning alert-dismissible fade in" style="padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Tidak ada order pada bulan tersebut </div>';
                $('#plus').find('.box-error').html(notice);
            }
            if (data.error.length > 0) {
                $('#add').attr("disabled", false);
                var error_html = '';
                for (var count = 0; count < data.error.length; count++) {
                    error_html += '<div class="alert alert-dismissible fade in" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data.error[count] + '</div>';
                }
                $('#plus').find('.box-error').html(error_html);
                //Fungsi Berhasil
            } else {
                $('#plus').modal('hide');
                swal({
                    title: "Berhasil Menambahkan Data Faktur",
                    text: "",
                    icon: "success",
                })
                    .then(function () {
                        location.reload()
                    });
            }
        },
    });

});
//edit
$('.modal-footer').on('click', '#update', function () {
    $('#update').attr("disabled", true);
    //Post Data edit
    $.ajax({
        type: 'POST',
        url: '/faktur/edit',
        data: {
            '_token': $('input[name=_token]').val(),
            'id': $("#id").val(),
            'old_id': $("#old_id").val(),
            'bulan_edit': $("#bulan_edit").val(),
            'tanggal_akhir_edit': $("#tanggal_akhir_edit").val(),
        },
        success: function (data) {
            //Menampilkan Error
            if (data == 3) {
                $('#update').attr("disabled", false);
                var notice = '<div class="alert alert-danger alert-dismissible fade in" style="padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Invoice Sudah Dibuat Pada Bulan Tersebut </div>';
                $('#edit').find('.box-error').html(notice);
            } else if (data == 1) {
                $('#update').attr("disabled", false);
                var notice = '<div class="alert alert-warning alert-dismissible fade in" style="padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Tidak ada order pada Bulan atau Tahun tersebut </div>';
                $('#edit').find('.box-error').html(notice);
            } else if (data == 4) {
                $('#update').attr("disabled", false);
                swal({
                    title: "Gagal Mengubah Data Faktur",
                    text: "Terdapat Field Kosong",
                    icon: "error",
                })
                //Fungsi Berhasil
            } else {
                $('#edit').modal('hide');
                swal({
                    title: "Berhasil Mengubah Data Faktur",
                    text: "",
                    icon: "success",
                })
                    .then(function () {
                        location.reload()
                    });
            }
        },
    });

});

//delete
function delete_data(id) {
    swal({
        title: "Menghapus Data",
        text: "Apakah anda ingin menghapus data ini",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $("#modal-action").fadeIn("slow");
                $.ajax({
                    url: "/faktur/delete",
                    type: "POST",
                    data: '_token=' + $('input[name=_token]').val() + '&' + 'id=' + id,
                    success: function (data) {
                        if (data == 2) {
                            $("#modal-action").fadeOut("slow");
                            swal({
                                title: "Faktur Gagal Dihapus",
                                text: "Terdapat pembayaran yang berkaitan dengan data ini",
                                icon: "error",
                            })
                        } else {
                            $("#modal-action").fadeOut("slow");
                            swal({
                                title: "Faktur Berhasil Dihapus",
                                text: "",
                                icon: "success",
                            })
                                .then(function () {
                                    location.reload()
                            });
                        }
                    },
                });
            }
        });
}

function sendEmail(id) {
    swal({
        title: "Mengirim Email",
        text: "Apakah anda ingin Mengirim Faktur ini ?",
        icon: "warning",
        buttons: {
            cancel: true,
            confirm: true,
          },
    })
        .then((willsend) => {
            if (willsend) {
                console.log(id);
                var urlEmail = "/kontrak/faktur/email/"+id
                swal({
                    title: "Loading....",
                    timer: 60000,
                    buttons: false,
                    closeOnClickOutside: false,
                })
                $.ajax({
                    url: urlEmail,
                    type: "GET",
                    success: function (data) {
                        if (data.serverstatuscode == 0) {
                            swal({
                                title: "Gagal",
                                text: data.serverstatusdes,
                                icon: "error",
                            })
                        }else{
                            if (data.statuscode == 1) {
                                swal({
                                    title: "Berhasil",
                                    text: data.description,
                                    icon: "success",
                                })
                            } else {
                                swal({
                                    title: "Gagal",
                                    text: data.description,
                                    icon: "error",
                                })
                            }
                        }
                        
                    },
                });
            }
        });
}
