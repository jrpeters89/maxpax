function inventoryCheck(user_token) {
	$("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#inventory_container").show();
	$.get("/src/inventory.php?act=list&user_token="+user_token,function(result) {
		var inventory = jQuery.parseJSON(result);
		if(inventory.count > 0) {
			$("#inventory_list").html('<table class="table sortable"><thead><tr><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th>Case</th><th>Sell UOM</th><th>Pallet</th><th>Location</th></tr></thead><tbody></tbody><tfoot></foot></table>');
			jQuery.each( inventory.data, function( i, inv ) {
				$("#inventory_list tbody").append('<tr><td class="width_180">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td>'+inv.Case+'</td><td>'+inv.SellUOM+'</td><td>'+inv.Pallet+'</td><td>'+inv.Location+'</td><td></td></tr>');
			});
			if(typeof inventory.total != "undefined") {
				$("#inventory_list tfoot").append('<tr><td colspan="2">TOTAL</td><td>'+inventory.total.quantity+'</td><td></td><td>'+inventory.total.case+'</td><td></td><td>'+inventory.total.pallet+'</td></tr>');
			}
			$.bootstrapSortable(false);
		} else {
			$("#inventory_list").html("No Inventory Available");
		}
	});
}
