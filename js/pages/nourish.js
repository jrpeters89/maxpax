function inventoryCheck(user_token) {
	$("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#inventory_container").show();
	$.get("/src/inventory.php?act=list&user_token="+user_token,function(result) {
		var inventory = jQuery.parseJSON(result);
		if(inventory.count > 0) {
			$("#inventory_list").html('<table class="table sortable"><thead><tr><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th>Case</th><th>Sell UOM</th><th>Pallet</th><th>Location</th></tr></thead><tbody></tbody></table>');
			jQuery.each( inventory.data, function( i, inv ) {
				$("#inventory_list tbody").append('<tr><td class="width_180">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td>'+inv.Class+'</td><td>'+inv.SellUOM+'</td><td>'+inv.Pallet+'</td><td>'+inv.Location+'</td></tr>');
			});
			$.bootstrapSortable(false);
		} else {
			$("#inventory_list").html("No Inventory Available");
		}
	});
}

function productionCheck(user_token) {
	$("#production_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#production_container").show();
	$.get("/src/production.php?act=list&user_token="+user_token,function(result) {
		var production = jQuery.parseJSON(result);
		if(production.count > 0) {
			$("#production_list").html('<table class="table sortable"><thead><tr><th data-defaultsort="asc">Item #</th><th>Name</th><th>Prod ID</th><th>Status</th><th class="text_right">Quantity</th><th class="text_right">Started</th><th class="text_right">Remainder</th></tr></thead><tbody></tbody></table>');
			jQuery.each( production.data, function( i, prod ) {
				$("#production_list tbody").append('<tr><td>'+prod.ItemId+'</td><td>'+prod.Name+'</td><td>'+prod.ProdId+'</td><td>'+prod.ProdStatus+'</td><td class="text_right">'+prod.Scheduled+'</td><td class="text_right">'+prod.Started+'</td><td class="text_right">'+prod.Remainder+'</td></tr>');
			});
			$.bootstrapSortable(false);
		} else {
			$("#production_list").html("No Inventory Available");
		}
	});
}

$("body").on("click",".opensales_tab",function(event) {
	event.preventDefault();
	var type = $(this).data("tab");
	$("#opensales_tabs li").removeClass("active");
	$("#tab_"+type).addClass("active");
	opensalesCheck(user_token,type);
});

function opensalesCheck(user_token,type) {
	type = (typeof type !== "undefined" ? type : "list");
	$("#opensales_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#opensales_container").show();
	$.get("/src/open_sales.php?type="+type+"&user_token="+user_token,function(result) {
		var sales = jQuery.parseJSON(result);
		if(sales.count > 0) {
			$("#opensales_list").html("");
			if(type == "items") {
				var count = 0;
				jQuery.each( sales.data, function( i, sale ) {
					$("#opensales_list").append('<table id="item_'+count+'" class="table"><thead><tr><th>Item #</th><th colspan="7">Description</th></tr><tr><th>'+i+'</th><th colspan="7">'+sale.description+'</th></tr></thead><tbody><tr><td>Cust Ref</td><td>Sales Order</td><td>Unit</td><td class="text_right">Quantity</td><td class="text_right">Shipped</td><td class="text_right">Remainder</td><td>Delivery Address</td></tr></tbody></table>');
					jQuery.each( sale.data, function( s, item ) {
						$("#item_"+count+" tbody").append('<tr><td>'+item.CustomerRef+'</td><td>'+item.SalesId+'</td><td>'+item.SalesUnit+'</td><td class="text_right">'+item.SalesQty+'</td><td class="text_right">'+item.Shipped+'</td><td class="text_right">'+item.Remainder+'</td><td class="width_170">'+item.DeliveryAddress+'</td></tr>');
					});
					count++;
				});
			} else {
				$("#opensales_list").html('<table class="table sortable"><thead><tr><th data-defaultsort="asc">Cust Ref</th><th class="width_120">Sales Order</th><th>Item #</th><th>Description</th><th>Unit</th><th class="text_right">Quantity</th><th class="text_right">Shipped</th><th class="text_right">Remainder</th></tr></thead><tbody></tbody></table>');
				jQuery.each( sales.data, function( i, sale ) {
					$("#opensales_list tbody").append('<tr><td>'+sale.CustomerRef+'</td><td class="width_120">'+sale.SalesId+'</td><td>'+sale.ItemId+'</td><td>'+sale.ItemName+'</td><td>'+sale.SalesUnit+'</td><td class="text_right">'+sale.SalesQty+'</td><td class="text_right">'+sale.Shipped+'</td><td class="text_right">'+sale.Remainder+'</td></tr>');
				});
				$.bootstrapSortable(false);
			}
		} else {
			$("#opensales_list").html("No Inventory Available");
		}
	});
}
