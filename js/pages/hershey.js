var hold_company_id;
var hold_user_token;

function hershey(user_token, company_id) {
    hold_company_id = company_id;
    hold_user_token = user_token;

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
                $("#hershey_data tbody").append('<tr><td>' + data.TransDate +'</td><td><a href="' + data.Id + '" id="' + data.Id + '" class="edit_hershey" data-trans-date="' + data.TransDate + '" data-lic-plate="' + data.LicPlate + '" data-batch-number="' + data.BatchNumber + '" data-prod-id="' + data.ProdId + '" data-description="' + data.Description + '" data-qty-good="' + data.QtyGood + '" data-uom-denominator="' + data.UOMDenominator + '" data-uom="' + data.UOM + '" data-material-number="' + data.MaterialNumber + '">' + data.LicPlate + '</a></td><td>' + data.MaterialNumber + '</td><td>' + data.BatchNumber + '</td><td>' + data.ProdId + '</td><td>' + data.Description + '</td><td>' + data.QtyGood +'</td><td>' + data.UOM +'</td></tr>');
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
                $("#hershey_data tbody").append('<tr><td>' + data.TransDate +'</td><td><a href="' + data.Id + '" id="' + data.Id + '" class="edit_hershey" data-trans-date="' + data.TransDate + '" data-lic-plate="' + data.LicPlate + '" data-batch-number="' + data.BatchNumber + '" data-prod-id="' + data.ProdId + '" data-description="' + data.Description + '" data-qty-good="' + data.QtyGood + '" data-uom-denominator="' + data.UOMDenominator + '" data-uom="' + data.UOM + '" data-material-number="' + data.MaterialNumber + '">' + data.LicPlate + '</a></td><td>' + data.MaterialNumber + '</td><td>' + data.BatchNumber + '</td><td>' + data.ProdId + '</td><td>' + data.Description + '</td><td>' + data.QtyGood +'</td><td>' + data.UOM +'</td></tr>');
            });
        } else {
            $("#hershey_list").html("No Records Available");
        }
    });
}

$("body").on("click", ".edit_hershey", function(event) {
   event.preventDefault();
    $("#EditTransDate").val($(this).data('trans-date'));
    console.log($(this).data('trans-date'));
    $("#EditLicPlate").val($(this).data('lic-plate'));
    console.log($(this).data('lic-plate'));
    //$("#edit_timestamp").val($(this).data('timestamp'));
    $("#EditMaterialNumber").val($(this).data('material-number'));
    console.log($(this).data('material-number'));
    $("#EditBatchNumber").val($(this).data('batch-number'));
    console.log($(this).data('batch-number'));
    $("#EditProdId").val($(this).data('prod-id'));
    console.log($(this).data('prod-id'));
    $("#EditQtyGood").val($(this).data('qty-good'));
    console.log($(this).data('qty-good'));
    $("#EditDescription").val($(this).data('description'));
    console.log($(this).data('description'));
    $("#EditUOMDenominator").val($(this).data('uom-denominator'));
    console.log($(this).data('uom-denominator'));
    $("#EditUOM").val($(this).data('uom'));
    console.log($(this).data('uom'));
    //$("#edit_file").val($(this).data('file'));
    $("#editHershey").modal("show");
});

//$("#edit_hershey_form").submit(function(event) {
$("body").on("click", "#save_hershey", function (event) {

   event.preventDefault();
    if(isNaN($("#EditQtyGood").val())) {
        alert("Quantity is not a valid number")
    } else {
        $("#edit_save").val("true");
        var data = JSON.stringify(jQuery('#edit_hershey_form').serializeArray());
        console.log(data);
        $.post('/src/edit_hershey.php', data, function (result) {
            console.log(result);
        });
        $("#editHershey").modal("hide");
        refreshHersheyDates(hold_user_token);
        //location.reload();
    }
});

$("body").on("click", "#update_hershey", function (event) {

    event.preventDefault();
    if(isNaN($("#EditQtyGood").val())) {
        alert("Quantity is not a valid number")
    } else {
        $("#edit_save").val("false");
        var data = JSON.stringify(jQuery('#edit_hershey_form').serializeArray());
        console.log(data);
        $.post('/src/edit_hershey.php', data, function (result) {
            console.log(result);
        });
        $("#editHershey").modal("hide");
        refreshHersheyDates(hold_user_token);
        //location.reload();
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
        refreshHersheyDates(hold_user_token);
        //location.reload();
    } else {

    }
});

