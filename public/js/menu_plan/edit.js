// load_data()

$("#date").datetimepicker({
    format: 'YYYY-MM-DD'
});
$("#date").keydown(function (event) {
    return false;
});

// Load Data
var list_data_html = "";
// function load_data()
// {
//     list_data_html = "";
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
//             sleep(100)
//             load_data_all();
//         }
//     });
// }
$("#btn-ubah").on('click', function(){    
    if($("#nama").val() == "") return swal("Gagal", "Nama rencana masih kosong", "error");
    if($("#date").val() == "") return swal("Gagal", "Tanggal masih kosong", "error");
    if(idx_list == "") return swal("Gagal", "Tidak ada menu yang Diubah", "error");
    
    $("#form-submit").submit();
    $("#btn-ubah").prop('disabled', true);
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
    if($("#card" + id).length != 0)
    {
        $("#card" + id).fadeOut('fast', function(){
            $("#card" + id).remove();
            idx_list = idx_list.replace(id + ",", "");
        });
        $("#list_menu").val(idx_list);
    }
}

$(document).ready(function () {   
    var lmr = $("#list_menu_result").val();
    var spc = lmr.split(';');
    var i = 0;
    while(i < spc.length - 1)
    {
        if(i == spc.length + 1) break;        
        var sps = spc[i].split('|');
        if($("#ls_c" + sps[2]).length != 0) {
          addWithSet(sps[2], sps[0], sps[1], sps[3]); 
        }
        i++;
    }
    //$("#list_menu_result").remove();
});

// Load Data yang ada di Database
// function load_data_all()
// {
//     var ct = $("#list_menu").val();
//     var sp = ct.split(',');
//     for(i = 0; i < sp.length; i++)
//     {
//         if(sp[i] != "")
//         {
//             var id_menu = sp[i].split('|')[0] + '|' + sp[i].split('|')[1];
//             var isDefault = sp[i].split('|')[2];
//             $("#menu_makanan").append(' \
//             <div class="col-md-6" id="card' + idx + '"> \
//               <div class="card-menu"> \
//                 <div class="card-container"> \
//                   <table border="0" style="width: 100%"> \
//                     <tr> \
//                       <td>Menu :</td> \
//                       <td>Status :</td> \
//                       <td></td> \
//                     </tr> \
//                     <tr> \
//                       <td width="70%"> \
//                         <select class="form-control" name="menu' + idx + '" id="menu' + idx + '" data-placeholder="Pilih Makanan"> \
//                           ' + list_data_html + '\
//                         </select> \
//                       </td> \
//                       <td width="30%"> \
//                         <select class="form-control" name="status' + idx + '" id="status' + idx + '" data-placeholder="Pilih Status"> \
//                           <option value="1">Default</option> \
//                           <option value="0">Alternatif</option> \
//                         </select> \
//                       </td> \
//                       <td> \
//                         <input type="button" class="btn btn-danger btn-sm" value="X" onclick="cl_cr(' + idx + ')"> \
//                       </td> \
//                     </tr> \
//                 </table> \
//                 </div> \
//               </div> \
//             </div> \
//             ');
//             $("#card" + idx).prop('hidden', true);
//             $("#card" + idx).fadeIn('fast');
//             refSel(idx);            
//             idx_list += idx + ",";                        
//             $("#menu" + idx).val(id_menu).change();
//             $("#status" + idx).val(isDefault).change();
//             idx++;
//         }
//     }
//     $("#list_menu").val(idx_list);
// }


function add(id_categori)
{
    if($("#ls_c" + id_categori).length == 0) return;

    var list_data_html = "";
    var data_list = $("#ls_c" + id_categori).val();
    var sp_dl = data_list.split(';');
    for(i = 0; i < sp_dl.length - 1; i++)
    {        
        var sp_sperator = sp_dl[i].split('|');
        list_data_html += "<option value='" + sp_sperator[0] + "|" + sp_sperator[3] + "'>" + sp_sperator[1] + " (Rp. " + sp_sperator[2] + ")</option>";
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
                <select class="form-control" name="menu' + idx + '" id="menu' + idx + '" data-placeholder="Pilih Makanan"> \
                  ' + list_data_html + '\
                </select> \
              </td> \
              <td width="30%"> \
                <select class="form-control" name="status' + idx + '" id="status' + idx + '" data-placeholder="Pilih Status"> \
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
    idx++;    
}
function addWithSet(id_category, menu_id, price_menu, status)
{
  var idx_temp = idx;
  add(id_category);

  // Price Menu without Comma  
  $("#menu" + idx_temp).val(menu_id + "|" + price_menu).change();
  $("#status"+ idx_temp).val(status).change();
  
}