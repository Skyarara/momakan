$('#harga').divide({
    delimiter: ',',
    divideThousand: true
});

// Cegah Budget Diisi dengan Huruf
$('#harga').on('keypress', function (keys) {
    if (keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
        keys.preventDefault();
    }
});

$(document).ready(function () {
    $('#kategori').select2();
});
$(document).ready(function () {
    $('#vendor').select2();
});

$(document).ready(function () {

    // Load
    var makanan = $("#makanan").val();
    var sp = makanan.split(',');
    for (i = 0; i < sp.length; i++) {
        if (sp[i] != "") {
            if ($("#mk" + sp[i]).length != 0) {
                var harga = $("#mk" + sp[i]).val();
                $("#mk" + sp[i]).prop('checked', true);
            }
        }
    }


    $('input[type="checkbox"]').click(function () {
        if ($(this).prop("checked") == true) {
            var makanan = document.getElementById('makanan');

            var selected_makanan = $(this).val();

            var id_makanan = selected_makanan.split('|')[0];

            makanan.value += id_makanan + ',';
        }
        else if ($(this).prop("checked") == false) {
            var makanan = document.getElementById('makanan').value;

            var selected_makanan = $(this).val();

            var id_makanan = selected_makanan.split('|')[0];
            makanan = makanan.replace(id_makanan + ',', '');

            $("#makanan").val(makanan);
        }
    });
});