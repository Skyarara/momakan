$("#btn-tambah").on('click', function(){
    window.location =  window.location.pathname + "/tambah";
});

$("#btn-tambah-action").on('click', function(){
    $("#btn-tambah-action").prop('disabled', true);
    this.form.submit();
});

function delete_data(id) {
    swal({
        title: "Menghapus Data",
        text: "Apakah anda ingin menghapus Menu Plan ini",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {
                $("#modal-action").fadeIn("slow");
                $.ajax({
                    url: "/menu_plan/delete",
                    type: "POST",
                    data: '_token=' + $('input[name=_token]').val() + '&' + 'id=' + id,
                    success: function (data) {
                        $("#modal-action").fadeOut("slow");
                        swal({
                            title: "Menu Plan Berhasil Dihapus",
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