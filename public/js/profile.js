
$(document).on('click', '#editan', function () {
    $('#edit').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Ubah Profile');
    $('#main').hide();
});

$('.modal-footer').on('click', '#update', function () {
    $('#update').attr("disabled", true);
    //Post Data Edit
    $.ajax({
        type: 'POST',
        url: '/profile/edit',
        data: {
            'id': $('#id').val(),
            '_token': $('input[name=_token]').val(),
            'corporate_id': $('#corporate_id').val(),
            'address_edit': $("#address_edit").val(),
            'email_edit': $("#email_edit").val(),
            'phone_edit': $("#phone_edit").val(),
        },
        success: function (data) {
            //Menampilkan Error
            if (data.error.length > 0) {
                $('#add').attr("disabled", false);
                var error_html = '';
                for (var count = 0; count < data.error.length; count++) {
                    error_html += '<div class="alert alert-dismissible fade in" style="background:#ff000087;padding-top:10px;padding-bottom:10px;"> <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + data.error[count] + '</div>';
                }
                $('#edit').find('.box-error').html(error_html);
            }
            //Fungsi Berhasil
            else {
                $('#edit').modal('hide');
                swal({
                    title: "Berhasil Mengubah Data",
                    text: "",
                    icon: "success",
                })
                    .then(function () {
                        location.reload()
                    }
                    );
            }
        },
    });

});