var hold_company_id;

function hershey(user_token, company_id) {
    hold_company_id = company_id;

    $('#edit_transaction_date').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $("#hershey_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#hershey_container").show();
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
    var startDateTxt = document.getElementById("hersheyStartDatePicker");
    var endDateTxt = document.getElementById("hersheyEndDatePicker");
    startDateTxt.value = startDate;
    endDateTxt.value = endDate;
    $('#hersheyStartDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#hersheyEndDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $.get("/src/hershey_documents.php?user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + company_id, function (result) {
       var hershey = jQuery.parseJSON(result);
        console.log(hershey);
        if (hershey.count > 0) {
            $("#hershey_list").html('');
            $("#hershey_list").append('<div id="hershey_data" class="table-responsive"><table id="heshey_data_table" class="table sortable"><thead><tr><th class="width_150">Transaction Date</th><th class="width_150">Lic Plate #</th><th class="width_150">Item #</th><th class="width_150">Batch #</th><th class="width_150"> Production #</th><th class="width_150">Description</th> <th class="width_150">Quantity</th><th class="width_150">UOM</th> </tr></thead><tbody></tbody><tfoot></tfoot></table> </div>')
            jQuery.each(hershey.data, function(x, data) {
                $("#hershey_data tbody").append('<tr><td>' + data.TransactionDate +'</td><td><a href="' + data.file + '" id="' + data.file + '" class="edit_hershey" data-transactiondate="' + data.TransactionDate + '" data-licplate="' + data.LicPlate + '" data-timestamp="' + data.TimeStamp + '" data-item="' + data.Item + '" data-batch="' + data.Batch + '" data-production="' + data.Production + '" data-description="' + data.Description + '" data-quantity="' + data.Qty + '" data-uomdenominator="' + data.UOMDenominator + '" data-uom="' + data.UOM + '" data-file="' + data.file +'">' + data.LicPlate + '</a></td><td>' + data.Item + '</td><td>' + data.Batch + '</td><td>' + data.Production + '</td><td>' + data.Description + '</td><td>' + data.Qty +'</td><td>' + data.UOM +'</td></tr>');
            });
        } else {
            $("#hershey_list").html("No Records Available");
        }
    });
}

function refreshHersheyDates (user_token) {
    $("#hershey_list").html('');
    $("#hershey_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#hershey_container").show();
    var startDateTxt = document.getElementById("hersheyStartDatePicker");
    var endDateTxt = document.getElementById("hersheyEndDatePicker");
    $.get("/src/hershey_documents.php?user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + company_id, function (result) {
        var hershey = jQuery.parseJSON(result);
        console.log(hershey);
        if (hershey.count > 0) {
            $("#hershey_list").html('');
            $("#hershey_list").append('<div id="hershey_data" class="table-responsive"><table id="heshey_data_table" class="table sortable"><thead><tr><th class="width_150">Transaction Date</th><th class="width_150">Lic Plate #</th><th class="width_150">Item #</th><th class="width_150">Batch #</th><th class="width_150"> Production #</th><th class="width_150">Description</th> <th class="width_150">Quantity</th><th class="width_150">UOM</th> </tr></thead><tbody></tbody><tfoot></tfoot></table> </div>')
            jQuery.each(hershey.data, function(x, data) {
                $("#hershey_data tbody").append('<tr><td>' + data.TransactionDate +'</td><td><a href="' + data.file + '" id="' + data.file + '" class="edit_hershey" data-transactiondate="' + data.TransactionDate + '" data-licplate="' + data.LicPlate + '" data-timestamp="' + data.TimeStamp + '" data-item="' + data.Item + '" data-batch="' + data.Batch + '" data-production="' + data.Production + '" data-description="' + data.Description + '" data-quantity="' + data.Qty + '" data-uomdenominator="' + data.UOMDenominator + '" data-uom="' + data.UOM + '" data-file="' + data.file +'">' + data.LicPlate + '</a></td><td>' + data.Item + '</td><td>' + data.Batch + '</td><td>' + data.Production + '</td><td>' + data.Description + '</td><td>' + data.Qty +'</td><td>' + data.UOM +'</td></tr>');
            });
        } else {
            $("#hershey_list").html("No Records Available");
        }
    });
}

$("body").on("click", ".edit_hershey", function(event) {
   event.preventDefault();
    $("#edit_transaction_date").val($(this).data('transactiondate'));
    $("#edit_lic_plate").val($(this).data('licplate'));
    $("#edit_timestamp").val($(this).data('timestamp'));
    $("#edit_item").val($(this).data('item'));
    $("#edit_batch").val($(this).data('batch'));
    $("#edit_production").val($(this).data('production'));
    $("#edit_quantity").val($(this).data('quantity'));
    $("#edit_description").val($(this).data('description'));
    $("#edit_uom_denominator").val($(this).data('uomdenominator'));
    $("#edit_uom").val($(this).data('uom'));
    $("#edit_file").val($(this).data('file'));
    $("#editHershey").modal("show");
});

//$("#edit_hershey_form").submit(function(event) {
$("body").on("click", "#save_hershey", function (event) {

   event.preventDefault();
    if(isNaN($("#edit_quantity").val())) {
        alert("Quantity is not a valid number")
    } else {
        $("#edit_save").val("true");
        var data = JSON.stringify(jQuery('#edit_hershey_form').serializeArray());
        console.log(data);
        $.post('/src/edit_hershey.php', data, function (result) {
            console.log(result);
        });
        $("#editHershey").modal("hide");
        location.reload();
    }
});

$("body").on("click", "#update_hershey", function (event) {

    event.preventDefault();
    if(isNaN($("#edit_quantity").val())) {
        alert("Quantity is not a valid number")
    } else {
        $("#edit_save").val("false");
        var data = JSON.stringify(jQuery('#edit_hershey_form').serializeArray());
        console.log(data);
        $.post('/src/edit_hershey.php', data, function (result) {
            console.log(result);
        });
        $("#editHershey").modal("hide");
        location.reload();
    }
});

$("body").on("click", "#delete_hershey", function (event) {

    event.preventDefault();
    var c = confirm("Are you sure you want to delete this record?");

    if(c == true)
    {
        $("#edit_save").val("false");
        var data = JSON.stringify(jQuery('#edit_hershey_form').serializeArray());
        console.log(data);
        $.post('/src/delete_hershey.php', data, function (result) {
            console.log(result);
        });
        $("#editHershey").modal("hide");
        location.reload();
    } else {

    }
});