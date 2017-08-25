var hold_company_id;
var hold_user_token;

function receiving(user_token, company_id) {
    hold_company_id = company_id;
    hold_user_token = user_token;

    $('#edit_transaction_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $("#receiving_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#receiving_container").show();
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
    var startDateTxt = document.getElementById("receivingStartDatePicker");
    var endDateTxt = document.getElementById("receivingEndDatePicker");
    startDateTxt.value = startDate;
    endDateTxt.value = endDate;
    $('#receivingStartDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#receivingEndDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $.get("/src/receiving.php?user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + company_id, function (result) {
        var receiving = jQuery.parseJSON(result);
        console.log(receiving);
        if (receiving.count > 0) {
            $("#receiving_list").html('');
            $("#receiving_list").append('<div id="receiving_data" class="table-responsive"><table id="receiving_data_table" class="table sortable"><thead><tr><th class="width_150">Receipt Date</th><th class="width_150">Batch #</th><th class="width_150">Item</th><th class="width_150">Packing Slip</th><th class="width_150">Description</th><th class="width_150">Quantity</th> <th class="width_150">Purchase Order</th><th class="width_150">Line #</th><th class="width_150">Vendor Account</th> </tr></thead><tbody></tbody><tfoot></tfoot></table> </div>')
            jQuery.each(receiving.data, function(x, data) {
                $("#receiving_data tbody").append('<tr><td>' + data.receipt_date +'</td><td><a href="' + data.batch_number + '" id="' + data.batch_number + '" class="edit_receiving" data-item="' + data.item + '" data-packing_slip_id="' + data.packing_slip_id + '" data-description="' + data.description + '" data-quantity="' + data.quantity + '" data-purchase_order="' + data.purchase_order + '" data-line_number="' + data.line_number + '" data-vendor_account="' + data.vendor_account + '" >' + data.batch_number + '</a></td><td>' + data.item + '</td><td>' + data.packing_slip_id + '</td><td>' + data.description + '</td><td>' + data.quantity + '</td><td>' + data.purchase_order +'</td><td>' + data.line_number +'</td><td>' + data.vendor_account + '</td></tr>');
            });
        } else {
            $("#receiving_list").html("No Records Available");
        }
    });
}

function refreshReceivingDates (user_token) {
    $("#receiving_list").html('');
    $("#receiving_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#receiving_container").show();
    var startDateTxt = document.getElementById("receivingStartDatePicker");
    var endDateTxt = document.getElementById("receivingEndDatePicker");
    $.get("/src/receiving.php?user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + company_id, function (result) {
        var receiving = jQuery.parseJSON(result);
        console.log(receiving);
        if (receiving.count > 0) {
            $("#receiving_list").html('');
            $("#receiving_list").append('<div id="receiving_data" class="table-responsive"><table id="receiving_data_table" class="table sortable"><thead><tr><th class="width_150">Receipt Date</th><th class="width_150">Batch #</th><th class="width_150">Item</th><th class="width_150">Packing Slip</th><th class="width_150">Description</th><th class="width_150">Quantity</th> <th class="width_150">Purchase Order</th><th class="width_150">Line #</th><th class="width_150">Vendor Account</th> </tr></thead><tbody></tbody><tfoot></tfoot></table> </div>')
            jQuery.each(receiving.data, function(x, data) {
                $("#receiving_data tbody").append('<tr><td>' + data.receipt_date +'</td><td><a href="' + data.batch_number + '" id="' + data.batch_number + '" class="edit_receiving" data-item="' + data.item + '" data-packing_slip_id="' + data.packing_slip_id + '" data-description="' + data.description + '" data-quantity="' + data.quantity + '" data-purchase_order="' + data.purchase_order + '" data-line_number="' + data.line_number + '" data-vendor_account="' + data.vendor_account + '" >' + data.batch_number + '</a></td><td>' + data.item + '</td><td>' + data.packing_slip_id + '</td><td>' + data.description + '</td><td>' + data.quantity + '</td><td>' + data.purchase_order +'</td><td>' + data.line_number +'</td><td>' + data.vendor_account + '</td></tr>');
            });
        } else {
            $("#receiving_list").html("No Records Available");
        }
    });
}

$("body").on("click", ".edit_receiving", function(event) {
    event.preventDefault();
    $("#EditReceivingBatchNumber").val($(this).data('batch_number'));
    console.log($(this).data('batch_number'));
    $("#EditReceivingDescription").val($(this).data('description'));
    $("#EditItem").val($(this).data('item'));
    $("#EditPackingSlipId").val($(this).data('packing_slip_id'));
    $("#EditQuantity").val($(this).data('quantity'));
    $("#EditPurchaseOrder").val($(this).data('purchase_order'));
    $("#EditLineNumber").val($(this).data('line_number'));
    $("#EditReceiptDate").val($(this).data('receipt_date'));
    $("#EditVendorAccount").val($(this).data('vendor_account'));
    $("#editReceiving").modal("show");
});

//$("#edit_hershey_form").submit(function(event) {
$("body").on("click", "#save_receiving", function (event) {

    event.preventDefault();
    if(isNaN($("#EditQuantity").val())) {
        alert("Quantity is not a valid number")
    } else {
        $("#edit_receiving_save").val("true");
        var data = JSON.stringify(jQuery('#edit_receiving_form').serializeArray());
        console.log(data);
        $.post('/src/edit_receiving.php', data, function (result) {
            console.log(result);
        });
        $("#editReceiving").modal("hide");
        refreshReceivingDates(hold_user_token);
        //location.reload();
    }
});

$("body").on("click", "#update_receiving", function (event) {

    event.preventDefault();
    if(isNaN($("#EditQuantity").val())) {
        alert("Quantity is not a valid number")
    } else {
        $("#edit_save").val("false");
        var data = JSON.stringify(jQuery('#edit_receiving_form').serializeArray());
        console.log(data);
        $.post('/src/edit_receiving.php', data, function (result) {
            console.log(result);
        });
        $("#editReceiving").modal("hide");
        refreshReceivingDates(hold_user_token);
        //location.reload();
    }
});

$("body").on("click", "#delete_receiving", function (event) {

    event.preventDefault();
    var c = confirm("Are you sure you want to delete this record?");

    if(c == true)
    {
        $("#edit_receiving_save").val("false");
        var data = JSON.stringify(jQuery('#edit_receiving_form').serializeArray());
        console.log(data);
        $.post('/src/delete_receiving.php', data, function (result) {
            console.log(result);
        });
        $("#editReceiving").modal("hide");
        refreshReceivingDates(hold_user_token);
        //location.reload();
    } else {

    }
});
