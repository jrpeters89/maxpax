function inventoryCheck(user_token, company_id) {
	$("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#inventory_container").show();
	$(".col-xs-10").html('');
	$(".col-xs-10").html('<h1 class="page_header">FG Inv On-Hand</h1>');
	$.get("/src/inventory.php?act=list&user_token="+user_token+"&company_id="+company_id,function(result) {
		var inventory = jQuery.parseJSON(result);
		if(inventory.count > 0) {
			$("#inventory_list").html('<table class="table sortable"><thead><tr><th>Location</th><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th class="text_right">Case</th><th>Sell UOM</th><th class="text_right">Pallet</th><th class="width_180">Prod Date</th></tr></thead><tbody></tbody><tfoot></foot></table>');
			jQuery.each( inventory.data, function( i, inv ) {
				$("#inventory_list tbody").append('<tr><td class="width_180">' + inv.Location + '</td><td class="width_120">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td class="text_right">'+inv.Case+'</td><td>'+inv.SellUOM+'</td><td class="text_right">'+inv.Pallet+'</td><td>'+inv.ProdDate+'</td></tr>');
			});
			if(typeof inventory.total != "undefined") {
				$("#inventory_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
			}
			$.bootstrapSortable(false);
		} else {
			$("#inventory_list").html("No FG Inv On-hand Available");
		}
	});
}

function invQuarantineCheck(user_token, company_id) {
	$("#inv_quarantine_list_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#inv_quarantine_container_container").show();
	/*$(".col-xs-10").html('');
	$(".col-xs-10").html('<h1 class="page_header">FG Inv On-Hand</h1>');*/
	$.get("/src/inv_quarantine.php?act=list&user_token="+user_token+"&company_id="+company_id,function(result) {
		var inventory = jQuery.parseJSON(result);
		if(inventory.count > 0) {
			$("#inv_quarantine_list_list").html('<table class="table sortable"><thead><tr><th>Location</th><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th class="text_right">Case</th><th>Sell UOM</th><th class="text_right">Pallet</th><th class="width_180">Prod Date</th></tr></thead><tbody></tbody><tfoot></foot></table>');
			jQuery.each( inventory.data, function( i, inv ) {
				$("#inv_quarantine_list_list tbody").append('<tr><td class="width_180">' + inv.Location + '</td><td class="width_120">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td class="text_right">'+inv.Case+'</td><td>'+inv.SellUOM+'</td><td class="text_right">'+inv.Pallet+'</td><td>'+inv.ProdDate+'</td></tr>');
			});
			if(typeof inventory.total != "undefined") {
				$("#inv_quarantine_list_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
			}
			$.bootstrapSortable(false);
		} else {
			$("#inv_quarantine_list_list").html("No FG Inv Quarantine Available");
		}
	});
}
