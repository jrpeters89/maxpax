var hold_company_id;

function inventoryCheck(user_token, company_id) {
    hold_company_id = company_id;
    $("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#inventory_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">On-Hand Inventory</h1>');
    $.get("/src/inventory.php?act=list&user_token="+user_token+"&company_id="+company_id,function(result) {
        var inventory = jQuery.parseJSON(result);
        if(inventory.count > 0) {
            $("#inventory_list").html('<table class="table sortable"><thead><tr><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th>Location</th></tr></thead><tbody></tbody></table>');
            jQuery.each( inventory.data, function( i, inv ) {
                $("#inventory_list tbody").append('<tr><td class="width_180">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td>'+inv.Location+'</td></tr>');
            });
            $.bootstrapSortable(false);
        } else {
            $("#inventory_list").html("No Inventory Available");
        }
    });
}