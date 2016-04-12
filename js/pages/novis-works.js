var hold_company_id;

function inventoryCheck(user_token, company_id) {
    $("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#inventory_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">On-Hand Inventory</h1>');
    $.get("/src/inventory.php?act=list&user_token="+user_token+"&company_id="+company_id,function(result) {
        var inventory = jQuery.parseJSON(result);
        if(inventory.count > 0) {
            $("#inventory_list").html('<table class="table sortable"><thead><tr><th class="width_100" data-defaultsort="asc">Class</th><th class="width_180">Item #</th><th>Product Name</th><th>Batch #</th><th class="width_100">Exp. Date</th><th class="text_right">Quantity</th><th>UOM</th><th>Location</th></tr></thead><tbody></tbody></table>');
            jQuery.each( inventory.data, function( i, inv ) {
                $("#inventory_list tbody").append('<tr><td class="width_100">'+inv.ItemGroupId+'</td><td class="width_180">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td>'+inv.BatchNumber+'</td><td>'+inv.expDate+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td>'+inv.Location+'</td></tr>');
            });
            $.bootstrapSortable(false);
        } else {
            $("#inventory_list").html("No Inventory Available");
        }
    });
}

function shippingData(user_token, company_id) {
    hold_company_id = company_id;
    $("#shipments_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#shipments_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">Shipments</h1>');
    var today = new Date();
    var startDay = 1;
    var startMonth = today.getMonth() + 1;
    var startYear = today.getFullYear();
    if (startDay < 10) {
        startDay = '0' + startDay;
    }
    if (startMonth < 10) {
        startMonth = '0' + startMonth;
    }
    var startDate = startYear + '-' + startMonth + '-' + startDay;

    var endDate;
    var endMonth;
    endMonth = today.getMonth() + 2;
    if (endMonth == 13) {
        endDate = (startYear + 1) + '-01-' + startDay;
    } else {

        if (endMonth < 10) {
            endMonth = '0' + endMonth;
        }
        endDate = startYear + '-' + endMonth + '-' + startDay;
    }
    //var endDate  = yyyy + '-' + (mm + 1) +'-' + dd;
    var startDateTxt = document.getElementById("shipStartDatePicker");
    var endDateTxt = document.getElementById("shipEndDatePicker");
    startDateTxt.value = startDate;
    endDateTxt.value = endDate;
    $('#shipStartDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#shipEndDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $.get("/src/shipments.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + company_id, function (result) {
        var shipments = jQuery.parseJSON(result);
        if (shipments.count > 0) {
            $("#shipments_list").html('');
            jQuery.each(shipments.data, function (x, psId) {
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Packing Slip #</th><th class="width_180">Ship Date</th><th class="width_180">Sale Order #</th><th class="width_180">Customer Ref</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//safewayfs02/Shared/Docs-USP/SO/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ShipDate + '</td> <td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr></tbody><tfoot></tfoot></table>');
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '_lot" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'_lot" class="table sortable"><thead><tr><th class="width_180">Item # / Lot #</th><th class="width_180"> Description / Exp. Date</th><th class="width_180">Delivered</th><th class="width_180">UOM</th></tr><tr><td><strong>' + psId.Item + '</strong></td><td><strong>' + psId.Description + '</strong></td></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
                jQuery.each(psId, function (z, det) {
                    if(typeof det.Lot != "undefined") {
                        $("#slip_id_" + psId.PackingSlipId + "_lot tbody").append('<tr><td>' + det.Lot + '</td><td>' + det.ExpirationDate + '</td><td>' + det.Delivered + '</td><td>' + det.UOM + '</td></tr>');
                    }
                });
                $("#slip_id_" + psId.PackingSlipId + "_lot tfoot").append('<tr><td></td><td><strong>Subtotal</strong></td><td><strong>' + psId.Subtotal + '</strong></td><td></td></tr>');

            });
            $.bootstrapSortable(false);
        } else {
            $("#shipments_list").html("No Shipments Available");
        }
    });
}
function refreshShipmentDates(user_token) {
    $("#shipments_list").html('');
    $("#shipments_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#shipments_container").show();
    var startDateTxt = document.getElementById("shipStartDatePicker");
    var endDateTxt = document.getElementById("shipEndDatePicker");
    $.get("/src/shipments.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + hold_company_id, function (result) {
        var shipments = jQuery.parseJSON(result);
        if (shipments.count > 0) {
            $("#shipments_list").html('');
            jQuery.each(shipments.data, function (x, psId) {
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Packing Slip #</th><th class="width_180">Ship Date</th><th class="width_180">Sale Order #</th><th class="width_180">Customer Ref</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//safewayfs02/Shared/Docs-USP/SO/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ShipDate + '</td> <td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr></tbody><tfoot></tfoot></table>');
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '_lot" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'_lot" class="table sortable"><thead><tr><th class="width_180">Item # / Lot #</th><th class="width_180"> Description / Exp. Date</th><th class="width_180">Delivered</th><th class="width_180">UOM</th></tr><tr><td><strong>' + psId.Item + '</strong></td><td><strong>' + psId.Description + '</strong></td></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
                jQuery.each(psId, function (z, det) {
                    if(typeof det.Lot != "undefined") {
                        $("#slip_id_" + psId.PackingSlipId + "_lot tbody").append('<tr><td>' + det.Lot + '</td><td>' + det.ExpirationDate + '</td><td>' + det.Delivered + '</td><td>' + det.UOM + '</td></tr>');
                    }
                });
                $("#slip_id_" + psId.PackingSlipId + "_lot tfoot").append('<tr><td></td><td><strong>Subtotal</strong></td><td><strong>' + psId.Subtotal + '</strong></td><td></td></tr>');

            });
            $.bootstrapSortable(false);
        } else {
            $("#shipments_list").html("No Shipments Available");
        }
    });
}

function receivingTransactions(user_token, company_id) {
    hold_company_id = company_id;
    $("#recv_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#recv_trans_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">Product Receipts</h1>');
    var today = new Date();
    var startDay = 1;
    var startMonth = today.getMonth() + 1;
    var startYear = today.getFullYear();
    if (startDay < 10) {
        startDay = '0' + startDay;
    }
    if (startMonth < 10) {
        startMonth = '0' + startMonth;
    }
    var startDate = startYear + '-' + startMonth + '-' + startDay;

    var endDate;
    var endMonth;
    endMonth = today.getMonth() + 2;
    if (endMonth == 13) {
        endDate = (startYear + 1) + '-01-' + startDay;
    } else {

        if (endMonth < 10) {
            endMonth = '0' + endMonth;
        }
        endDate = startYear + '-' + endMonth + '-' + startDay;
    }
    //var endDate  = yyyy + '-' + (mm + 1) +'-' + dd;
    var startDateTxt = document.getElementById("recvStartDatePicker");
    var endDateTxt = document.getElementById("recvEndDatePicker");
    startDateTxt.value = startDate;
    endDateTxt.value = endDate;
    $('#recvStartDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#recvEndDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $.get("/src/recv_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + company_id, function (result) {
        var receipt = jQuery.parseJSON(result);
        if (receipt.count > 0) {
            $("#recv_trans_list").html('');
            jQuery.each(receipt.data, function (x, psId) {
                $("#recv_trans_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Product Receipt #</th><th class="width_180">Receipt Date</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//safewayfs02/Shared/Docs-USP/PO/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ReceiptDate + '</td> </tr></tbody><tfoot></tfoot></table>');
                $("#recv_trans_list").append('<div id="slip_id_' + psId.PackingSlipId + '_lot" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'_lot" class="table sortable"><thead><tr><th class="width_150">Purchase Order #</th><th class="width_120">Line #</th><th class="width_180">Item #</th><th class="width_180"> Description</th><th class="width_120">Received</th><th class="width_120">Date</th><th class="width_150">Lot #</th><th class="width_150">Quantity</th></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
                jQuery.each(psId, function (z, det) {
                    if(typeof det.Lot != "undefined") {
                        $("#slip_id_" + psId.PackingSlipId + "_lot tbody").append('<tr><td>' + det.PurchaseOrder + '</td><td>' + det.LineNumber + '</td><td>' + det.ItemNumber + '</td><td>' + det.Description + '</td><td>' + det.Received + '</td><td>' + det.Date + '</td><td>' + det.Lot + '</td><td>' + det.Quantity + '</td></tr>');
                    }
                });

            });
            $.bootstrapSortable(false);
        } else {
            $("#recv_trans_list").html("No Receiving Transactions Available");
        }
    });
}
function refreshRecvTransDates(user_token) {
    $("#recv_trans_list").html('');
    $("#recv_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#recv_trans_container").show();
    var startDateTxt = document.getElementById("recvStartDatePicker");
    var endDateTxt = document.getElementById("recvEndDatePicker");
    $.get("/src/recv_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + hold_company_id, function (result) {
        var receipt = jQuery.parseJSON(result);
        if (receipt.count > 0) {
            $("#recv_trans_list").html('');
            jQuery.each(receipt.data, function (x, psId) {
                $("#recv_trans_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId + '" class="table sortable"><thead><tr><th class="width_180">Product Receipt #</th><th class="width_180">Receipt Date</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//safewayfs02/Shared/Docs-USP/PO/' + x + '&user_token=' + user_token + '">' + x + '</a></td><td>' + psId.ReceiptDate + '</td> </tr></tbody><tfoot></tfoot></table>');
                $("#recv_trans_list").append('<div id="slip_id_' + psId.PackingSlipId + '_lot" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId + '_lot" class="table sortable"><thead><tr><th class="width_150">Purchase Order #</th><th class="width_120">Line #</th><th class="width_180">Item #</th><th class="width_180"> Description</th><th class="width_120">Received</th><th class="width_120">Date</th><th class="width_150">Lot #</th><th class="width_150">Quantity</th></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
                jQuery.each(psId, function (z, det) {
                    if (typeof det.Lot != "undefined") {
                        $("#slip_id_" + psId.PackingSlipId + "_lot tbody").append('<tr><td>' + det.PurchaseOrder + '</td><td>' + det.LineNumber + '</td><td>' + det.ItemNumber + '</td><td>' + det.Description + '</td><td>' + det.Received + '</td><td>' + det.Date + '</td><td>' + det.Lot + '</td><td>' + det.Quantity + '</td></tr>');
                    }
                });

            });
            $.bootstrapSortable(false);
        } else {
            $("#recv_trans_list").html("No Receiving Transactions Available");
        }
    });
}