function shippingData(user_token) {
	$("#shipments_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#shipments_container").show();
	$.get("/src/shipments.php?act=list&user_token="+user_token,function(result) {
		var shipments = jQuery.parseJSON(result);
		if(shipments.count > 0) {
			$("#shipments_list").html('<table class="table sortable"><thead><tr><th>Item</th><th class="text_right">Description</th><th class="text_right">Lot</th><th>BBD</th><th class="text_right">Qty</th><th class="text_right">USP Order #</th><th class="text_right">Customer Order #</th></tr></thead><tbody></tbody><tfoot></foot></table>');
			jQuery.each( shipments.data, function( i, ship ) {
				$("#shipments_list tbody").append('<tr><td>'+ship.Item+'</td><td class="text_right">'+ship.Description+'</td><td>'+ship.BatchNumber+'</td><td class="text_right">'+ship.ExpirationDate+'</td><td>'+ship.Quantity+'</td><td class="text_right">'+ship.SalesOrder+'</td><td class="text_right">'+ship.CustomerRef+'</td></tr>');
			});
			// if(typeof shipments.total != "undefined") {
			// 	$("#inventory_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
			// }
			$.bootstrapSortable(false);
		} else {
			$("#shipments_list").html("No Shipments Available");
		}
	});
}
