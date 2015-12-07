function inventoryTransactions(user_token) {
    $("#inv_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#inv_trans_container").show();
    $.get("/src/inv_trans.php?act=list&user_token="+user_token,function(result) {
        var inventory = jQuery.parseJSON(result);
        if(inventory.count > 0) {
            $("#inv_trans_list").html('');
            //$("#inv_trans_list").append('<link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">');
            //$("#inv_trans_list").append('<script src="http://code.jquery.com/jquery-1.10.2.js"></script>');
            //$("#inv_trans_list").append('<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>');
            //$("#inv_trans_list").append('<script>$(function() { $("#datepicker").datepicker();});</script>');
            $("#inv_trans_list").append('<p>Start Date: <input type="text" id="startDatePicker"></p> ');
            jQuery.each( inventory.data, function( t, inv ) {
                $("#inv_trans_list").append('<div id="inv_report'+t+'" class="table-responsive"><h3 style="border-bottom: 1px solid #bbb; padding-bottom: 10px;">'+t+'</h3></div>');
                /*var cur_trans = 0;*/
                jQuery.each(inv, function (i, item) {
                    $("#inv_report"+t+"").append('<h4>'+i+'</h4><table id="inv_rpt_tbl_'+i+'" class="table sortable"><thead><tr><th data-defaultsort="asc">Lot # / Reference</th><th>Qty</th><th>UOM</th></tr></thead><tbody></tbody><tfoot></tfoot></table>');
                    jQuery.each(item, function (j, jtem) {
                        $("#inv_rpt_tbl_"+i+" tbody").append('<tr><td class="width_180">' + jtem.Lot + ' / ' +jtem.ReferenceId+'</td><td>'+jtem.Qty+'</td><td>'+jtem.UOM+'</td><td>'+jtem.Date+'</td></tr>');
                    });
                });
                /*cur_trans++;*/

            });
            /*if(typeof inventory.total != "undefined") {
                $("#inv_trans_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
            }*/
            $('#startDatePicker').datepicker()
                .on('changeDate', function (ev) {

                });
            $.bootstrapSortable(false);
        } else {
            $("#inv_trans_list").html("No Inventory Transactions Available");
        }
    });
}