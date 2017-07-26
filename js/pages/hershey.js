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
                $("#hershey_data tbody").append('<tr><td>' + data.TransDate +'</td><td><a href="' + data.Id + '" id="' + data.Id + '" class="edit_hershey" data-TransDate="' + data.TransDate + '" data-LicPlate="' + data.MAX_LicensePlateNumber + '" data-BatchNumber="' + data.BatchNumber + '" data-ProdId="' + data.ProdId + '" data-Description="' + data.Description + '" data-QtyGood="' + data.QtyGood + '" data-UOMDenominator="' + data.UOMDenominator + '" data-UOM="' + data.UOM + '">' + data.LicPlate + '</a></td><td>' + data.MaterialNumber + '</td><td>' + data.BatchNumber + '</td><td>' + data.ProdId + '</td><td>' + data.Description + '</td><td>' + data.QtyGood +'</td><td>' + data.UOM +'</td></tr>');
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
                $("#hershey_data tbody").append('<tr><td>' + data.TransDate +'</td><td><a href="' + data.Id + '" id="' + data.Id + '" class="edit_hershey" data-TransDate="' + data.TransDate + '" data-LicPlate="' + data.MAX_LicensePlateNumber + '" data-BatchNumber="' + data.BatchNumber + '" data-ProdId="' + data.ProdId + '" data-Description="' + data.Description + '" data-QtyGood="' + data.QtyGood + '" data-UOMDenominator="' + data.UOMDenominator + '" data-UOM="' + data.UOM + '">' + data.LicPlate + '</a></td><td>' + data.MaterialNumber + '</td><td>' + data.BatchNumber + '</td><td>' + data.ProdId + '</td><td>' + data.Description + '</td><td>' + data.QtyGood +'</td><td>' + data.UOM +'</td></tr>');
            });
        } else {
            $("#hershey_list").html("No Records Available");
        }
    });
}

$("body").on("click", ".edit_hershey", function(event) {
   event.preventDefault();
    $("#EditTransDate").val($(this).data('TransDate'));
    $("#EditLicPlate").val($(this).data('LicPlate'));
    //$("#edit_timestamp").val($(this).data('timestamp'));
    $("#EditMaterialNumber").val($(this).data('MaterialNumber'));
    $("#EditBatchNumber").val($(this).data('BatchNumber'));
    $("#EditProdId").val($(this).data('ProdId'));
    $("#EditQtyGood").val($(this).data('QtyGood'));
    $("#EditDescription").val($(this).data('Description'));
    $("#EditUOMDenominator").val($(this).data('UOMDenominator'));
    $("#EditUOM").val($(this).data('UOM'));
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
    type = (typeof type !== "undefined" ? type : "list");
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