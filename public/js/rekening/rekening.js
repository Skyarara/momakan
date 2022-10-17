//Add
$(document).on('click', '#new', function () {
    $('#create').modal('show');
    $('.form-horizontal').show();
    $('.modal-title').text('Rekening Baru');
});

$("#bank_logo").keydown(function () {
    document.getElementById('er1').hidden = true;
});
$("#bank_name").keydown(function () {
    document.getElementById('er2').hidden = true;
});
$("#account_name").click(function () {
    document.getElementById('er3').hidden = true;
});
$("#account_number").click(function () {
    document.getElementById('er4').hidden = true;
});

$("#add").click(function () {
    document.getElementById('add').disabled = true;
    var bank_logo = document.getElementById('bank_logo').value;
    var bank_name = document.getElementById('bank_name').value;
    var account_name = document.getElementById('account_name').value;
    var account_number = document.getElementById('account_number').value;
    
    if (bank_logo == null || bank_logo == "" || bank_name == null || bank_name == "" || account_name == null || account_name == "" || account_number == null || account_number == "") {
        if (bank_logo == null || bank_logo == "") { document.getElementById('er1').hidden = false; }
        if (bank_name == null || bank_name == "") { document.getElementById('er2').hidden = false; }
        if (account_name == null || account_name == "") {  document.getElementById('er3').hidden = false; }
        if (account_number == null || account_number == "") {  document.getElementById('er4').hidden = false; }
        document.getElementById('add').disabled = false;
    } else {
        $.ajax({
            type: 'POST',
            url: '/rekening/add',
            data: new FormData($('#add_form')[0]),
            processData: false,
            contentType: false,
            success: function (data) {
                document.getElementById('add').disabled = false;
                $('#create').modal('hide');
                swal({ title: "Berhasil Menambahkan Data", text: "", icon: "success", })
                .then(function () { location.reload(); });
            },
        });
    }
});

//Edit
$(document).on('click', '#edit', function () {
    document.getElementById('edit_bank_name').removeAttribute('readonly');
    document.getElementById('edit_account_name').removeAttribute('readonly');
    document.getElementById('edit_account_number').removeAttribute('readonly');
    $('#footer_action_button').text("Ubah");
    $('.actionBtn').addClass('edit');
    $('.modal-title').text('Ubah Rekening');
    $('.form-horizontal').show();
    $('#id').val($(this).data('id'));
    $('#edit_bank_name').val($(this).data('bank_name'));
    $('#edit_account_name').val($(this).data('account_name'));
    $('#edit_account_number').val($(this).data('account_number'));
    $('#show').modal('show');
    $('#update').show();
    $('#img').text('Logo Bank Lama');
    var imgsrc = $(this).data('img');
    $('#my_image').attr('src', imgsrc);
    $('#edit_gambar').show();
});

$('.modal-footer').on('click', '#update', function () {
    $.ajax({
        type: 'POST',
        url: '/rekening/edit',
        data: new FormData($('#edit_form')[0]),
        processData: false,
        contentType: false,
        success: function (data) {
            if (data == 2) {
                swal("Gagal Mengubah Data", "Terdapat Field Kosong", "error");
            }
            
            else {
                swal({ title: "Berhasil Mengubah Data", text: "", icon: "success", })
                .then(function () { location.reload(); });
            }
        }
    });
});

//Delet
function delete_data(id) {
    swal({ title: "Menghapus Data", text: "Apakah anda ingin menghapus data ini", icon: "warning", buttons: true, dangerMode: true, })
    .then((willDelete) => {
        if (willDelete) {
            $("#modal-action").fadeIn("slow");
            $.ajax({
                url: "/rekening/delete",
                type: "POST",
                data: '_token=' + $("#_token").val() + '&' + 'id=' + id,
                success: function (data) {
                    if (data == 0) {
                        $("#modal-action").fadeOut("slow");
                        swal("Gagal menghapus data, harap coba lagi", { icon: "error", });
                    } else {
                        $("#modal-action").fadeOut("slow");
                        swal("Data berhasil dihapus", { icon: "success", })
                        .then(function () { location.reload(); });
                    }
                },
                error: function (data) {
                    $("#modal-action").fadeOut("slow");
                    swal("Gagal menghapus data!", { icon: "error", });
                }
            });
        } else {
            
        }
    });
}

//View
$(document).on('click', '#see', function () {
    $('#edit_bank_name').prop('readonly', 'true');
    $('#edit_account_name').prop('readonly', 'true');
    $('#edit_account_number').prop('readonly', 'true');
    $('#footer_action_button').text("Ubah");
    $('.modal-title').text('Lihat Rekening');
    $('#img').text('Gambar');
    $('.form-horizontal').show();
    $('#id').val($(this).data('id'));
    $('#edit_bank_name').val($(this).data('bank_name'));
    $('#edit_account_name').val($(this).data('account_name'));
    $('#edit_account_number').val($(this).data('account_number'));
    $('#show').modal('show');
    $('#update').show();
    var imgsrc = $(this).data('img');
    $('#my_image').attr('src', imgsrc);
    $('#edit_gambar').hide();
    $('#update').hide();
});