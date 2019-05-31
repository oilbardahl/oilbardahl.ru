function editTable(){ //Добавляем кнопку в админку SEND TO EVOTOR
     $(document).ready(
        function(){
            if($('.added-jquery').length == 0){
                // head
                $('table#tbl_sale_order thead tr').append('<td class="adm-list-table-cell added-jquery" style="min-width: 145px;text-align: center;">Action</td>');

                // body
                rrows = $("table#tbl_sale_order tr.adm-list-table-row");
                for(var i = 0; i < rrows.length; i++) {
                    rslinks = rrows[i]
                    arlinks = $(rslinks).find("td.adm-list-table-cell a");
                        for(var w = 0; w < arlinks.length; w++) {
                            if(arlinks[w].href.indexOf("sale_order_view.php?ID=") !== -1){
                                var id = arlinks[w].text.replace("№","");
                                id = id.replace(/\D/g,'');
                                $($(rrows[i]).append("<td class='adm-list-table-cell added-jquery'><a class='downloadtofile' onclick='ajax_export("+id+")' style='cursor:pointer;'>SEND TO EVOTOR</a></td>"));
                                break;
                            }
                        }
                }
                //$("form[name=form_tbl_sale_order]").attr("enctype", "multipart/form-data");
            }
        }
    );
}

function ajax_export(id){
        $.ajax({
            url: "/bitrix/admin/pavelbabich_kassa.php",
            data: {"ID_ORDER":id, "EXPORT":"Y"},
            dataType: 'html',
            success: function(response){alert(response);}
        });
    return false;
}

$(document).ready(
    function(){
        editTable();
        setInterval(editTable, 2000);
    }
);