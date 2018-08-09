var hold_company_id;

function inventoryCheck(user_token, company_id) {
    hold_company_id = company_id;
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

function coasList(user_token, company_id) {
    hold_company_id = company_id;
    $("#coas_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#coas_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">COAs</h1>');
    console.log(company_id);
    $.get("/src/documents.php?user_token=" + user_token + "&company_id=" + company_id + "&tab=coas",function(result) {

        var documents = jQuery.parseJSON(result);
        if(documents.active == true) {
            $("#coas_list").html("");
            if(typeof documents.list != 'undefined') {
                jQuery.each( documents.list, function( i, val ) {
                    if(i > 1) { //Skip "." and ".."
                        $("#coas_list").append('<a href="'+val.url+'" class="list-group-item" target="_blank"><i class="fa fa-file-'+val.ext+'-o"></i>&nbsp;&nbsp;<span class="doc_name">'+val.name+'</span></a>');
                    }
                });
                $("#search-coas").fadeIn();
            } else {
                $("#coas_list").html("No Documents Available");
            }
        } else {
            $("#coas_list").html("No Documents Available");
        }
    });
}


$("body").on("click",".opensales_tab",function(event) {
    event.preventDefault();
    var type = $(this).data("tab");
    $("#opensales_tabs li").removeClass("active");
    $("#tab_"+type).addClass("active");
    opensalesCheck(user_token,type);
});


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
    $(".col-xs-10").html('<h1 class="page_header">Receipts</h1>');
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

function productionTransactions(user_token, company_id) {
    hold_company_id = company_id;
    $("#prod_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#prod_trans_container_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">Production</h1>');
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
    var startDateTxt = document.getElementById("prodStartDatePicker");
    var endDateTxt = document.getElementById("prodEndDatePicker");
    startDateTxt.value = startDate;
    endDateTxt.value = endDate;
    $('#prodStartDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $('#prodEndDatePicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
    $.get("/src/prod_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value  + "&company_id=" + company_id, function (result) {
        var prodTrans = jQuery.parseJSON(result);
        if (prodTrans.count > 0) {
            $("#prod_trans_list").html('');
            jQuery.each(prodTrans.data, function (x, prodNum) {
                $("#prod_trans_list").append('<div id="prod_num_' + prodNum.Production + '" class="table-responsive"><table id="prod_num_table_' + prodNum.Production +'" class="table sortable"><thead><tr><th class="width_180">Prod Date</th><th>Production #</th><th>Item #</th><th>Description</th></tr></thead><tbody><tr><td class="width_180">' + prodNum.TransDate + '</td><td>' + x + '</td> <td>' + prodNum.ItemNumber + '</td><td>' + prodNum.Description + '</td></tr></tbody><tfoot></tfoot></table>');
                $("#prod_trans_list").append('<div id="prod_num_' + prodNum.Production + '_lot" class="table-responsive"><table id="prod_num_table_' + prodNum.Production +'_lot" class="table sortable"><thead><tr><th>Lot #</th><th>Exp. Date</th><th>Good Qty</th></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
                jQuery.each(prodNum, function (z, det) {
                    if(typeof det.Lot != "undefined") {

                        $("#prod_num_" + prodNum.Production + "_lot tbody").append('<tr><td>' + det.Lot + '</td><td>' + det.ExpDate + '</td><td>' + det.GoodQuantity + '</td><</tr>');
                    }
                });
            });
            $.bootstrapSortable(false);
        } else {
            $("#prod_trans_list").html("No Production Transactions Available");
        }
    });
}

function refreshProdTransDates(user_token) {
    $("#prod_trans_list").html('');
    $("#prod_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#prod_trans_container").show();
    var startDateTxt = document.getElementById("prodStartDatePicker");
    var endDateTxt = document.getElementById("prodEndDatePicker");
    $.get("/src/prod_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value + "&company_id=" + hold_company_id, function (result) {
        var prodTrans = jQuery.parseJSON(result);
        if (prodTrans.count > 0) {
            $("#prod_trans_list").html('');
            jQuery.each(prodTrans.data, function (x, prodNum) {
                $("#prod_trans_list").append('<div id="prod_num_' + prodNum.Production + '" class="table-responsive"><table id="prod_num_table_' + prodNum.Production +'" class="table sortable"><thead><tr><th class="width_180">Prod Date</th><th>Production #</th><th>Item #</th><th>Description</th></tr></thead><tbody><tr><td class="width_180">' + prodNum.TransDate + '</td><td>' + x + '</td> <td>' + prodNum.ItemNumber + '</td><td>' + prodNum.Description + '</td></tr></tbody><tfoot></tfoot></table>');
                $("#prod_trans_list").append('<div id="prod_num_' + prodNum.Production + '_lot" class="table-responsive"><table id="prod_num_table_' + prodNum.Production +'_lot" class="table sortable"><thead><tr><th>Lot #</th><th>Exp. Date</th><th>Good Qty</th></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
                jQuery.each(prodNum, function (z, det) {
                    if(typeof det.Lot != "undefined") {

                        $("#prod_num_" + prodNum.Production + "_lot tbody").append('<tr><td>' + det.Lot + '</td><td>' + det.ExpDate + '</td><td>' + det.GoodQuantity + '</td><</tr>');
                    }
                });
            });

            $.bootstrapSortable(false);
        } else {
            $("#prod_trans_list").html("No Productions Transactions Available");
        }
    });
}

function opensalesCheck(user_token,type) {
    type = (typeof type !== "undefined" ? type : "list");
    $("#opensales_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#opensales_container").show();
    $(".col-xs-10").html('');
    $(".col-xs-10").html('<h1 class="page_header">Open Sales</h1>');
    $.get("/src/open_sales.php?type="+type+"&user_token="+user_token+"&company_id="+hold_company_id,function(result) {
        var sales = jQuery.parseJSON(result);
        if(sales.count > 0) {
            $("#opensales_list").html("");
            if(type == "items") {
                var count = 0;
                jQuery.each( sales.data, function( i, sale ) {
                    $("#opensales_list").append('<table id="item_'+count+'" class="table"><thead><tr><th>Item #</th><th colspan="7">Description</th></tr><tr><th>'+i+'</th><th colspan="7">'+sale.description+'</th></tr></thead><tbody><tr><td>Cust Ref</td><td>Sales Order</td><td>Unit</td><td class="text_right">Quantity</td><td class="text_right">Shipped</td><td class="text_right">Remainder</td><td>Delivery Address</td></tr></tbody></table>');
                    jQuery.each( sale.data, function( s, item ) {
                        $("#item_"+count+" tbody").append('<tr><td>'+item.CustomerRef+'</td><td>'+item.SalesId+'</td><td>'+item.SalesUnit+'</td><td class="text_right">'+item.SalesQty+'</td><td class="text_right">'+item.Shipped+'</td><td class="text_right">'+item.Remainder+'</td><td class="width_170">'+item.DeliveryAddress+'</td></tr>');
                    });
                    count++;
                });
            } else {
                $("#opensales_list").html('<table class="table sortable"><thead><tr><th data-defaultsort="asc">Cust Ref</th><th class="width_120">Sales Order</th><th>Item #</th><th>Description</th><th>Unit</th><th class="text_right">Quantity</th><th class="text_right">Shipped</th><th class="text_right">Remainder</th></tr></thead><tbody></tbody></table>');
                jQuery.each( sales.data, function( i, sale ) {
                    $("#opensales_list tbody").append('<tr><td>'+sale.CustomerRef+'</td><td class="width_120">'+sale.SalesId+'</td><td>'+sale.ItemId+'</td><td>'+sale.ItemName+'</td><td>'+sale.SalesUnit+'</td><td class="text_right">'+sale.SalesQty+'</td><td class="text_right">'+sale.Shipped+'</td><td class="text_right">'+sale.Remainder+'</td></tr>');
                });
                $.bootstrapSortable(false);
            }
        } else {
            $("#opensales_list").html("No Inventory Available");
        }
    });
}

function usageDocumentList(user_token, company_id) {
    $("#usage_document_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#usage_reports_container").show();

    $.get("/src/documents.php?user_token=" + user_token + "&company_id=" + company_id + "&tab=usage",function(result) {
        var documents = jQuery.parseJSON(result);
        if(documents.active == true) {
            $("#usage_document_list").html("");
            if(typeof documents.list != 'undefined') {
                jQuery.each( documents.list, function( i, val ) {
                    if(i > 1) { //Skip "." and ".."
                        $("#usage_document_list").append('<a href="'+val.url+'" class="list-group-item" target="_blank"><i class="fa fa-file-'+val.ext+'-o"></i>&nbsp;&nbsp;<span class="doc_name">'+val.name+'</span></a>');
                    }
                });
                $("#usage_search-docs").fadeIn();
            } else {
                $("#usage_document_list").html("No Documents Available");
            }
        } else {
            $("#usage_document_list").html("No Documents Available");
        }
    });
}

$("#usage_search-docs").keyup(function () {
    var searchTerm = $("#usage_search-docs").val();

    if(searchTerm == "" || searchTerm == undefined) {
        $("#usage_document_list a").removeClass('out').addClass('in').show();
    } else {
        var listItem = $('#usage_document_list').children('a');

        var searchSplit = searchTerm;

        //extends :contains to be case insensitive
        $.extend($.expr[':'], {
            'containsi': function(elem, i, match, array)
            {
                return (elem.textContent || elem.innerText || '').toLowerCase()
                    .indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        $("#usage_document_list a span.doc_name").not(":containsi('" + searchSplit + "')").each(function(e)   {
            $(this).closest("a").addClass('out').removeClass('in').fadeOut();
        });

        $("#usage_document_list a span.doc_name:containsi('" + searchSplit + "')").each(function(e) {
            $(this).closest("a").removeClass('out').addClass('in').fadeIn('slow');
        });

        var calCount = $('#usage_document_list .in').length;
        //$('.list-count').text(jobCount + ' items');

        //shows empty state text when no jobs found
        if(calCount == '0') {
            $('#no_usage_doc_results').fadeIn();
        }
        else {
            $('#no_usage_doc_results').hide();
        }
    }
});

function shippingDocumentList(user_token, company_id) {
    $("#shipping_document_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#shipping_schedule_container").show();

    $.get("/src/documents.php?user_token=" + user_token + "&company_id=" + company_id + "&tab=shipping_schedule",function(result) {
        var documents = jQuery.parseJSON(result);
        if(documents.active == true) {
            $("#shipping_document_list").html("");
            if(typeof documents.list != 'undefined') {
                jQuery.each( documents.list, function( i, val ) {
                    if(i > 1) { //Skip "." and ".."
                        $("#shipping_document_list").append('<a href="'+val.url+'" class="list-group-item" target="_blank"><i class="fa fa-file-'+val.ext+'-o"></i>&nbsp;&nbsp;<span class="doc_name">'+val.name+'</span></a>');
                    }
                });
                $("#shipping_search-docs").fadeIn();
            } else {
                $("#shipping_document_list").html("No Documents Available");
            }
        } else {
            $("#shipping_document_list").html("No Documents Available");
        }
    });
}

$("#shipping_search-docs").keyup(function () {
    var searchTerm = $("#shipping_search-docs").val();

    if(searchTerm == "" || searchTerm == undefined) {
        $("#shipping_document_list a").removeClass('out').addClass('in').show();
    } else {
        var listItem = $('#shipping_document_list').children('a');

        var searchSplit = searchTerm;

        //extends :contains to be case insensitive
        $.extend($.expr[':'], {
            'containsi': function(elem, i, match, array)
            {
                return (elem.textContent || elem.innerText || '').toLowerCase()
                    .indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });

        $("#shipping_document_list a span.doc_name").not(":containsi('" + searchSplit + "')").each(function(e)   {
            $(this).closest("a").addClass('out').removeClass('in').fadeOut();
        });

        $("#shipping_document_list a span.doc_name:containsi('" + searchSplit + "')").each(function(e) {
            $(this).closest("a").removeClass('out').addClass('in').fadeIn('slow');
        });

        var calCount = $('#shipping_document_list .in').length;
        //$('.list-count').text(jobCount + ' items');

        //shows empty state text when no jobs found
        if(calCount == '0') {
            $('#no_shipping_doc_results').fadeIn();
        }
        else {
            $('#no_shipping_doc_results').hide();
        }
    }
});/**
 * Created by christophercole on 7/16/17.
 */
