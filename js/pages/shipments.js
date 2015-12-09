function shippingData(user_token) {
    $("#shipments_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#shipments_container").show();
    $.get("/src/shipments.php?act=list&user_token=" + user_token, function (result) {
        var shipments = jQuery.parseJSON(result);
        if (shipments.count > 0) {
            $("#shipments_list").html('');
            //$("#shipments_list").html('<table class="table sortable"><thead><tr><th>Packing Slip #</tr></thead><tbody></tbody><tfoot></foot></table>');
            //$("#shipments_list").html('<table class="table sortable"><thead><tr><th class="text_right">Packing Slip #</th><th>Ship Date</th><th>Item #</th><th>Description</th><th class="text_right">Sales Order #</th><th class="text_right">Customer Ref</th></tr></thead><tbody></tbody><tfoot></foot></table>');
            jQuery.each(shipments.data, function (x, psId) {
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Packing Slip #</th><th>Ship Date</th><th>Item #</th><th>Description</th><th>Sale Order #</th><th>Customer Ref</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/file.php?loc=//safewayfs02/Shared/Docs-USP/QC/QCDS/ForGPBShipments/' + x +'.pdf&user_token =' + user_token +'" download>' + x + '</a></td><td>' + psId.ShipDate + '</td> <td>' + psId.Item + '</td><td>' + psId.Description + '</td><td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr></tbody><tfoot></tfoot></table>');
                //$("slip_id_table_" + psId.PackingSlipId + "tbody").append('<tr><td>' + x + '</td><td>' + psId.ShipDate + '</td> <td>' + psId.Item + '</td><td>' + psId.Description + '</td><td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr>');
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '_lot" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'_lot" class="table sortable"><thead><tr><th>Lot #</th><th>Exp. Date</th><th>Delivered</th><th>UOM</th></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
                jQuery.each(psId, function (z, det) {
                    $("#slip_id_" + psId.PackingSlipId + "_lot tbody").append('<tr><td>' + det.Lot + '</td><td>' + det.ExpirationDate + '</td><td>' + det.Delivered + '</td><td>' + det.UOM + '</td></tr>');
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
