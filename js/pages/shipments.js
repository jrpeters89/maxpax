function shippingData(user_token) {
    $("#shipments_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#shipments_container").show();
    $.get("/src/shipments.php?act=list&user_token=" + user_token, function(result) {
        var shipments = jQuery.parseJSON(result);
        if (shipments.count > 0) {
            $("#shipments_list").html('<table class="table sortable"><thead><tr><th>Packing Slip #</tr></thead><tbody></tbody><tfoot></foot></table>');
            //$("#shipments_list").html('<table class="table sortable"><thead><tr><th class="text_right">Packing Slip #</th><th>Ship Date</th><th>Item #</th><th>Description</th><th class="text_right">Sales Order #</th><th class="text_right">Customer Ref</th></tr></thead><tbody></tbody><tfoot></foot></table>');
            jQuery.each(shipments.data, function(i, ship) {
							jQuery.each(ship, function(i, psId) {
                $("#shipments_list tbody").append('<tr><td>' + psId.PackingSlipId + '</td></tr>');
                jQuery.each(psId, function(z, det) {
                    $("#shipments_list tfoot").append('<tr><td>' + det.ShipDate + '</td><td class="text_right">' + det.Item + '</td><td class="text_right">' + det.Description + '</td><td>' + det.SalesOrder + '</td><td class="text_right">' + det.CustomerRef + '</td></tr>');
                });
            });
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
