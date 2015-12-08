function inventoryTransactions(user_token) {
    $("#inv_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#inv_trans_container").show();
    var today = new Date();
    var dd = 1;
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }

    var startDate = yyyy + '-' + mm + '-' + dd;
    var endDate;
    if (mm == 12) {
        endDate = (yyyy + 1) + '-01-' + dd;
    } else {
        mm = mm + 1;
        if (mm < 10) {
            mm = '0' + mm;
        }
        endDate = yyyy + '-' + mm + '-' + dd;
    }
    //var endDate  = yyyy + '-' + (mm + 1) +'-' + dd;
    var startDateTxt = document.getElementById("startDatePicker");
    var endDateTxt = document.getElementById("endDatePicker");
    startDateTxt.value = startDate;
    endDateTxt.value = endDate;
    $('#startDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#endDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    //$('#endDatePicker').datepicker("option", "dateFormat", "yy-mm-dd");
    $.get("/src/inv_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value, function (result) {
        var inventory = jQuery.parseJSON(result);
        if (inventory.count > 0) {
            $("#inv_trans_list").html('');
            jQuery.each(inventory.data, function (itemId, transType) {
                $("#inv_trans_list").append('<div id="inv_report_' + itemId + '" class="table-responsive"><h3 style="border-bottom: 1px solid #bbb; padding-bottom: 10px;">' + itemId + '</h3></div>');
                jQuery.each(transType, function (key, item) {
                    $("#inv_report_" + itemId + "").append('<h4>' + key + '</h4><table id="inv_rpt_tbl_' + itemId + "_" + key + '" class="table sortable"><thead><tr><th data-defaultsort="asc">Lot # / Reference</th><th>Qty</th><th>UOM</th><th>Date</th></tr></thead><tbody></tbody><tfoot></tfoot></table>');
                    jQuery.each(item, function (index, value) {
                        $("#inv_rpt_tbl_" + itemId + "_" + key + " tbody").append('<tr><td class="width_180">' + value.Lot + ' / ' + value.ReferenceId + '</td><td>' + value.Qty + '</td><td>' + value.UOM + '</td><td>' + value.Date + '</td></tr>');
                    });
                });
            });
            /*if(typeof inventory.total != "undefined") {
             $("#inv_trans_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
             }*/

            $.bootstrapSortable(false);
        } else {
            $("#inv_trans_list").html("No Inventory Transactions Available");
        }
    });
}

function refreshInvTransDates(user_token) {
    $("#inv_trans_list").html('');
    $("#inv_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#inv_trans_container").show();
    var startDateTxt = document.getElementById("startDatePicker");
    var endDateTxt = document.getElementById("endDatePicker");
    //startDateTxt.value = startDate;
    //endDateTxt.value = endDate;

    $.get("/src/inv_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value, function (result) {
        var inventory = jQuery.parseJSON(result);
        if (inventory.count > 0) {
            $("#inv_trans_list").html('');

            //$("#inv_trans_list").append('<p>Start Date: <input type="text" id="startDatePicker"></p> ');
            jQuery.each(inventory.data, function (itemId, transType) {
                $("#inv_trans_list").append('<div id="inv_report_' + itemId + '" class="table-responsive"><h3 style="border-bottom: 1px solid #bbb; padding-bottom: 10px;">' + itemId + '</h3></div>');
                jQuery.each(transType, function (key, item) {
                    $("#inv_report_" + itemId + "").append('<h4>' + key + '</h4><table id="inv_rpt_tbl_' + itemId + "_" + key + '" class="table sortable"><thead><tr><th data-defaultsort="asc">Lot # / Reference</th><th>Qty</th><th>UOM</th><th>Date</th></tr></thead><tbody></tbody><tfoot></tfoot></table>');
                     jQuery.each(item, function (index, value) {
                        $("#inv_rpt_tbl_" + itemId + "_" + key + " tbody").append('<tr><td class="width_180">' + value.Lot + ' / ' + value.ReferenceId + '</td><td>' + value.Qty + '</td><td>' + value.UOM + '</td><td>' + value.Date + '</td></tr>');
                    });
                });
            });
            /*if(typeof inventory.total != "undefined") {
             $("#inv_trans_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
             }*/

            $.bootstrapSortable(false);
        } else {
            $("#inv_trans_list").html("No Inventory Transactions Available");
        }
    });
}