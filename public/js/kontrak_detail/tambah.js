// Number Divide
$("#budget").divide({
    delimiter:',',
    divideThousand:true
});
$("#budget_ubah").divide({
    delimiter:',',
    divideThousand:true
});

// Cegah Budget Diisi dengan Huruf
$("#budget").on('keypress', function(keys){    
    if(keys.keyCode > 31 && (keys.keyCode < 48 || keys.keyCode > 57)) {
        keys.preventDefault();    
    }
});

// Budget
$("#budget").on('change', function(){
    total_harga_cek();
});
$("#budget").on('keyup', function(){ total_harga_cek(); });
// Function Koma
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
// Mencegah penambahan 2 Kali atau lebih
$("#btn-tambah").on('click', function(){
    if($("#budget").val() != "" && $("#name").val() != "")
    {
        $("#btn-tambah").prop('disabled', true);
        this.form.submit();
    }
});

// Function Cek Total Harga
var total_harga = 0;
function total_harga_cek()
{
    var budget = $("#budget").val();
    budget = budget.replace(/,/g, '');
    if(total_harga > budget)
    {
        $("#total_harga").html('<font color="red"><b>Rp. ' + numberWithCommas(total_harga) + '</b></font>');
        $("#btn-tambah").prop('disabled', true);
    } else {
        $("#total_harga").html('<font color="black"><b>Rp. ' + numberWithCommas(total_harga) + '</b></font>');
        $("#btn-tambah").prop('disabled', false);
    }
    $("#total_harga_tf").val(total_harga);
}
$(document).ready(function(){
    $('input[type="checkbox"]').click(function(){
        if($(this).prop("checked") == true){
            var makanan = document.getElementById('makanan');

            var selected_makanan = $(this).val();

            var id_makanan = selected_makanan.split('|')[0];
            var harga_makanan = selected_makanan.split('|')[1];

            total_harga += parseInt(harga_makanan);
            total_harga_cek();
            
            makanan.value += id_makanan + ',';
        }
        else if($(this).prop("checked") == false){
            var makanan = document.getElementById('makanan').value;

            var selected_makanan = $(this).val();

            var id_makanan = selected_makanan.split('|')[0];
            var harga_makanan = selected_makanan.split('|')[1];
            makanan = makanan.replace(id_makanan + ',', '');

            total_harga -= parseInt(harga_makanan);

            total_harga_cek();
           
            $("#makanan").val(makanan);          
        }
    });
});

// Laravel Dusk Testing -> CheckBox
function pilih_makanan(Max)
{
    var WasChecked = 0;
    for(var i = 0; i < Max; i++)
    {
        // Check ID 0 - 100
        for(var a = WasChecked + 1; a < 100; a++)
        {
            if($("#mk" + a).length != 0)
            {
                $("#mk" + a).click();
                WasChecked = a;
                a = 99;
            }
        }        
    }
}