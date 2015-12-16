function inventoryCheck(user_token) {
	$("#inventory_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#inventory_container").show();
	$.get("/src/inventory.php?act=list&user_token="+user_token,function(result) {
		var inventory = jQuery.parseJSON(result);
		if(inventory.count > 0) {
			$("#inventory_list").html('<h5><i>"CS" - Customer-supplied (owned by Energems)</i></h5><table class="table sortable"><thead><tr><th class="width_100" data-defaultsort="asc">Class</th><th class="width_180">Item #</th><th>Product Name</th><th>Batch #</th><th class="width_100">Exp. Date</th><th class="text_right">Quantity</th><th>UOM</th><th>Location</th></tr></thead><tbody></tbody></table>');
			jQuery.each( inventory.data, function( i, inv ) {
				$("#inventory_list tbody").append('<tr><td class="width_100">'+inv.ItemGroupId+'</td><td class="width_180">'+inv.ItemId+'</td><td>'+inv.ItemName+'</td><td>'+inv.BatchNumber+'</td><td>'+inv.expDate+'</td><td class="text_right">'+inv.AvailPhysical+'</td><td>'+inv.BOMUnitId+'</td><td>'+inv.Location+'</td></tr>');
			});
			$.bootstrapSortable(false);
		} else {
			$("#inventory_list").html("No Inventory Available");
		}
	});
}

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

function shippingData(user_token) {
	$("#shipments_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#shipments_container").show();
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
	$.get("/src/shipments.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value, function (result) {
		var shipments = jQuery.parseJSON(result);
		if (shipments.count > 0) {
			$("#shipments_list").html('');
			//$("#shipments_list").html('<table class="table sortable"><thead><tr><th>Packing Slip #</tr></thead><tbody></tbody><tfoot></foot></table>');
			//$("#shipments_list").html('<table class="table sortable"><thead><tr><th class="text_right">Packing Slip #</th><th>Ship Date</th><th>Item #</th><th>Description</th><th class="text_right">Sales Order #</th><th class="text_right">Customer Ref</th></tr></thead><tbody></tbody><tfoot></foot></table>');
			jQuery.each(shipments.data, function (x, psId) {
				$("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Packing Slip #</th><th>Ship Date</th><th>Item #</th><th>Description</th><th>Sale Order #</th><th>Customer Ref</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//safewayfs02/Shared/Docs-USP/SO/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ShipDate + '</td> <td>' + psId.Item + '</td><td>' + psId.Description + '</td><td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr></tbody><tfoot></tfoot></table>');
				//$("slip_id_table_" + psId.PackingSlipId + "tbody").append('<tr><td>' + x + '</td><td>' + psId.ShipDate + '</td> <td>' + psId.Item + '</td><td>' + psId.Description + '</td><td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr>');
				$("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '_lot" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'_lot" class="table sortable"><thead><tr><th>Lot #</th><th>Exp. Date</th><th>Delivered</th><th>UOM</th></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
				jQuery.each(psId, function (z, det) {
					if(typeof det.Lot != "undefined") {
						$("#slip_id_" + psId.PackingSlipId + "_lot tbody").append('<tr><td>' + det.Lot + '</td><td>' + det.ExpirationDate + '</td><td>' + det.Delivered + '</td><td>' + det.UOM + '</td></tr>');
					}
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
function refreshShipmentDates(user_token) {
	$("#shipments_list").html('');
	$("#shipments_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#shipments_container").show();
	var startDateTxt = document.getElementById("shipStartDatePicker");
	var endDateTxt = document.getElementById("shipEndDatePicker");
	$.get("/src/shipments.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value, function (result) {
		var shipments = jQuery.parseJSON(result);
		if (shipments.count > 0) {
			$("#shipments_list").html('');
			jQuery.each(shipments.data, function (x, psId) {
				$("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'" class="table sortable"><thead><tr><th class="width_180">Packing Slip #</th><th>Ship Date</th><th>Item #</th><th>Description</th><th>Sale Order #</th><th>Customer Ref</th></tr></thead><tbody><tr><td class="width_180"><a href="/src/shipments_file.php?loc=//safewayfs02/Shared/Docs-USP/SO/' + x +'&user_token=' + user_token +'">' + x + '</a></td><td>' + psId.ShipDate + '</td> <td>' + psId.Item + '</td><td>' + psId.Description + '</td><td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr></tbody><tfoot></tfoot></table>');
				//$("slip_id_table_" + psId.PackingSlipId + "tbody").append('<tr><td>' + x + '</td><td>' + psId.ShipDate + '</td> <td>' + psId.Item + '</td><td>' + psId.Description + '</td><td>' + psId.SalesOrder + '</td><td>' + psId.CustomerRef + '</td></tr>');
				$("#shipments_list").append('<div id="slip_id_' + psId.PackingSlipId + '_lot" class="table-responsive"><table id="slip_id_table_' + psId.PackingSlipId +'_lot" class="table sortable"><thead><tr><th>Lot #</th><th>Exp. Date</th><th>Delivered</th><th>UOM</th></tr></thead><tbody></tbody><tfoot></tfoot></table> </div>');
				jQuery.each(psId, function (z, det) {
					if(typeof det.Lot != "undefined") {
						$("#slip_id_" + psId.PackingSlipId + "_lot tbody").append('<tr><td>' + det.Lot + '</td><td>' + det.ExpirationDate + '</td><td>' + det.Delivered + '</td><td>' + det.UOM + '</td></tr>');
					}
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

function productionTransactions(user_token) {
	$("#prod_trans_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#prod_trans_container_container").show();
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
	$.get("/src/prod_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value, function (result) {
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
			// if(typeof shipments.total != "undefined") {
			// 	$("#inventory_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
			// }
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
	$.get("/src/prod_trans.php?act=list&user_token=" + user_token + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value, function (result) {
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
			// if(typeof shipments.total != "undefined") {
			// 	$("#inventory_list tfoot").append('<tr><td colspan="2">TOTAL</td><td class="text_right">'+inventory.total.quantity+'</td><td></td><td class="text_right">'+inventory.total.case+'</td><td></td><td class="text_right">'+inventory.total.pallet+'</td></tr>');
			// }
			$.bootstrapSortable(false);
		} else {
			$("#prod_trans_list").html("No Productions Transactions Available");
		}
	});
}
