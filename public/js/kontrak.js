// Button Tambah
$("#btn-tambah").on('click', function () {
    $("#modal-tambah").modal('show');
});

// DateTimePicker
$("#tanggal_mulai").datetimepicker({
    format: 'YYYY-MM-DD'
});
$("#tanggal_berakhir").datetimepicker({
    format: 'YYYY-MM-DD'
});
$("#tanggal_mulai_ubah").datetimepicker({
    format: 'YYYY-MM-DD'
});
$("#tanggal_berakhir_ubah").datetimepicker({
    format: 'YYYY-MM-DD'
});
$("#tangga_mulai").keydown(function (event) {
    return false;
});
$("#tangga_mulai_ubah").keydown(function (event) {
    return false;
});
$("#tanggal_berakhir").keydown(function (event) {
    return false;
});
$("#tanggal_berakhir_ubah").keydown(function (event) {
    return false;
});


// Select2
$(document).ready(function () {
    $('#perusahaan').select2();
    $('#perusahaan_ubah').select2();

    // Laravel Dusk Function
    id_latest_dusk = $("#id_terbaru").val();
});

// Function Tambah Action Ajax
function tambah_action(restore) {
    $.ajax({
        url: 'kontrak/tambah',
        type: 'POST',
        data: {
            '_token': $("#_token").val(),
            'perusahaan_id': $("#perusahaan").val(),
            // 'tanggal_mulai': $("#tanggal_mulai").val(),
            // 'tanggal_berakhir': $("#tanggal_berakhir").val(),
            'restore': restore
        },
        success: function (data) {
            if (data.errors) {
                $("#modal-action").fadeOut('slow');
                swal("Gagal", data.errors, "error");
            } else {
                $("#modal-action").fadeOut('slow');
                swal("Berhasil", data.result, "success");

                $("#myTable").append(' \
                     <tr id="dt' + data.id + '"> \
                         <td id="cc' + data.id + '"><b>' + data.kode_kontrak + '</b></td> \
                         <td id="cp' + data.id + '">' + data.nama_perusahaan + '</td> \
                         <td style="padding-top:1.25%; text-align:center" id="st' + data.id + '"><span class="label label-success">Aktif</span></td> \
                         <td style="text-align:center"> \
                             <button id="#" class="btn btn-info btn-sm" onclick="invoice(' + data.id + ')"><i class="fa fa-credit-card"></i></button> \
                             <button id="#" class="btn btn-primary btn-sm" onclick="order(' + data.id + ')"><i class="fa fa-cart-plus"></i></button> \
                             <button id="#" class="btn btn-success btn-sm" onclick="detail(' + data.id + ')"><i class="fa fa-eye"></i></button> \
                             <button id="#" class="btn btn-danger btn-sm" onclick="batalkan(' + data.id + ')"><i class="fa fa-hand-stop-o"></i></button> \
                             <button id="#" class="btn btn-danger btn-sm" onclick="hapus(' + data.id + ')"><i class="glyphicon glyphicon-trash"></i></button> \
                         </td> \
                     </tr>'
                );

                // if (data.tgs >= data.tgm && data.tgs <= data.tgb) {
                $("#st" + data.id).html('<span class="label label-success">Aktif</span>');
                // } else $("#st" + data.id).html('<span class="label label-danger">Nonaktif</span>');

                // Laravel Dusk Testing
                id_latest_dusk = data.id;
                // End
            }
        },
        error: function (error_data) {
            $("#modal-action").fadeOut('slow');
            swal("Gagal", "Gagal Menambahkan Data", "error");
        }
    });
}

// Button Tambah Action
$("#btn-tambah-action").on('click', function () {
    var tanggal_mulai = "NOT_USE_ANYMORE"; //$("#tanggal_mulai").val();
    var tanggal_berakhir = "NOT_USE_ANYMORE"; //$("#tanggal_berakhir").val();
    if (tanggal_mulai != "" && tanggal_berakhir != "") {
        // if (tanggal_berakhir < tanggal_mulai) return swal('Gagal', 'Tanggal Berakhir harus melebihi Tanggal Mulai', 'error');

        $("#modal-action").fadeIn('slow');
        $("#modal-tambah").modal('hide');

        $.ajax({
            url: 'kontrak/tambah/terbaru',
            type: 'POST',
            data: {
                '_token': $("#_token").val(),
                'perusahaan_id': $("#perusahaan").val(),
            },
            success: function (data) {
                if (data.errors) {
                    $("#modal-action").fadeOut('slow');
                    swal("Gagal", data.errors, "error");
                } else {
                    if (data.result == true) {
                        swal({
                            title: "Menyalin Data Kontrak Sebelumnya",
                            text: "Apakah anda ingin Menyalin data kontrak sebelumnya untuk kontrak yang baru?",
                            buttons: ['Tidak', 'Iya'],
                            dangerMode: true,
                        })
                            .then((willDelete) => {
                                if (willDelete) {
                                    tambah_action(1); // Memindahkan Data Kontrak Sebelumnya untuk Kontrak Yang Baru
                                } else {
                                    tambah_action(0); // Tidak usah memindahkan
                                }
                            });
                    } else { tambah_action(0); }
                }
            },
            error: function (error_data) {
                $("#modal-action").fadeOut('slow');
                swal("Gagal", "Gagal Menambahkan Data", "error");
            }
        })
    } else return swal('Gagal', 'Terdapat field yang kosong! Harus diisi semua', 'warning');
});

