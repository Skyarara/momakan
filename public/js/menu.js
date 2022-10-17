$("#harga").divide({
  delimiter: ',',
  divideThousand: true
});

$("#harga_edit").divide({
  delimiter: ',',
  divideThousand: true
});

$('#harga').on('keypress', function (keys) {
  if (keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
    keys.preventDefault();
  }
});

$('#harga_edit').on('keypress', function (keys) {
  if (keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
    keys.preventDefault();
  }
});

$("#nama_makanan").keydown(function () { hid_er(1); });
$("#deskripsi").keydown(function () { hid_er(2); });
$("#harga").change(function () { hid_er(3); });
$("#gambar").change(function () { hid_er(4); });
$("#tambah-btn").click(function () {
  document.getElementById('alert').hidden = true;
  if ($("#nama_makanan").val() != "" &&
    $("#deskripsi").val() != "" &&
    $("#harga").val() != "" &&
    $("#gambar").val() != "") {
    $("#modal-default").modal('hide');
    $("#modal-action").fadeIn("slow");
    $.ajax({
      url: '/menu_makanan/tambah',
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
            }
            );
          $("#modal-action").fadeOut("slow");
          $("#modal-default").modal('hide');

          document.getElementById('nama_makanan').value = "";
          document.getElementById('deskripsi').value = "";
          document.getElementById('harga').value = "";
          document.getElementById('gambar').value = "";

          var sp = data.split('|');
          var no = sp[5];
          var row = document.getElementById('table-data').rows.length;
          document.getElementById('body-t').innerHTML += "<tr><td>" + row + "</td><td id='name" + no + "'><img src='/storage/images/" + sp[1] + "' class='img-thumbnail' width='75' height='75'></img></td><td id='name" + no + "'>" + sp[2] + "</td><td id='desc" + no + "'>" + sp[3] + "</td><td id='price" + no + "'>" + sp[4] + "</td><td><div class='btn-group'><button type='button' onclick='view_data(" + no + ");' class='btn btn-primary'><i class='fa fa-eye'></i></button><button type='button' onclick='edit_data(" + no + ");' class='btn btn-warning'><i class='fa fa-edit'></i></button><button type='button' onclick='delete_data(" + no + ");' class='btn btn-danger'><i class='fa fa-remove'></i></button></div></td></tr>";

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
    if ($("#nama_makanan").val() == "") document.getElementById('er1').hidden = false;
    if ($("#deskripsi").val() == "") document.getElementById('er2').hidden = false;
    if ($("#harga").val() == "") document.getElementById('er3').hidden = false;
    if ($("#gambar").val() == "") document.getElementById('er4').hidden = false;
  }
});

$(document).ready(function () {
  reset_er();
  $('.kategori-makanan').select2();
});

