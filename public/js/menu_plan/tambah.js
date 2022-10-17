$(document).ready(function () {

    list_kategori = $("#list_kategori").val();
    $("#total-harga").html(total_harga)
    category_array = list_kategori.split(',')
    category_array.pop()

    category_array.forEach(cat=>{
      data_list_menu.push( $("#ls_c" + cat).val())
    })
    // $('input[type="checkbox"]').on('click', (function () {
    //     if ($(this).prop("checked") == true) {
    //         var makanan = document.getElementById('makanan');
    //         makanan.value += $(this).val() + ',';
    //     }
    //     else if ($(this).prop("checked") == false) {
    //         var makanan = document.getElementById('makanan').value;
    //         makanan = makanan.replace($(this).val() + ',', '');

    //         $("#makanan").val(makanan);
    //     } 
    // }));
    // var d
    // list_kategori.map(e =>{
    //   d = [e]
    // })
});

//load_data()
var data_list_menu= new Array

var category_array = new Array
var total_harga = 0;
$("#menu1").select2();
$("#status1").select2();



// Function OnClick
// function ch(id) {
//     if ($("#mk" + id).prop("checked") == true) {
//         var makanan = document.getElementById('makanan');
//         makanan.value += $("#mk" + id).val() + ',';
//     }
//     else if ($("#mk" + id).prop("checked") == false) {
//         var makanan = document.getElementById('makanan').value;
//         makanan = makanan.replace($("#mk" + id).val() + ',', '');

//         $("#makanan").val(makanan);
//     }
// }

$("#date").datetimepicker({
    format: 'YYYY-MM-DD'
});
$("#date").keydown(function (event) {
    return false;
});

// Tanggal Berubah
var list_kategori = "";
// function performChange(date) {
//     $("#btn-tambah").prop('disabled', true);

//     // Kirim Data
//     $.ajax({
//         url: '/menu_plan/tambah/cek',
//         type: 'POST',
//         data: {
//             '_token': $("#_token").val(),
//             'date': date
//         },
//         success: function (data) {

//             // Menghapus semua checkbox
//             var lk_sp = list_kategori.split(',');
//             for (i = 0; i < lk_sp.length - 1; i++) {
//                 $("#kt" + lk_sp[i]).html("");
//                 $("#kt_hd" + lk_sp[i]).prop('hidden', false);
//             }


//             // Restore
//             for (i = 0; i < data.result.length; i++) {
//                 $("#kt" + data.result[i].id_kategori).append(
//                     ' \
//                     <div class="checkbox"> \
//                         <label> \
//                             <input type="checkbox" onclick="ch(' + data.result[i].id + ')" id="mk' + data.result[i].id + '" value="' + data.result[i].id + '|' + data.result[i].real_price + '">' + data.result[i].name + ' (Rp. <b>' + data.result[i].price + '</b>) \
//                         </label> \
//                     </div>'
//                 );
//             }

//             // Hidden yang kosong
//             for (i = 0; i < lk_sp.length; i++) {
//                 if ($("#kt" + lk_sp[i]).html() == "") {
//                     $("#kt_hd" + lk_sp[i]).prop('hidden', true);
//                 }
//             }

//             if (data.check_default == true) {
//                 $("#isDefault").html('<option value="0">Alternatif</option>');
//             } else {
//                 $("#isDefault").html('<option value="1">Default</option><option value="0">Alternatif</option>');
//             }
//             $("#btn-tambah").prop('disabled', false);
//             $("#makanan").val("");
//         }
//     })
// }
// $("#date").on('dp.change', function (e) {
//     performChange(
//         e.date.format(e.date._f).toString().split('T')[0]
//     );
// });

// Load Data
var list_data_html = null;
// function load_data()
// {
//     list_data_html = null;
//     $.ajax({
//         url: '/menu_plan/tambah/data',
//         type: 'POST',
//         data: {
//             '_token': $("#_token").val()
//         },
//         success: function(data) {
//             for(i = 0; i < data.result.length; i++)
//             {
//                 list_data_html += "<option value='" + data.result[i].id + "|" + data.result[i].price + "'>" + data.result[i].nama + " (Rp. " + data.result[i].price_f + ")</option>";
//             }
//         }
//     });
// }


$("#btn-tambah").on('click', function(){      
    if($("#nama").val() == "") return swal("Gagal", "Nama rencana masih kosong", "error");
    if($("#date").val() == "") return swal("Gagal", "Tanggal masih kosong", "error");
    if(idx_list == "") return swal("Gagal", "Tidak ada menu yang ditambahkan", "error");
    var formData = $("#form-submit").serializeArray()
    var resultCheck = [false,false,false,false]

    for (let index = 0; index < category_array.length; index++) {
      var category = category_array[index]
      var menuCategory = $("#ct" + category).data('menu')
      if (menuCategory) {
        for (let cat_index = 4; cat_index < formData.length; cat_index++) {
          for (let menu_index = 0; menu_index < menuCategory.length; menu_index++) {
            if (formData[cat_index].name == menuCategory[menu_index]) {
              if (formData[cat_index+1].value == 1) {
                resultCheck[index]= true 
              }
            }
          }
        }
      }
    }
    
    var check = true
    resultCheck.forEach(cat => {
      if (cat == false) {
        check = false
      }
    });
    if (!check) return swal("Gagal", "ada kategori yang tidak memiliki menu plan default", "error");
      
    $("#form-submit").submit();
    $("#btn-tambah").prop('disabled', true);
});


