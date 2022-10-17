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
                    url: "/makanan_paket/delete",
                    type: "POST",
                    data: '_token=' + $("#_token").val() + '&' + 'id=' + id,
                    success: function (data) {
                        if (data == 0) {
                            $("#modal-action").fadeOut("slow");
                            swal("Gagal Menghapus data! Harap coba lagi", {
                                icon: "error",
                            });
                        } else if (data == 2) {
                            $("#modal-action").fadeOut("slow");
                            swal("Data Gagal di Hapus", "Terdapat Kontrak Dengan Makanan Ini", "warning");
                        } else {
                            $("#modal-action").fadeOut("slow");
                            swal("Data Berhasil Dihapus", {
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

function isActive(id) {
    $.ajax({
        url: '/makanan_paket/change',
        type: "POST",
        data: '_token=' + $("#_token").val() + '&' + 'id=' + id,
        success: function (response) {
            if (response == true) {
            }
            else (response == false)

        },
    });
}

