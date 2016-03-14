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
	$("#inv_quarantine_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#inv_quarantine_container").show();
	$(".col-xs-10").html('');
	$(".col-xs-10").html('<h1 class="page_header">FG Inv Quarantine</h1>');
	$.get("/src/inv_quarantine.php?act=list&user_token="+user_token+"&company_id="+company_id,function(result) {
		var inventory = jQuery.parseJSON(result);
		if(inventory.count > 0) {
			$("#inv_quarantine_list").html('<table class="table sortable"><thead><tr><th>Location</th><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th class="text_right">Case</th><th>Sell UOM</th><th class="text_right">Pallet</th><th class="width_180">Prod Date</th></tr></thead><tbody></tbody><tfoot></foot></table>');
			jQuery.each( inventory.data, function( i, inv ) {
				$("#inv_quarantine_list tbody").append('<tr><td class="width_180">' + inv.Location + '</td><td class="width_120">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td class="text_right">'+inv.Case+'</td><td>'+inv.SellUOM+'</td><td class="text_right">'+inv.Pallet+'</td><td>'+inv.ProdDate+'</td></tr>');
			});
			if(typeof inventory.total != "undefined") {
				$("#inv_quarantine_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
			}
			$.bootstrapSortable(false);
		} else {
			$("#inv_quarantine_list").html("No FG Inv Quarantine Available");
		}
	});
}

function packagingCheck(user_token, company_id) {
	$("#packaging_inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#packaging_inventory_container").show();
	$(".col-xs-10").html('');
	$(".col-xs-10").html('<h1 class="page_header">Packaging Inv On-Hand</h1>');
	$.get("/src/packaging_inventory.php?act=list&user_token="+user_token+"&company_id="+company_id,function(result) {
		var inventory = jQuery.parseJSON(result);
		if(inventory.count > 0) {
			$("#packaging_inventory_list").html('');
			jQuery.each( inventory.data, function( i, inv ) {
				$("#packaging_inventory_list").append('<div id="inventory_list_' + inv.ItemId +'" class="table-responsive"><table id="inventory_list_table_' + inv.ItemId +'" class="table sortable"><thead><tr><th class="width_100"></th><th class="width_100"></th><th class="width_180">Batch #</th><th class="width_100">Exp. Date</th><th class="width_100">Quantity</th><th class="width_100">UOM</th><th class="width_100">Location</th></tr><tr ><td class="width_100"><strong>' + inv.ItemGroupId + '</strong></td><td class="width_100"><strong>' + inv.ItemId + '</td><td class="width_180"><strong>' + inv.ItemName + '</strong></td><td class="width_100"></td><td class="width_100"></td><td class="width_100"></td><td class="width_100"></td></tr></thead><tbody></tbody><tfoot></tfoot></table></div>');
				jQuery.each(inv, function(x, item) {
					if (typeof item.BatchNumber != "undefined") {
						$("#inventory_list_" + inv.ItemId + " tbody").append('<tr><td class="width_100"></td><td class="width_100"></td><td class="width_180">' + item.BatchNumber + '</td><td class="width_100">' + item.expDate + '</td><td class="width_100">' + item.AvailPhysical + '</td><td class="width_100">' + item.BOMUnitId +'</td><td class="width_100">' + item.Location +'</td></tr>');
					}
				});
				$("#inventory_list_" + inv.ItemId + " tfoot").append('<tr><td class="width_100"></td><td class="width_100"></td><td class="width_180"></td><td class="width_100"><strong>Subtotal</strong></td><td class="width_100"><strong>' + inv.Subtotal + '</strong></td><td class="width_100"></td><td class="width_100"></td></tr>');

			});
			$.bootstrapSortable(false);
		} else {
			$("#packaging_inventory_list").html("No Packaging Inventory Available");
		}
	});
}