// Function Tambah Menu Makanan
var idx_list = "";
var idx = 0;
// $("#tambah_menu_makanan").on('click', function(){  
//   $("#menu_makanan").append(' \
//   <div class="col-md-6" id="card' + idx + '"> \
//     <div class="card-menu"> \
//       <div class="card-container"> \
//         <table border="0" style="width: 100%"> \
//           <tr> \
//             <td>Menu :</td> \
//             <td>Status :</td> \
//             <td></td> \
//           </tr> \
//           <tr> \
//             <td width="70%"> \
//               <select class="form-control" name="menu' + idx + '" id="menu' + idx + '" data-placeholder="Pilih Makanan"> \
//                 ' + list_data_html + '\
//               </select> \
//             </td> \
//             <td width="30%"> \
//               <select class="form-control" name="status' + idx + '" id="status' + idx + '" data-placeholder="Pilih Status"> \
//                 <option value="2">Extra</option> \
//                 <option value="1">Default</option> \
//                 <option value="0">Alternatif</option> \
//               </select> \
//             </td> \
//             <td> \
//               <input type="button" class="btn btn-danger btn-sm" value="X" onclick="cl_cr(' + idx + ')"> \
//             </td> \
//           </tr> \
//       </table> \
//       </div> \
//     </div> \
//   </div> \
//   ');
//   $("#card" + idx).prop('hidden', true);
//   $("#card" + idx).fadeIn('fast');
//   refSel(idx);
//   idx_list += idx + ",";
//   $("#list_menu").val(idx_list);
//   idx++;
// });

//fungsi untuk memberikan atribut disable pada button tambah
function setDisableButton() {
  $("#btn-tambah").attr("disabled","disabled")
  $("#btn-tambah").addClass("disabled")
}

//fungsi untuk menghilangkan atribut disable pada button tambah
function setAcitvateButton() {
    $("#btn-tambah").removeAttr("disabled","disabled")
    $("#btn-tambah").removeClass("disabled")
}

function addtotal(harga) {
  this.total_harga += harga
  $("#total-harga").html(total_harga)

  if (this.total_harga > 12000) {
    $("#total-harga").addClass("info-total-harga-warning")
    setDisableButton()
  }
}
function minustotal(harga){
  this.total_harga -= harga
  $("#total-harga").html(total_harga)

  if (this.total_harga <= 12000 && $("#total-harga").hasClass("info-total-harga-warning")) {
    $("#total-harga").removeClass("info-total-harga-warning")
    setAcitvateButton()
  }
}

function getOption(new_id,id_categori) {
  var split = data_list_menu[id_categori-2].split(";")
  var optionGet = ""
  split.forEach(e => {
    if(e.search(new_id)>=0){
      optionGet = e
      e.slice()
    }
  });

  var sp_sperator = optionGet.split('|');
  return "<option value='" + sp_sperator[0] + "|" + sp_sperator[3] + "'>" + sp_sperator[1] + " (Rp. " + sp_sperator[2] + ")</option>";
}

function menuChange(id_select_menu){
  var id_menu = "#menu"+id_select_menu
  var id_status = "#status"+id_select_menu
  var data = $(id_menu).children("option:selected").val()
  var status = $(id_status).children("option:selected").val()
  var oldMenu = $("#menu"+id_select_menu).data("menuBefore")
  
  if (status == 1) {
    $("#menu"+id_select_menu).data("menuBefore",data)
    addtotal(parseInt(data.split('|')[1]))
    minustotal(parseInt(oldMenu.split('|')[1]))
  }
}

function menuDestroy(id_select_menu) {
  var id_menu = "#menu"+id_select_menu
  var id_status = "#status"+id_select_menu
  var data = $(id_menu).children("option:selected").val()
  var status = $(id_status).children("option:selected").val()
  if (status == 1) {
    minustotal(parseInt(data.split('|')[1]))
  }


}

function statusChange(id_select_status) {
  var id_menu = "#menu"+id_select_status
  var id_status = "#status"+id_select_status
  var data = $(id_menu).children("option:selected").val()
  var status = $(id_status).children("option:selected").val()
  var oldStatus = $("#status"+id_select_status).data("statusBefore")
  if (status == 1) {
    addtotal(parseInt(data.split('|')[1]))
  }else{
    if (oldStatus == 1) {
      minustotal(parseInt(data.split('|')[1]))
    }
  }
  $("#status"+id_select_status).data("statusBefore",status)
}

