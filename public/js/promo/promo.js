//add new promo
$(document).on('click', '#new', function () {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Promo Baru');
});

$("#title").keydown(function () {
    document.getElementById('er1').hidden = true;
});
$("#deskripsi").keydown(function () {
    document.getElementById('er2').hidden = true;
});
$("#gambar").click(function () {
    document.getElementById('er3').hidden = true;
});
$("#add").click(function () {
    document.getElementById('add').disabled = true;

    var title = document.getElementById('title').value;
    var deskripsi = document.getElementById('deskripsi').value;
    var gambar = document.getElementById('gambar').value;
    //Rasyid Function
    if (title == null || title == "" ||
        deskripsi == null || deskripsi == "" ||
        gambar == null || gambar == "") {
        if (title == null || title == "") {
            document.getElementById('er1').hidden = false;
        }
        if (deskripsi == null || deskripsi == "") {
            document.getElementById('er2').hidden = false;
        }
        if (gambar == null || gambar == "") {
            document.getElementById('er3').hidden = false;
        }
        document.getElementById('add').disabled = false;
    } else {
        //End
        $.ajax({
            type: 'POST',
            url: '/promo/add',
            data: new FormData($('#add_form')[0]),
            processData: false,
            contentType: false,
            success: function (data) {
                document.getElementById('add').disabled = false;
                $('#create').modal('hide');
                swal({
                    title: "Berhasil Menambahkan Data",
                    text: "",
                    icon: "success",
                })
                    .then(function () {
                        location.reload();
                    }
                    );
            },
        });
    }
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
                    url: "/promo/delete",
                    type: "POST",
                    data: '_token=' + $("#_token").val() + '&' + 'id=' + id,
                    success: function (data) {
                        if (data == 0) {
                            $("#modal-action").fadeOut("slow");
                            swal("Gagal Menghapus data! Harap coba lagi", {
                                icon: "error",
                            });
                            // } else if (data == 2) {
                            //     $("#modal-action").fadeOut("slow");
                            //     swal("Data Gagal di Hapus", "Terdapat Kontrak Dengan Makanan Ini", "warning");
                        } else {
                            $("#modal-action").fadeOut("slow");
                            swal("Data Berhasil di hapus", {
                                icon: "success",
                            })
                                .then(function () {
                                    location.reload();
                                }
                                );
                        }
                    },
                    error: function (data) {
                        $("#modal-action").fadeOut("slow");
                        swal("Gagal Menghapus data! Harap coba lagi", {
                            icon: "error",
                        });
                    }
                });
            } else { }
        });
}
function hid_er(no) {
    document.getElementById('er' + no).hidden = true;
}
function hid_er_edit(no) {
    document.getElementById('er' + no + "_edit").hidden = true;
}
function reset_er() {
    for (i = 1; i <= 4; i++) {
        document.getElementById('er' + i).hidden = true;
        if (i != 4) {
            document.getElementById('er' + i + '_edit').hidden = true;
        }
    }
}

//edit
$(document).on('click', '#edit', function () {
    document.getElementById('edit_title').removeAttribute('readonly');
    document.getElementById('edit_deskripsi').removeAttribute('readonly');
    $('#footer_action_button').text("Ubah");
    $('.actionBtn').addClass('edit');
    $('.modal-title').text('Ubah Promo');
    $('.form-horizontal').show();
    $('#id').val($(this).data('id'));
    $('#edit_title').val($(this).data('title'));
    $('#edit_deskripsi').val($(this).data('desc'));
    $('#show').modal('show');
    $('#update').show();
    $('#img').text('Gambar Lama');
    var imgsrc = $(this).data('img');
    $('#my_image').attr('src', imgsrc);
    $('#edit_gambar').show();
});

$('.modal-footer').on('click', '#update', function () {
    $.ajax({
        type: 'POST',
        url: '/promo/ubah',
        data: new FormData($('#edit_form')[0]),
        processData: false,
        contentType: false,
        success: function (data) {
            if (data == 2) {
                swal("Gagal Mengubah Data", "Terdapat Field Kosong", "error");
            }
            else {
                swal({
                    title: "Berhasil Mengubah Data",
                    text: "",
                    icon: "success",
                })
                    .then(function () {
                        location.reload();
                    }
                    );
            }
        }
    });
});

//show

$(document).on('click', '#see', function () {
    $('#edit_title').prop('readonly', 'true');
    $('#edit_deskripsi').prop('readonly', 'true');
    $('#footer_action_button').text("Ubah");
    $('.modal-title').text('Lihat Promo');
    $('#img').text('Gambar');
    $('.form-horizontal').show();
    $('#id').val($(this).data('id'));
    $('#edit_title').val($(this).data('title'));
    $('#edit_deskripsi').val($(this).data('desc'));
    $('#show').modal('show');
    $('#update').show();
    var imgsrc = $(this).data('img');
    $('#my_image').attr('src', imgsrc);
    $('#edit_gambar').hide();
    $('#update').hide();

});