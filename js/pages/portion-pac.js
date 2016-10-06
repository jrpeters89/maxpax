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
			$("#packaging_inventory_list").html('<table class="table sortable"><thead><tr><th class="width_130">ID</th><th class="width_130">Name</th><th class="width_130">Quantity</th></th></tr></thead><tbody></tbody><tfoot></tfoot></table>');
			jQuery.each( inventory.data, function( i, inv ) {
				if(typeof inv.ItemId != "undefined") {
					$("#packaging_inventory_list tbody").append('<tr><td class="width_130">' + inv.ItemId + '</td><td class="width_350">' + inv.ItemName + '</td><td class="width_130">' + inv.Subtotal + '</td></td></tr>');
				}
			});
			$.bootstrapSortable(false);
		} else {
			$("#packaging_inventory_list").html("No Packaging Inventory Available");
		}
	});

	function openPurchase(user_token, company_id) {
		$("#open_purchase_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
		$("#open_purchase_container").show();
		$(".col-xs-10").html('');
		$(".col-xs-10").html('<h1 class="page_header">Open Purchase</h1>');
		$.get("/src/open_purchase.php?act=list&user_token="+user_token+"&company_id="+company_id,function(result) {
			var open_purchase = jQuery.parseJSON(result);
			if(open_purchase.count > 0) {
				$("#open_purchase_list").html('<table class="table sortable"><thead><tr><th class="width_180" data-defaultsort="asc">Purchase Order</th><th class="width_100">Line #</th><th>Item Number</th><th>Description</th><th class="width_100">Delivery Date</th><th>Order Quantity</th><th>Deliver Remainder</th><th>Unit</th></tr></thead><tbody></tbody></table>');
				jQuery.each( open_purchase.data, function( p, purch ) {
					$("#open_purchase_list tbody").append('<tr><td class="width_180">'+purch.PurchaseOrder+'</td><td class="width_100">'+purch.LineNumber+'</td><td>'+purch.ItemNumber+'</td><td>'+purch.Description+'</td><td>'+purch.DeliveryDate+'</td><td>'+purch.OrderQuantity+'</td><td>'+purch.DeliverRemainder+'</td><td>'+purch.Unit+'</td></tr>');
				});
				$.bootstrapSortable(false);
			} else {
				$("#open_purchase_list").html("No Open Purchase Available");
			}
		});
	}
}
