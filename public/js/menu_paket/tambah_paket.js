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
    $('input[type="checkbox"]').click(function () {
        if ($(this).prop("checked") == true) {
            var makanan = document.getElementById('makanan');
            makanan.value += $(this).val() + ',';
        }
        else if ($(this).prop("checked") == false) {
            var makanan = document.getElementById('makanan').value;
            makanan = makanan.replace($(this).val() + ',', '');

            $("#makanan").val(makanan);
        }
    });
});


