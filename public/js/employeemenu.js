$("#qty").keydown(function () { hid_er(1); });

$('.menu_select2').select2();

//func Tambah
$("#tambah-btn").click(function () {
    document.getElementById('alert').hidden = true;
    if ($("#qty").val() != "" && $("#qty").val() != 0) {
        $("#modal-default").modal('hide');
        $("#modal-action").fadeIn("slow");
        $.ajax({
            url: '/employeefood/tambah',
            type: 'POST',
            processData: false,
            contentType: false,
            data: new FormData($("#add_form")[0]),
            success: function (data) {
                var cs = data.includes("success|");
                if (cs == true) {
                    swal("Sukses!", "Data berhasil ditambah!", "success")
                        .then(function () {
                            location.reload();
                        });

                    $("#modal-action").fadeOut("slow");
                    $("#modal-default").modal('hide');

                    document.getElementById('qty').value = "";
                    document.getElementById('notes').value = "";

                    // var sp = data.split('|');
                    // var no = sp[4];
                    // var row = document.getElementById('table-data').rows.length;
                    // document.getElementById('body-t').innerHTML += "<tr><td>" + row + "</td><td id='qty" + no + "'>" + sp[1] + "</td><td id='notes" + no + "'>" + sp[2] + "</td><td>" + sp[3] + "</td><td style='text-align:center'><div class='btn-group'><button type='button' onclick='view_data(" + no + ");' class='btn btn-primary'><i class='fa fa-eye'></i></button><button type='button' onclick='edit_data(" + no + ");' class='btn btn-warning'><i class='fa fa-edit'></i></button><button type='button' onclick='delete_data(" + no + ");' class='btn btn-danger'><i class='fa fa-remove'></i></button></div></td></tr>";

                } else {
                    swal("Gagal!", data, "error");
                    document.getElementById('alert').hidden = false;
                    document.getElementById('error_text').innerHTML = data;
                    $("#modal-default").modal('show');
                    $("#modal-action").fadeOut("slow");
                }
            },
            error: function (data) {
                swal("Gagal!", data, "error");
                document.getElementById('alert').hidden = false;
                document.getElementById('error_text').innerHTML = "Error! Harap di cek data sebelum di tambahkan";
                $("#modal-default").modal('show');
                $("#modal-action").fadeOut("slow");
            }
        });
    } else {
        $('#er1').show();
        // document.getElementById('er1').hidden = false;
    }
});

//edit modal
// $(document).on('click', '.edit-modal', function () {
//     $('#modal-edit').modal('show');
//     $('.modal-title').text('Edit Menu');
//     $('#menu_edit').append('<option id="punch" value="' + $(this).data('menu_id') + '"selected >' + $(this).data('menu') + '</option>');
//     $('#extra_edit').val($(this).data('extra')).trigger('change');
//     $('#notes_edit').val($(this).data('notes'));
//     $('#id').val($(this).data('id'));
//     $('#qty_edit').val($(this).data('qty'));
//     $('body').click(function (evt) {
//         if (evt.target.id == "modal-edit")
//             $('#menu_edit').find('#punch').remove();
//     });
// });

$('.extra_select').change(function () {
    if ($(this).val() == 1) {
        $(this).closest('.modal-body').find('#qty').removeAttr('readonly');
    } else {
        $(this).closest('.modal-body').find('#qty').attr('readonly', true);
    }
});

$('.extra_select_edit').change(function () {
    if ($(this).val() == 1) {
        $(this).closest('.modal-body').find('#qty_edit').removeAttr('readonly');
    } else {
        $(this).closest('.modal-body').find('#qty_edit').attr('readonly', true);
    }
});

$(".ubah-btn").click(function () {
    let closest = $(this).closest('.modal-content');
    document.getElementById('alert_edit').hidden = true;
    if ($(closest).find("#qty_edit").val() != "" && $(closest).find("#qty_edit").val() != 0) {
        swal({
            title: "Mengubah Data",
            text: "Apakah anda ingin mengubah data ID ini?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willUpdate) => {
                if (willUpdate) {
                    $("#modal-default-edit").modal('hide');
                    $("#modal-action").fadeIn("slow");
                    $.ajax({
                        url: '/employeefood/ubah',
                        type: 'POST',
                        processData: false,
                        contentType: false,
                        data: new FormData($(closest).find('#edit_form')[0]),
                        success: function (data) {
                            data = data.split('|');
                            if (data[0] == 1) {
                                swal("Sukses!", "Data berhasil diubah!", "success")
                                    .then(function () { location.reload(); });
                            } else {
                                swal("Gagal!", data[1], "error");
                                document.getElementById('alert_edit').hidden = false;
                                document.getElementById('error_text_edit').innerHTML = data[1];
                                $("#modal-default-edit").modal('show');
                                $("#modal-action").fadeOut("slow");
                            }
                        },
                        error: function (data) {
                            swal("Gagal!", data, "error");
                            document.getElementById('alert_edit').hidden = false;
                            document.getElementById('error_text_edit').innerHTML = "Error! Harap di cek data sebelum di ubah";
                            $("#modal-default-edit").modal('show');
                            $("#modal-action").fadeOut("slow");
                        }
                    });
                }
            });
    } else {
        document.getElementById('er1_edit').hidden = false;
    }
});

// func View
function view_data(id) {
    $("#modal-action").fadeIn("slow");
    $.ajax({
        url: '/employeefood/detail',
        type: 'POST',
        data: '_token=' + $("#_token").val() + '&id=' + id,
        success: function (data) {
            data = data.split('|');
            if (data[0] == "1") {
                $("#modal-default-detail").modal('show');
                $("#modal-action").fadeOut("slow");
                document.getElementById('body-detail').innerHTML = data[1];
            } else {
                swal("Gagal!", data[1], "error");
                $("#modal-action").fadeOut("slow");
            }
        },
        error: function (data) {
            swal("Gagal!", 'Gagal mengambil informasi data ini! Coba lagi', "error");
            $("#modal-action").fadeOut("slow");
        }
    });
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function view_previousdata(id, name) {

}


function delete_data(id) {
    swal({
        title: "Menghapus Data",
        text: "Apakah anda ingin menghapus data id ini [" + id + "]",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $("#modal-action").fadeIn("slow");
                $.ajax({
                    url: "/employeefood/hapus",
                    type: "POST",
                    data: '_token=' + $("#_token").val() + '&' + 'id=' + id,
                    success: function (data) {
                        if (data == 0) {
                            $("#modal-action").fadeOut("slow");
                            swal("Gagal Menghapus data! Harap coba lagi", { icon: "error", });
                        } else {
                            $("#modal-action").fadeOut("slow");
                            document.getElementById('body-t');
                            swal("Data Berhasil di hapus", { icon: "success" }).then(function () { location.reload(); });
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


function hid_er(no) {
    document.getElementById('er' + no).hidden = true;
}

function hid_er_edit(no) {
    document.getElementById('er' + no + "_edit").hidden = true;
}

function reset_er() {
    for (i = 1; i <= 1; i++) {
        document.getElementById('er' + i).hidden = true;
        if (i != 2) {
            document.getElementById('er' + i + '_edit').hidden = true;
        }
    }
}