// Place Function in here
function view_data(id) {
  $("#modal-action").fadeIn("slow");
  $.ajax({
    url: '/menu_makanan/detail',
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

function edit_data(id) {
  $("#kategori_makanan_edit").select2().next().hide();
  // $('#harga_edit').val($("#price" + id).val());

  $.ajax({
    url: '/menu_makanan/detail',
    type: 'POST',
    data: '_token=' + $("#_token").val() + '&id=' + id,
    success: function (data) {
      var Cnt = data.split('|CNT|')[1];
      document.getElementById('edit_div').innerHTML = "";
      document.getElementById('edit_div').innerHTML = data;
      // sleep(10);
      var KategoriMakanan = document.getElementById('kategori_makanan_detail_' + Cnt).value;

      $("#kategori_makanan_edit").val(KategoriMakanan).change();

      $("#kategori_makanan_edit").select2().next().show();

    }
  });

  document.getElementById('nama_makanan_edit').value = document.getElementById('name' + id).innerHTML;
  document.getElementById('deskripsi_edit').value = document.getElementById('desc' + id).innerHTML;
  var price = document.getElementById('price' + id).innerHTML.split('Rp. ')[1];
  // price = price.toString().replace(',', '');
  $("#harga_edit").divide({
    delimiter: '',
    divideThousand: false
  });
  document.getElementById('harga_edit').value = price;
  $("#harga_edit").divide({
    delimiter: ',',
    divideThousand: true
  });
  document.getElementById('gambar_lama').innerHTML = document.getElementById('image' + id).innerHTML;
  document.getElementById('id').value = id;
  $("#modal-default-edit").modal('show');
}

$("#nama_makanan_edit").keydown(function () { hid_er_edit(1); });
$("#deskripsi_edit").keydown(function () { hid_er_edit(2); });
$("#harga_edit").change(function () { hid_er_edit(3); });
$("#ubah-btn").click(function () {
  document.getElementById('alert_edit').hidden = true;
  if ($("#nama_makanan_edit").val() != "" &&
    $("#deskripsi_edit").val() != "" &&
    $("#harga_edit").val() != "") {
    swal({
      title: "Mengubah Data",
      text: "Apakah anda ingin mengubah data id ini ?",
      icon: "warning",
      buttons: true,
      dangerMode: true,
    })
      .then((willUpdate) => {
        if (willUpdate) {
          $("#modal-default-edit").modal('hide');
          $("#modal-action").fadeIn("slow");
          $.ajax({
            url: '/menu_makanan/ubah',
            type: 'POST',
            processData: false,
            contentType: false,
            data: new FormData($('#edit_form')[0]),
            success: function (data) {
              data = data.split('|');
              if (data[0] == 1) {
                swal("Sukses!", "Data berhasil diubah!", "success")
                  .then(function () {
                    location.reload();
                  }
                  );
                $("#modal-default-edit").modal('hide');
                $("#modal-action").fadeOut("slow");

                document.getElementById('nama_makanan').value = "";
                document.getElementById('deskripsi').value = "";
                document.getElementById('harga').value = "";
                document.getElementById('gambar').value = "";

                var sp = data.split('|');
                var no = sp[5];
                var row = document.getElementById('table-data').rows.length;
                document.getElementById('body-t').innerHTML += "<tr><td>" + row + "</td><td id='name" + no + "'><img src='/storage/images/" + sp[1] + "' class='img-thumbnail' width='75' height='75'></img></td><td id='name" + no + "'>" + sp[2] + "</td><td id='desc" + no + "'>" + sp[3] + "</td><td id='price" + no + "'>" + sp[4] + "</td><td><div class='btn-group'><button type='button' onclick='view_data(" + no + ");' class='btn btn-primary'><i class='fa fa-eye'></i></button><button type='button' onclick='edit_data(" + no + ");' class='btn btn-warning'><i class='fa fa-edit'></i></button><button type='button' onclick='delete_data(" + no + ");' class='btn btn-danger'><i class='fa fa-remove'></i></button></div></td></tr>";
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
    if ($("#nama_makanan_edit").val() == "") document.getElementById('er1_edit').hidden = false;
    if ($("#deskripsi_edit").val() == "") document.getElementById('er2_edit').hidden = false;
    if ($("#harga_edit").val() == "") document.getElementById('er3_edit').hidden = false;
  }
});
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
          url: "/menu_makanan/hapus",
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
              swal("Data Tidak Dapat Dihapus", "karena memiliki relasi dengan menu plan", "error");
            } else {
              $("#modal-action").fadeOut("slow");
              document.getElementById('nama_makanan').value = "";
              document.getElementById('deskripsi').value = "";
              document.getElementById('harga').value = "";
              document.getElementById('gambar').value = "";

              var sp = data.split('|');
              var no = sp[4];
              var row = document.getElementById('table-data').rows.length;
              document.getElementById('body-t').innerHTML += "<tr><td id='name" + no + "'>" + sp[1] + "</td><td id='desc" + no + "'>" + sp[2] + "</td><td id='price" + no + "'>" + sp[3] + "</td></tr>";
              swal("Sukses!", "Data berhasil dihapus!", "success")
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