// Button Ubah Action
// $("#btn-ubah-action").on('click', function () {
//     var tanggal_mulai = $("#tanggal_mulai_ubah").val();
//     var tanggal_berakhir = $("#tanggal_berakhir_ubah").val();
//     if (tanggal_mulai != "" && tanggal_berakhir != "") {
//         if (tanggal_berakhir < tanggal_mulai) return swal('Gagal', 'Tanggal Berakhir harus melebihi Tanggal Mulai', 'error');

//         var id = $("#id").val();

//         $("#modal-action").fadeIn('slow');
//         $("#modal-ubah").modal('hide');

//         $.ajax({
//             url: 'kontrak/ubah',
//             type: 'POST',
//             data: {
//                 '_token': $("#_token").val(),
//                 'id': id,
//                 'perusahaan_id': $("#perusahaan_ubah").val(),
//                 'tanggal_mulai': $("#tanggal_mulai_ubah").val(),
//                 'tanggal_berakhir': $("#tanggal_berakhir_ubah").val()
//             },
//             success: function (data) {
//                 if (data.errors) {
//                     $("#modal-action").fadeOut('slow');
//                     swal("Gagal", data.errors, "error");
//                 } else {
//                     $("#modal-action").fadeOut('slow');
//                     swal("Berhasil", data.result, "success");

//                     $("#cc" + id).text(data.kode_kontrak);
//                     $("#cp" + id).text(data.nama_perusahaan);
//                     if (data.tgs >= data.tgm && data.tgs <= data.tgb) {
//                         $("#st" + id).html('<span class="label label-success">Aktif</span>');
//                     } else $("#st" + id).html('<span class="label label-danger">Nonaktif</span>');
//                 }
//             },
//             error: function (error_data) {
//                 $("#modal-action").fadeOut('slow');
//                 swal("Gagal", "Gagal Mengubah Data", "error");
//             }
//         });
//     } else return swal('Gagal', 'Terdapat field yang kosong! Harus diisi semua', 'warning');
// });

// List Detail Button Action
var id_detail = 0;
$("#btn-list-detail-kontrak").on('click', function () {
    window.location = "kontrak/detail/" + id_detail;
});

// Function Detail
function detail(id) {
    if ($("#cc" + id).length != 0 && $("#cp" + id).length != 0) {
        window.location = "/kontrak/detail/" + id;
    } else return swal("Gagal", "tidak bisa buka (?)", "warning");
}

// Function ubah
function ubah(id) {
    if ($("#cc" + id).length != 0 && $("#cp" + id).length != 0) {
        var status = $("#st" + id).text();
        if (status.includes('Aktif')) {
            $("#modal-ubah").modal('show');
            $("#tanggal_mulai_ubah").val("Sedang Mengambil...");
            $("#tanggal_berakhir_ubah").val("Sedang Mengambil...");

            document.getElementById("tanggal_mulai_ubah").disabled = true;
            document.getElementById("tanggal_berakhir_ubah").disabled = true;
            document.getElementById("btn-ubah-action").disabled = true;

            $.ajax({
                url: 'kontrak/detail',
                type: 'POST',
                data: {
                    '_token': $("#_token").val(),
                    'id': id
                },
                success: function (data) {
                    if (data.errors) {
                        swal("Gagal", data.errors, 'error');
                        $("#tanggal_mulai_ubah").val("Gagal");
                        $("#tanggal_berakhir_ubah").val("Gagal");

                        document.getElementById("tanggal_mulai_ubah").disabled = false;
                        document.getElementById("tanggal_berakhir_ubah").disabled = false;
                        document.getElementById("btn-ubah-action").disabled = false;
                        return;
                    }
                    $("#tanggal_mulai_ubah").val(data.tgm);
                    $("#tanggal_berakhir_ubah").val(data.tgb);
                    $("#perusahaan_ubah").val(data.np).change();

                    document.getElementById("tanggal_mulai_ubah").disabled = false;
                    document.getElementById("tanggal_berakhir_ubah").disabled = false;
                    document.getElementById("btn-ubah-action").disabled = false;
                }
            });
            $("#id").val(id);
        } else swal("Gagal", "Data yang tidak aktif tidak dapat diubah", "warning");
    } else return swal("Gagal", "Data yang ingin diubah tidak ada", "warning");
}

