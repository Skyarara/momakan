// Function Tambah Btn
$("#btn-tambah").on("click", function () {
    $("#modal-tambah").modal("show");
});

//funsgsi ganti status
function change(id, name) {
    var status = $('#isActive' + id).text();
    swal({
        title: "Mengubah Status",
        text: "Apakah Anda ingin mengubah status " + name + " menjadi " + (status == 'Aktif' ? 'Tidak Aktif ?' : 'Aktif ?'),
        icon: "warning",
        buttons: true,
        dangerMode: true
    }).then(willChange => {
        if (willChange) {
            $("#modal-action").fadeIn("slow");
            $.ajax({
                url: "/kontrak/detail/status_change",
                type: "POST",
                data: "_token=" + $("#_token").val() + "&" + "id=" + id,
                success: function (data) {
                    $("#modal-action").fadeOut("slow");
                    swal({
                        title: "Sukses",
                        text: "Berhasil mengubah data",
                        icon: "success"
                    });
                    $("#status" + id).replaceWith(
                        (data == 1
                            ? "<td id='status" +
                            id +
                            "' style='text-align:center; vertical-align:middle;'><span id='isActive" + id + "' class='label label-success'>Aktif</span></td>"
                            : "<td id='status" +
                            id +
                            "' style='text-align:center; vertical-align:middle;'><span id='isActive" + id + "' class='label label-danger'>Tidak Aktif</span></td>"
                        ));
                },
                error: function (data) {
                    $("#modal-action").fadeOut("slow");
                    swal("Gagal Mengubah Status", {
                        icon: "error"
                    });
                }
            });
        } else {
        }
    });
}

// Function Tambah Btn Action
$("#btn-tambah-action").on("click", function () {
    if ($("#pegawai").find("option:selected").length == 0) {
        swal("Gagal!", "Tidak ada pegawai yang di pilih", "warning");
    } else {
        $("#modal-tambah").modal("hide");
        $("#modal-action").fadeIn("slow");

        $.ajax({
            url: "/kontrak/detail/" + id + "/" + id_dk + "/pegawai",
            type: "POST",
            data: {
                _token: $("#_token").val(),
                id: id,
                id_dk: id_dk,
                id_em: $("#pegawai option:selected").val(),
                isActive: $("#isActive option:selected").val()
            },
            success: function (data) {
                if (data.errors) {
                    $("#modal-action").fadeOut("slow");
                    swal("Gagal", data.errors, "error");
                } else {
                    $("#modal-action").fadeOut("slow");
                    swal("Berhasil", data.result, "success");

                    $("#myTable").append(
                        ' \
                        <tr id="dt' +
                        data.id +
                        '"> \
                            <td id="nd' +
                        data.id +
                        '">' +
                        data.nama +
                        "</td> \
                            <td>" +
                        data.email +
                        "</td> \
                            <td>" +
                        data.nomor_hp +
                        "</td> \
                            <td id ='status" +
                        data.id +
                        "' style='text-align:center; vertical-align:middle;'>" +
                        (data.isActive == true
                            ? '<span class="label label-success">Aktif</span>'
                            : '<span class="label label-danger">Tidak Aktif</span>') +
                        '</td> \
                            <td style="text-align:center"> \
                                <button id="#" class="btn btn-warning btn-sm" onclick="change(' +
                        data.id +
                        ')"><i class="glyphicon glyphicon-pencil"></i></button> \
                            <button id="#" class="btn btn-danger btn-sm" onclick="hapus(' +
                        data.id +
                        ')"><i class="glyphicon glyphicon-trash"></i></button> \
                            </td> \
                        </tr>'
                    );

                    $(
                        "#pegawai option[value=" +
                        $("#pegawai option:selected").val() +
                        "]"
                    ).remove();
                }
            },
            error: function (data_error) {
                $("#modal-action").fadeOut("slow");
                swal("Gagal", "Gagal Mengubah Data", "error");
            }
        });
    }
});

// Id
var id = 0;
var id_dk = 0;

$(document).ready(function () {
    // Select2
    $("#pegawai").select2();

    // Set ID
    id = $("#id").val();
    id_dk = $("#id_dk").val();
});

// Function Hapus
function hapus(id_p, name) {
    if ($("#dt" + id_p, name).length != 0) {
        swal({
            title: "Menghapus Data",
            text: "Apakah anda ingin menghapus data id ini",
            icon: "warning",
            buttons: true,
            dangerMode: true
        }).then(willDelete => {
            if (willDelete) {
                $("#modal-action").fadeIn("slow");
                $.ajax({
                    url:
                        "/kontrak/detail/" +
                        id +
                        "/" +
                        id_dk +
                        "/pegawai/hapus",
                    type: "POST",
                    data: {
                        _token: $("#_token").val(),
                        id: id,
                        id_dk: id_dk,
                        id_p: id_p
                    },
                    success: function (data) {
                        if (data.errors) {
                            $("#modal-action").fadeOut("slow");
                            swal("Gagal", data.errors, "error");
                        } else {
                            $("#modal-action").fadeOut("slow");
                            swal("Berhasil", data.result, "success");

                            $("#dt" + id_p).remove();

                            $("#pegawai").html("");
                            for (i = 0; i < data.pegawai.length; i++) {
                                $("#pegawai").append(
                                    '<option value="' +
                                    data.pegawai[i].id +
                                    '">' +
                                    data.pegawai[i].name +
                                    "</option>"
                                );
                            }
                        }
                    },
                    error: function (error_data) {
                        $("#modal-action").fadeOut("slow");
                        swal("Gagal", "Gagal Menghapus Data", "error");
                    }
                });
            }
        });
    } else
        return swal(
            "Gagal",
            "Data yang ingin di hapus tidak di temukan",
            "error"
        );
}

// Laravel Dusk Testion -> Direct
function kembali_ke_kontrak_detail() {
    ///kontrak/detail/{id}/{id_dk}/pegawai
    var wlp = window.location.pathname;
    var wlpsp = wlp.split("/");
    var id = wlpsp[wlpsp.length - 3];

    window.location = "/kontrak/detail/" + id;
}