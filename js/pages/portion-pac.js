function inventoryCheck(user_token, company_id) {
    $("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#part_number_container").hide();
    $("#inventory_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">FG Inv On-Hand</h1>');
    $.get("/src/inventory.php?act=list&user_token=" + user_token + "&company_id=" + company_id, function (result) {
        var inventory = jQuery.parseJSON(result);
        if (inventory.count > 0) {
            $("#inventory_list").html('<table class="table sortable"><thead><tr><th>Location</th><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th class="text_right">Case</th><th>Sell UOM</th><th class="text_right">Pallet</th><th class="width_180">Prod Date</th></tr></thead><tbody></tbody><tfoot></foot></table>');
            jQuery.each(inventory.data, function (i, inv) {
                $("#inventory_list tbody").append('<tr><td class="width_180">' + inv.Location + '</td><td class="width_120">' + inv.ItemId + '</td><td>' + inv.ItemName + '</td><td class="text_right">' + inv.AvailPhysical + '</td><td>' + inv.BOMUnitId + '</td><td class="text_right">' + inv.Case + '</td><td>' + inv.SellUOM + '</td><td class="text_right">' + inv.Pallet + '</td><td>' + inv.ProdDate + '</td></tr>');
            });
            if (typeof inventory.total != "undefined") {
                $("#inventory_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">' + inventory.total.quantity + '</td><td></td><td class="text_right">' + inventory.total.case + '</td><td></td><td class="text_right">' + inventory.total.pallet + '</td></tr>');
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
    $.get("/src/inv_quarantine.php?act=list&user_token=" + user_token + "&company_id=" + company_id, function (result) {
        var inventory = jQuery.parseJSON(result);
        if (inventory.count > 0) {
            $("#inv_quarantine_list").html('<table class="table sortable"><thead><tr><th>Location</th><th class="width_180">Item #</th><th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th class="text_right">Case</th><th>Sell UOM</th><th class="text_right">Pallet</th><th class="width_180">Prod Date</th></tr></thead><tbody></tbody><tfoot></foot></table>');
            jQuery.each(inventory.data, function (i, inv) {
                $("#inv_quarantine_list tbody").append('<tr><td class="width_180">' + inv.Location + '</td><td class="width_120">' + inv.ItemId + '</td><td>' + inv.ItemName + '</td><td class="text_right">' + inv.AvailPhysical + '</td><td>' + inv.BOMUnitId + '</td><td class="text_right">' + inv.Case + '</td><td>' + inv.SellUOM + '</td><td class="text_right">' + inv.Pallet + '</td><td>' + inv.ProdDate + '</td></tr>');
            });
            if (typeof inventory.total != "undefined") {
                $("#inv_quarantine_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">' + inventory.total.quantity + '</td><td></td><td class="text_right">' + inventory.total.case + '</td><td></td><td class="text_right">' + inventory.total.pallet + '</td></tr>');
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
    $.get("/src/packaging_inventory.php?act=list&user_token=" + user_token + "&company_id=" + company_id, function (result) {
        var inventory = jQuery.parseJSON(result);
        if (inventory.count > 0) {
            $("#packaging_inventory_list").html('<table class="table sortable"><thead><tr><th class="width_130">ID</th><th class="width_130">Name</th><th class="width_130">Quantity</th></th></tr></thead><tbody></tbody><tfoot></tfoot></table>');
            jQuery.each(inventory.data, function (i, inv) {
                if (typeof inv.ItemId != "undefined") {
                    $("#packaging_inventory_list tbody").append('<tr><td class="width_130">' + inv.ItemId + '</td><td class="width_350">' + inv.ItemName + '</td><td class="width_130">' + inv.Subtotal + '</td></td></tr>');
                }
            });
            $.bootstrapSortable(false);
        } else {
            $("#packaging_inventory_list").html("No Packaging Inventory Available");
        }
    });
}
function openPurchase(user_token, company_id) {
    $("#open_purchase_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#open_purchase_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">Open PO: Label/Pack</h1>');
    $.get("/src/open_purchase.php?act=list&user_token=" + user_token + "&company_id=" + company_id, function (result) {
        var open_purchase = jQuery.parseJSON(result);
        if (open_purchase.count > 0) {
            $("#open_purchase_list").html('<table class="table sortable"><thead><tr><th class="width_180" data-defaultsort="asc">Purchase Order</th><th class="width_100">Line #</th><th>Item Number</th><th>Description</th><th class="width_100">Delivery Date</th><th>Order Quantity</th><th>Deliver Remainder</th><th>Unit</th></tr></thead><tbody></tbody></table>');
            jQuery.each(open_purchase.data, function (p, purch) {
                $("#open_purchase_list tbody").append('<tr><td class="width_180">' + purch.PurchaseOrder + '</td><td class="width_100">' + purch.LineNumber + '</td><td>' + purch.ItemNumber + '</td><td>' + purch.Description + '</td><td>' + purch.DeliveryDate + '</td><td>' + purch.OrderQuantity + '</td><td>' + purch.DeliverRemainder + '</td><td>' + purch.Unit + '</td></tr>');
            });
            $.bootstrapSortable(false);
        } else {
            $("#open_purchase_list").html("No Open Purchase Available");
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
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Packing Slip #</th><th class="width_180">Ship Date</th><th class="width_180">Sale Order #</th><th class="width_180">Customer Ref</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//sw-fs-02/Shared/Docs-USP/SO/SO Shipping Paperwork/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ShipDate + '</td> <td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr></tbody><tfoot></tfoot></table>');
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
    $.get("/src/shipments.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value  + "&company_id=" + hold_company_id, function (result) {
        var shipments = jQuery.parseJSON(result);
        if (shipments.count > 0) {
            $("#shipments_list").html('');
            jQuery.each(shipments.data, function (x, psId) {
                $("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Packing Slip #</th><th class="width_180">Ship Date</th><th class="width_180">Sale Order #</th><th class="width_180">Customer Ref</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//sw-fs-02/Shared/Docs-USP/SO/SO Shipping Paperwork/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ShipDate + '</td> <td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr></tbody><tfoot></tfoot></table>');
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
                $("#recv_trans_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Product Receipt #</th><th class="width_180">Receipt Date</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//sw-fs-02/Shared/Docs-MAX/PO/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ReceiptDate + '</td> </tr></tbody><tfoot></tfoot></table>');
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
                $("#recv_trans_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId + '" class="table sortable"><thead><tr><th class="width_180">Product Receipt #</th><th class="width_180">Receipt Date</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//sw-fs-02/Shared/Docs-MAX/PO/' + x + '&user_token=' + user_token + '">' + x + '</a></td><td>' + psId.ReceiptDate + '</td> </tr></tbody><tfoot></tfoot></table>');
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