// Hapus Function
function hapus(id) {
    if ($("#cc" + id).length != 0 && $("#cp" + id).length != 0) {
        swal({
            title: "Menghapus Kontrak! anda yakin?",
            text: "Data yang berkaitan dengan kontrak ini akan terhapus!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $("#modal-action").fadeIn('slow');
                    $.ajax({
                        url: 'kontrak/hapus',
                        type: 'POST',
                        data: {
                            '_token': $("#_token").val(),
                            'id': id,
                        },
                        success: function (data) {
                            if (data.errors) {
                                $("#modal-action").fadeOut('slow');
                                swal("Gagal", data.errors, "error");
                            } else {
                                $("#modal-action").fadeOut('slow');
                                swal("Berhasil", data.result, "success");

                                $("#dt" + id).remove();

                            }
                        },
                        error: function (error_data) {
                            $("#modal-action").fadeOut('slow');
                            swal("Gagal", "Gagal Mengapus Data", "error");
                        }
                    })
                }
            });
    } else return swal("Gagal", "Data yang ingin dihapus tidak ada", "warning");
}

// Function Order
function order(id) {
    if ($("#cc" + id).length != 0 && $("#cp" + id).length != 0) {
        window.location = "/kontrak/order/" + id;
    } else return swal("Gagal", "Data yang ingin ditampilkan ordernya tidak ada", "warning");
}
function invoice(id) {
    if ($("#cc" + id).length != 0 && $("#cp" + id).length != 0) {
        window.location = "/kontrak/faktur/" + id;
    } else return swal("Gagal", "Data yang ingin ditampilkan invoicenya tidak ada", "warning");
}
function batalkan(id, name){
    if ($("#cc" + id).length != 0 && $("#cp" + id).length != 0) {
        swal({
            title: "Membatalkan Kontrak",
            text: "Apakah anda ingin Membatalkan Kontrak ini [" + name + "]",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $("#modal-action").fadeIn('slow');
                    $.ajax(
                        {
                            url: "/kontrak/batalkan",
                            type: "POST",
                            data: {
                                '_token': $("#_token").val(),
                                'id': id
                            },
                            success: function(data){
                                if(data.errors)
                                {
                                    $("#modal-action").fadeOut('slow');
                                    swal("Gagal", data.errors, "error");
                                } else {
                                    $("#modal-action").fadeOut('slow');
                                    swal("Berhasil", data.result, "success");

                                    $("#st" + id).html('<span class="label label-danger">Nonaktif</span>');
                                    $("#btn_status" + id).html('<i class="fa fa-sign-in"></i>');
                                    var newFun = 'aktifkan('+id+')'
                                    $("#btn_status"+id).attr('onClick',newFun)
                                    $('#btn_hapus'+id).removeAttr('disabled')
                                }
                            }
                        }
                    );
                }
            });
    } else return swal("Gagal", "Data yang ingin di batalkan kontraknya tidak ada", "warning");
}

function aktifkan(id, name) {
    if ($("#cc" + id).length != 0 && $("#cp" + id).length != 0) {
        swal({
            title: "Aktifkan Kontrak",
            text: "Apakah anda ingin Aktifkan Kontrak ini [" + name + "]",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $("#modal-action").fadeIn('slow');
                    $.ajax(
                        {
                            url: "/kontrak/aktifkan",
                            type: "POST",
                            data: {
                                '_token': $("#_token").val(),
                                'id': id
                            },
                            success: function(data){
                                if(data.errors)
                                {
                                    $("#modal-action").fadeOut('slow');
                                    swal("Gagal", data.errors, "error");
                                } else {
                                    $("#modal-action").fadeOut('slow');
                                    swal("Berhasil", data.result, "success");

                                    $("#st" + id).html('<span class="label label-success">Aktif</span>');
                                    $("#btn_status" + id).html('<i class="fa fa-hand-stop-o"></i>');
                                    var newFun = 'batalkan('+id+')'
                                    $("#btn_status"+id).attr('onClick',newFun)
                                    $("#btn_hapus"+id).attr('disabled','disabled')
                                }
                            }
                        }
                    );
                }
            });

    }else return  swal("Gagal", "Data yang ingin di Aktifkan kontraknya tidak ada", "warning");
}


// Function Dusk Testing Laravel
var id_latest_dusk = 0;
function ubah_terbaru() {
    ubah(id_latest_dusk);
}
function hapus_terbaru() {
    hapus(id_latest_dusk);
}
function detail_terbaru() {
    detail(id_latest_dusk);
}