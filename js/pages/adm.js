function inventoryTransactions(user_token) {
    $("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#inventory_container").show();
    $.get("/src/inv_trans.php?act=list&user_token="+user_token,function(result) {
        var inventory = jQuery.parseJSON(result);
        if(inventory.count > 0) {
            $("#inventory_list").html('<table class="table sortable"><thead><tr><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th class="text_right">Case</th><th>Sell UOM</th><th class="text_right">Pallet</th></tr></thead><tbody></tbody><tfoot></foot></table>');
            jQuery.each( inventory.data, function( i, inv ) {
                $("#inventory_list tbody").append('<tr><td class="width_180">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td class="text_right">'+inv.Case+'</td><td>'+inv.SellUOM+'</td><td class="text_right">'+inv.Pallet+'</td></tr>');
            });
            if(typeof inventory.total != "undefined") {
                $("#inventory_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
            }
            $.bootstrapSortable(false);
        } else {
            $("#inventory_list").html("No Inventory Available");
        }
    });
}