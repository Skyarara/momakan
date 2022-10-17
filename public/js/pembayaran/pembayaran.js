$(document).on('click', '.create-modal', function () {
    $('#plus').modal('show');
    $('.modal-title').text('Tambah Pembayaran');
});

//menampilkan foto setiap ada perubahan pada modal tambah
$('#receipt_photo').on('change', function() {
    readURL(this);
});
function readURL(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#image_receipt').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
  }
}

//menampilkan foto setiap ada perubahan pada modal edit
$('#edit_receipt_photo').on('change', function() {
    readURLEdit(this);
});
function readURLEdit(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
          $('#edit_image_receipt').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
  }
}

// Number Divide
$("#paid_amount").divide({
    delimiter:',',
    divideThousand:true
});

// Cegah Paid Amount Diisi dengan Huruf
$("#paid_amount").on('keypress', function(keys){ 
    if(keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
        keys.preventDefault();    
    }
});

//input paid amount modal edit
// Number Divide
$("#edit_paid_amount").divide({
    delimiter:',',
    divideThousand:true
});

// Cegah Paid Amount Diisi dengan Huruf
$("#edit_paid_amount").on('keypress', function(keys){ 
    if(keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
        keys.preventDefault();    
    }
});

$(document).on('click', '#edit-modal', function () {
    $('#edit').modal('show');
    $('.modal-title').text('Edit Pembayaran');
    $('#old_id').val($(this).data('id'));
    $('#old_invoice_id').val($(this).data('invoice_id'));
    $('#edit_invoice_id').val($(this).data('invoice_id')).trigger('change');
    $('#edit_date').val($(this).data('date'));

    $('#edit_paid_amount').val($(this).data('paid_amount'))
    $('#edit_image_receipt').attr('src',$(this).data('receipt_photo'))

    if ($(this).data('is_verified')) {
        $('#edit_is_verified_true').prop('checked',true)
    } else {
        $('#edit_is_verified_false').prop('checked',true)
    }

});

$('#edit').on('change', '#edit_invoice_id', function () {
    $.ajax({
        type: 'get',
        url: 'pembayaran/get_invoice',
        data: {
            'invoice_id': $("#edit_invoice_id").val(),
        },
        success: function (data) {
            if (data['employee'] != 'false') {
                $('#edit').find('#wrap').remove();
                $('#edit').find('.result').append("<div id='wrap'>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nomor Invoice :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='nomor' name='nomor' value='" + data['nomor'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nama Perusahaan :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='perusahaan' name='perusahaan' value='" + data['perusahaan'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nama Pegawai :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='perusahaan' name='perusahaan' value='" + data['employee'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Total :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='total' name='total' value='" + data['total'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                );
            } else {
                $('#edit').find('#wrap').remove();
                $('#edit').find('.result').append("<div id='wrap'>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nomor Invoice :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='nomor' name='nomor' value='" + data['nomor'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nama Perusahaan :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='perusahaan' name='perusahaan' value='" + data['perusahaan'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Total :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='total' name='total' value='" + data['total'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                );
            }
        },
    });
});

$('#plus').on('change', '#invoice_id', function () {
    $.ajax({
        type: 'get',
        url: 'pembayaran/get_invoice',
        data: {
            'invoice_id': $("#invoice_id").val(),
        },
        success: function (data) {
            //Menampilkan Error
            if (data == 0) {
                $('#plus').find('#wrap').remove();
                $('#add').attr("disabled", false);
                var notice = '<div class="alert alert-warning alert-dismissible fade in" style="padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Harap Coba Lagi </div>';
                $('#plus').find('.box-error').show();
                $('#plus').find('.box-error').html(notice);
                $('#nomor').val('');
                $('#perusahaan').val('');
                $('#bulan').val('');
                $('#total').val('');
                //Fungsi Berhasil
            } else if (data['employee'] != 'false') {
                $('#plus').find('#wrap').remove();
                $('#plus').find('.result_add').append("<div id='wrap'>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nomor Invoice :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='nomor' name='nomor' value='" + data['nomor'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nama Perusahaan :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='perusahaan' name='perusahaan' value='" + data['perusahaan'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nama Pegawai :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='perusahaan' name='perusahaan' value='" + data['employee'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Total :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='total' name='total' value='" + data['total'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                );
                $('.box-error').hide();
            } else {
                $('#plus').find('#wrap').remove();
                $('#plus').find('.result_add').append("<div id='wrap'>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nomor Invoice :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='nomor' name='nomor' value='" + data['nomor'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Nama Perusahaan :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='perusahaan' name='perusahaan' value='" + data['perusahaan'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "<div class='form-group row add'>"
                    + "<label class='control-label col-sm-2' for='nomor'>Total :</label>"
                    + "<div class='col-sm-10'>"
                    + "<input class='form-control' id='total' name='total' value='" + data['total'] + "'readonly>"
                    + "</div>"
                    + "</div>"
                    + "</div>"
                );
                $('.box-error').hide();
            }
        },
    });
});

$('.modal-footer').on('click', '#add', function () {
    $('#add').attr("disabled", true);


    
    //Post Data tambah
    $.ajax({
        type: 'POST',
        url: '/pembayaran/tambah',
        data: new FormData($("#form-add-payment")[0]),
        processData: false,
        contentType: false,
        success: function (data) {
            //Menampilkan Error
            if (data == 1) {
                $('#add').attr("disabled", false);
                var notice = '<div class="alert alert-danger alert-dismissible fade in" style="padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Pembayaran Sudah Dibuat </div>';
                $('#plus').find('.box-error').html(notice);
            }
            else if (data == 2) {
                $('#add').attr("disabled", false);
                swal({
                    title: "Terdapat Field Kosong",
                    text: "",
                    icon: "error",
                })
            }else  if (data == 3) {
                $('#add').attr("disabled", false);
                swal({
                    title: "Gagal Mengupload Gambar",
                    text: "",
                    icon: "error",
                })
            } else {
                $('#plus').modal('hide');
                swal({
                    title: "Berhasil Menambahkan Data Pembayaran",
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
    $('.box-error-edit').show();
    //Post Data edit
    $.ajax({
        type: 'POST',
        url: '/pembayaran/edit',
        data: new FormData($("#form-edit-payment")[0]),
        processData: false,
        contentType: false,
        success: function (data) {
            //Menampilkan Error
            if (data == 1) {
                var notice = '<div class="alert alert-danger alert-dismissible fade in" style="padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> Pembayaran Sudah Dibuat </div>';
                $('#edit').find('.box-error').html(notice);
                $('#update').attr("disabled", false);
            } else if (data == 4) {
                $('#update').attr("disabled", false);
                swal({
                    title: "Gagal Mengubah Data Pembayaran",
                    text: "Terdapat Field Kosong",
                    icon: "error",
                })
                //Fungsi Berhasil
            }else if (data == 3) {
                $('#update').attr("disabled", false);
                swal({
                    title: "Gagal Mengupload Gambar",
                    text: "",
                    icon: "error",
                })
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
                    url: "/pembayaran/delete",
                    type: "POST",
                    data: '_token=' + $('input[name=_token]').val() + '&' + 'id=' + id,
                    success: function (data) {
                        $("#modal-action").fadeOut("slow");
                        swal({
                            title: "Pembayaran Berhasil Dihapus",
                            text: "",
                            icon: "success",
                        })
                            .then(function () {
                                location.reload()
                            });
                    },
                });
            } else { }
        });
}
