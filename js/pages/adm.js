function inventoryTransactions(user_token) {
    $("#inv_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#inv_trans_container").show();
    $.get("/src/inv_trans.php?act=list&user_token="+user_token,function(result) {
        var inventory = jQuery.parseJSON(result);
        if(inventory.count > 0) {
            $("#inv_trans_list").html('<table class="table sortable"><thead><tr><th class="width_180">Company ID</th><th>Date Physical</th><th class="text_right">Invent Trans Ref Id</th><th>Invent Trans Type</th><th class="text_right">Invent Unit Id</th><th>Item Id</th><th class="text_right">Item Name</th><th>Qty</th></tr></thead><tbody></tbody><tfoot></foot></table>');
            jQuery.each( inventory.data, function( t, inv ) {
                $("#inv_trans_list tbody").append('<tr><td class="width_180">'+t+'</td></tr>');
                jQuery.each(inv, function (i, item) {
                    $("#inv_trans_list tbody").append('<tr><td class="width_180">'+i+'</td></tr>');
                    jQuery.each(item, function (j, jtem) {
                        $("#inv_trans_list tbody").append('<tr><td class="width_180">'+jtem.ReferenceId+'</td><td>jtem.Qty</td><td>jtem.UOM</td></tr>');
                    });
                });

            });
            /*if(typeof inventory.total != "undefined") {
                $("#inv_trans_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
            }*/
            $.bootstrapSortable(false);
        } else {
            $("#inv_trans_list").html("No Inventory Transactions Available");
        }
    });
}