function getListData(id_categori,menu) {
  //fitur menghilangkan makanan yg telah dipilih
  var result = data_list_menu[id_categori-2]
  var split = data_list_menu[id_categori-2].split(";")
  split.shift()
  var join = split.join(";")
  //$("#ls_c" + id_categori).val(join);
  data_list_menu[id_categori-2] = join

  if (split.length == 0) return false
  return result
}

function updateMenuOption(id_categori,menu){
  var menuList = $("#ct" + id_categori).data('menu')
  var index = menuList.indexOf(menu)
  if (index >= 0) {
   menuList.slice(index,1) 
  }
  menuList.forEach(menu => {
    var sp_dl = data_list_menu[id_categori-2].split(";")
    list_data_html = ""
    for(i = 0; i < sp_dl.length - 1; i++)
    {   
        var sp_sperator = sp_dl[i].split('|');
        list_data_html += "<option value='" + sp_sperator[0] + "|" + sp_sperator[3] + "'>" + sp_sperator[1] + " (Rp. " + sp_sperator[2] + ")</option>";
    }
    var chosenMenu = $("#"+menu).data("getmenuchosen")
    chosenMenu+=list_data_html
    $("#"+menu).html(chosenMenu)
  });
}

function add(id_categori)
{
    if($("#ls_c" + id_categori).length == 0) return;

    var list_data_html = "";
    var data_list =""
    data_list = getListData(id_categori,"menu" + idx)

    if (!data_list) return;
    var sp_dl = data_list.split(';');
    var data_harga = new Array

    var menuf

    for(i = 0; i < sp_dl.length - 1; i++)
    {   
        var sp_sperator = sp_dl[i].split('|');
        list_data_html += "<option value='" + sp_sperator[0] + "|" + sp_sperator[3] + "'>" + sp_sperator[1] + " (Rp. " + sp_sperator[2] + ")</option>";
        data_harga.push({harga: sp_sperator[3],id: sp_sperator[0]})
        if (i == 0) {
          menuf = list_data_html
        }
    }
    
    

    $("#ct" + id_categori).append(' \
    <div id="card' + idx + '"> \
      <div class="card-menu"> \
        <div class="card-container"> \
          <table border="0" style="width: 100%"> \
            <tr> \
              <td>Menu :</td> \
              <td>Status :</td> \
              <td></td> \
            </tr> \
            <tr> \
              <td width="70%"> \
                <select class="form-control" name="menu' + idx + '" id="menu' + idx + '" data-placeholder="Pilih Makanan" onchange="menuChange('+ idx +')"> \
                  ' + list_data_html + '\
                </select> \
              </td> \
              <td width="30%"> \
                <select class="form-control" name="status' + idx + '" id="status' + idx + '" data-placeholder="Pilih Status" onchange="statusChange('+ idx +')"> \
                  <option value="1">Default</option> \
                  <option value="0">Alternatif</option> \
                  <option value="2">Extra</option> \
                </select> \
              </td> \
              <td> \
                <input type="button" class="btn btn-danger btn-sm" value="X" onclick="cl_cr(' + idx + ')"> \
              </td> \
            </tr> \
        </table> \
        </div> \
      </div> \
    </div> \
    ');
    $("#card" + idx).prop('hidden', true);
    $("#card" + idx).fadeIn('fast');
    refSel(idx);
    idx_list += idx + ",";
    $("#list_menu").val(idx_list);
    let menuBefore = $("#menu"+idx).children("option:selected").val()
    let statusBefore = $("#status"+idx).children("option:selected").val()
    $("#menu"+idx).data("menuBefore",menuBefore)
    $("#status"+idx).data("statusBefore",statusBefore) 
    $("#menu"+idx).data("getmenuchosen",menuf)
    $("#menu"+idx).data("thisCategoryMenu",id_categori)
  
    //testing
    var menu = $("#ct" + id_categori).data('menu')
    if (!menu) {
      $("#ct" + id_categori).data('menu',["menu"+idx])
    }else{
      menu.push("menu"+idx)
      $("#ct" + id_categori).data('menu',menu)
    }
    //$("#ct" + id_categori).data('menu',menu)
    addtotal(parseInt(data_harga[0].harga))
   idx++;  
    
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function refSel(idx)
{
    $("#menu" + idx).select2(); $("#status" + idx).select2();
}
// Hapus Semua
$("#hapus_semua_menu").on('click', function(){
    if(idx_list.length != 0)
    {        
        var sp_idx = idx_list.split(',');
        for(i = 0; i < sp_idx.length - 1; i++)
        {
            cl_cr(sp_idx[i]);
        }
    }
});
// Close Function
function cl_cr(id)
{
  menuDestroy(id)
    if($("#card" + id).length != 0)
    {
        $("#card" + id).fadeOut('fast', function(){
            $("#card" + id).remove();
            idx_list = idx_list.replace(id + ",", "");
        });
        $("#list_menu").val(idx_list);
    }
}