$("body").on("click",".raf_tab",function(event) {
    event.preventDefault();
    var type = $(this).data("tab");
    $("#raf_tabs li").removeClass("active");
    $("#tab_"+type).addClass("active");
    rafCheck(user_token,type);
});

function rafCheck(user_token, type) {
    type = (typeof type !== "undefined" ? type : "raf");
    $("#raf_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#raf_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">RAF</h1>');
    $.get("/src/raf.php?type="+type+"&user_token="+user_token,function(result) {
        var raf = jQuery.parseJSON(result);
        if(raf.count > 0) {
            $("#raf_list").html("");
            //if(type == "raf") {
                $("#raf_list").html('<table class="table sortable"><thead><tr><th>Location</th><th class="width_180">Item #</th><th class="width_180 ">Batch #</th> <th>Product Name</th><th class="text_right">Quantity</th><th>UOM</th><th class="text_right">Case</th><th>Sell UOM</th><th class="text_right">Pallet</th><th class="width_180">Prod Date</th></tr></thead><tbody></tbody><tfoot></foot></table>');
                jQuery.each( raf.data, function( i, inv ) {
                    $("#raf_list tbody").append('<tr><td class="width_180">' + inv.Location + '</td><td class="width_120">'+inv.ItemId+'</td><td class="width_180">'+inv.BatchNumber+'</td><td>'+inv.ItemName+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td class="text_right">'+inv.Case+'</td><td>'+inv.SellUOM+'</td><td class="text_right">'+inv.Pallet+'</td><td>'+inv.ProdDate+'</td></tr>');
                });
                if(typeof raf.total != "undefined") {
                    $("#raf_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+raf.total.quantity+'</td><td></td><td class="text_right">'+raf.total.case+'</td><td></td><td class="text_right">'+raf.total.pallet+'</td></tr>');
                }
                $.bootstrapSortable(false);
            //} else {

           // }
        } else {
            $("#raf_list").html("No Items Available");
        }
    });
}

$("body").on("click",".prod_raf_tab",function(event) {
    event.preventDefault();
    var company = $(this).data("tab");
    $("#prod_raf_tabs li").removeClass("active");
    $("#prod_raf_company_"+company).addClass("active");
    prodRafCheck(user_token,company);
});

function prodRafCheck(user_token, company_id) {
    hold_company_id = company_id;
    if (typeof company_id == "undefined") { company_id = "1"; }
    $("#prod_raf_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#prod_raf_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">Production Order Status</h1>');
    $.get("/src/prod_raf.php?company="+company_id+"&user_token="+user_token,function(result) {
        var raf = jQuery.parseJSON(result);
        if(raf.count > 0) {
            $("#prod_raf_list").html("");
            //if(type == "raf") {
            $("#prod_raf_list").html('<table class="table sortable"><thead><tr><th>Production ID</th><th class="width_180">Item ID</th><th class="width_180 ">Name</th> <th>Production Status</th><th class="text_right">Quantity Scheduled</th><th>Remaining Physical Inventory</th><th class="text_right">Notes</th><th>First RAF Date</th></tr></thead><tbody></tbody><tfoot></foot></table>');
            jQuery.each( raf.data, function( i, inv ) {
                $("#prod_raf_list tbody").append('<tr><td class="width_180">' + inv.ProdId + '</td><td class="width_120">'+inv.ItemId+'</td><td class="width_180">'+inv.Name+'</td><td>'+inv.ProdStatus+'</td><td class="text_right">'+inv.QtySched+'</td><td>'+inv.RemainInventPhysical+'</td><td class="text_right">'+inv.Notes+'</td><td>'+inv.FirstRAFDate+'</td></tr>');
            });
            if(typeof raf.total != "undefined") {
                $("#prod_raf_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+raf.total.quantity+'</td><td></td><td class="text_right">'+raf.total.case+'</td><td></td><td class="text_right">'+raf.total.pallet+'</td></tr>');
            }
            $.bootstrapSortable(false);
            //} else {

            // }
        } else {
            $("#prod_raf_list").html("No Items Available");
        }
    });
}