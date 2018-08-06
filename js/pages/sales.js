var hold_company_id;
function salesData(user_token) {
	$("#sales_container").show();
	$.get("/src/sales.php?user_token="+user_token,function(result) {
		var sales = jQuery.parseJSON(result);
		if(sales.active == true) {
			$(".cur_month").html(sales.month_name);
			$(".cur_year").html(sales.cur_year);
			$(".prev_year").html(sales.prev_year);
			$(".month_range").html(sales.month_range);
			$("#cur_mtd_0").html("$"+sales[0].cur_mtd);
			$("#cur_ytd_0").html("$"+sales[0].cur_ytd);
			$("#past_mtd_0").html("$"+sales[0].prev_mtd);
			$("#past_ytd_0").html("$"+sales[0].prev_ytd);
			$("#cur_mtd_1").html("$"+sales[1].cur_mtd);
			$("#cur_ytd_1").html("$"+sales[1].cur_ytd);
			$("#past_mtd_1").html("$"+sales[1].prev_mtd);
			$("#past_ytd_1").html("$"+sales[1].prev_ytd);
			$("#cur_mtd_total").html("$"+sales.cur_mtd_total);
			$("#past_mtd_total").html("$"+sales.past_mtd_total);
			$("#cur_ytd_total").html("$"+sales.cur_ytd_total);
			$("#past_ytd_total").html("$"+sales.past_ytd_total);
		}
	});
}

function historicalSalesData(user_token) {
	$("#historical_mtd_sales").html('');
	$("#historical_mtd_sales").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#historical_sales_container").show();
    $.get("/src/historical_sales.php?user_token="+user_token,function(result) {
        var sales = jQuery.parseJSON(result);
        if(sales.active == true) {
            $(".cur_month").html(sales.month_name);
            $(".cur_year").html(sales.cur_year);
            $(".prev_year").html(sales.prev_year);
            $(".month_range").html(sales.month_range);
            $("#cur_mtd_0").html("$"+sales[0].cur_mtd);
            $("#cur_ytd_0").html("$"+sales[0].cur_ytd);
            $("#past_mtd_0").html("$"+sales[0].prev_mtd);
            $("#past_ytd_0").html("$"+sales[0].prev_ytd);
            $("#cur_mtd_1").html("$"+sales[1].cur_mtd);
            $("#cur_ytd_1").html("$"+sales[1].cur_ytd);
            $("#past_mtd_1").html("$"+sales[1].prev_mtd);
            $("#past_ytd_1").html("$"+sales[1].prev_ytd);
            $("#cur_mtd_total").html("$"+sales.cur_mtd_total);
            $("#past_mtd_total").html("$"+sales.past_mtd_total);
            $("#cur_ytd_total").html("$"+sales.cur_ytd_total);
            $("#past_ytd_total").html("$"+sales.past_ytd_total);
        }
    });
}

function refreshHistoricalSalesData(user_token, periodObject) {
	var period = periodObject.value;
	console.log("The historical sales select worked. The period is " + period);
    $("#historical_mtd_sales").html('');
    $("#historical_mtd_sales").html('<div id="loading"><img src="images/spin.gif" /></div>');
    $("#historical_sales_container").show();
    $.get("/src/historical_sales.php?user_token="+user_token+"&period="+period,function(result) {
        var sales = jQuery.parseJSON(result);
        if(sales.active == true) {
            $(".cur_month").html(sales.month_name);
            $(".cur_year").html(sales.cur_year);
            $(".prev_year").html(sales.prev_year);
            $(".month_range").html(sales.month_range);
            $("#historical_cur_mtd_0").html("$"+sales[0].cur_mtd);
            $("#cur_ytd_0").html("$"+sales[0].cur_ytd);
            $("#past_mtd_0").html("$"+sales[0].prev_mtd);
            $("#past_ytd_0").html("$"+sales[0].prev_ytd);
            $("#cur_mtd_1").html("$"+sales[1].cur_mtd);
            $("#cur_ytd_1").html("$"+sales[1].cur_ytd);
            $("#past_mtd_1").html("$"+sales[1].prev_mtd);
            $("#past_ytd_1").html("$"+sales[1].prev_ytd);
            $("#cur_mtd_total").html("$"+sales.cur_mtd_total);
            $("#past_mtd_total").html("$"+sales.past_mtd_total);
            $("#cur_ytd_total").html("$"+sales.cur_ytd_total);
            $("#past_ytd_total").html("$"+sales.past_ytd_total);
        }
    });
}

function agingChart(user_token,company) {
	$("#chart_box").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#aging_detail").html('<center>Loading...</center>');
	if(typeof company == "undefined") { company = "1"; }
	$("#aging_container").show();
	$.get("/src/cust_aging.php?user_token="+user_token+"&company="+company,function(result) {
		var aging = jQuery.parseJSON(result);
		console.log(aging);
		if (aging.data.total > 0) {
			var plot = $.plot($("#chart_box"), [ {
				    hoverable: true,
				    data: aging.data.chart,
				    color: '#3498db'
				 } ], {
					bars: {
						show: true,
					 	clickable: true,
						align: "center"
					},
					yaxis: {
							show: true,
							axisLabel: "Amounts (Thousands)",
							position: "left"
					},
					xaxis: {
					 	show: true,
						ticks: [[0, "Not Due<br>"+numeral(Math.round(aging.data.chart[0][1])).format('0,0')],[1,"Current<br>"+numeral(Math.round(aging.data.chart[1][1])).format('0,0')],[2,"30 Days<br>"+numeral(Math.round(aging.data.chart[2][1])).format('0,0')],[3,"60 Days<br>"+numeral(Math.round(aging.data.chart[3][1])).format('0,0')],[4,"90 Days<br>"+numeral(Math.round(aging.data.chart[4][1])).format('0,0')],[5,"180 & Over<br>"+numeral(Math.round(aging.data.chart[5][1])).format('0,0')]]
					},
					grid: {
						clickable: true,
						hoverable: true
					}
				}
			);
			$("#chart_box").bind("plotclick", function (event, pos, item) {
			    if (item) {
			        //highlight(item.series, item.datapoint);
							$(".aging_group").hide();
							$("#aging_group_"+item.dataIndex).show();
			        console.log(item);
			    }
			});

			var aging_data = aging.data.due;
			var aging_times = {
				0: "Not Due",
				1: "Due Now",
				2: "> 30 Days",
				3: "> 60 Days",
				4: "> 90 Days",
				5: "> 180 Days",
			};
			$("#aging_detail").html('');
			var cur_cust = 0;
			var cust_total  = parseFloat("0.00");
			var group_total  = parseFloat("0.00");
			var final_total  = parseFloat("0.00");
			jQuery.each( aging_data, function( t,  data) {
				$("#aging_detail").append('<div id="aging_group_'+t+'" class="aging_group table-responsive"><h3 style="border-bottom: 1px solid #bbb; padding-bottom: 10px;">'+aging_times[t]+'</h3></div>');
				cur_cust = 0;
				group_total = parseFloat("0.00");
				jQuery.each( data.items, function( cust,  items) {
					$("#aging_group_"+t+"").append('<h4>'+cust+'</h4><table id="aging_table_'+t+'_'+cur_cust+'" class="table sortable"><thead><tr><th data-defaultsort="asc">Due Date</th><th>Voucher/Invoice</th><th class="text_right">Amount</th></tr></thead><tbody></tbody><tfoot></foot></table>');
					cust_total = parseFloat("0.00");
					jQuery.each( items, function( i,  item) {
						$("#aging_table_"+t+"_"+cur_cust+" tbody").append('<tr><td class="width_180"  data-dateformat="MM/DD/YYYY">'+item.DueDate+'</td><td>'+item.InvoiceId+'</td><td class="text_right">'+item.AmountCur+'</td></tr>');
						cust_total = parseFloat(cust_total) + parseFloat(item.RawNum);
						group_total = parseFloat(group_total) + parseFloat(item.RawNum);
						final_total = parseFloat(group_total) + parseFloat(item.RawNum);
					});
					$("#aging_table_"+t+"_"+cur_cust+" tfoot").append('<tr><td colspan="2"><strong>TOTAL</strong></td><td class="text_right"><strong>'+numeral(cust_total).format('0,0.00')+'</strong></tr>');
					cur_cust++;
				});
				$("#aging_group_"+t).append('<div class="row" style="margin-left: 0px; margin-right: 0px;"><div class="col-xs-6"><h4 style="margin-top: 0px;"><strong>Grand Total:</strong></h4></div><div class="col-xs-6" style="text-align: right;"><h4 style="margin-top: 0px;"><strong>'+numeral(group_total).format('0,0.00')+'</strong></h4></div></div>');
			});
			$("#chart_box").append('<div style="padding-top: 305px; text-align: center; font-size: 12px; color: rgb(84, 84, 84);">Grand Total: '+numeral(Math.round(aging.data.chart[0][1] + aging.data.chart[1][1] + aging.data.chart[2][1] + aging.data.chart[3][1] + aging.data.chart[4][1] + aging.data.chart[5][1] )).format('0,0')+'</div>');
			$.bootstrapSortable(false);
		} else {
			$("#chart_box").html('');
			$("#aging_detail").html('<center><h4 style="margin-top: 20px;">No Data Found</h4></center>')
		}
	});
}

function invAdjData(user_token, company_id) {
	hold_company_id = company_id;
	$("#inv_adj_detail").html('<div id="loading"><img src="images/spin.gif" /></div>');
	if(typeof company_id == "undefined") { company_id = "1"; }
	$("#inv_adj_detail").show();
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
	var startDateTxt = document.getElementById("invAdjStartDatePicker");
	var endDateTxt = document.getElementById("invAdjEndDatePicker");
	startDateTxt.value = startDate;
	endDateTxt.value = endDate;
	$('#invAdjStartDatePicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('#invAdjEndDatePicker').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$.get("/src/inventory_adjustments.php?user_token="+user_token+"&company="+company_id + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value,function(result) {
		var inv_adj = jQuery.parseJSON(result);
		if (inv_adj.count > 0) {
			$("#inv_adj_detail").html('');
			jQuery.each(inv_adj.data, function (x, item) {
				//var itemString = item.Item.split(".");
				var itemString = item.Item.split(/[\.|\/]+/);
				$("#inv_adj_detail").append('<div id="item_' + itemString[0] +'" class="table-responsive"><table id="item_table_' + itemString[0] +'" class="table_sortable"><thead></thead><tbody><tr><td><strong>' + item.Item + ' / ' + item.Name + ' / ' + item.ItemGroup + '</strong></td></tr></tbody><tfoot></tfoot></table></div>');
				$("#inv_adj_detail").append('<div id="item_' + itemString[0] + '_line" class="table-responsive"><table id="item_table_' + itemString[0]+ '_line" class="table sortable"><thead><tr><th class="width_180">Date</th><th class="width_180">User</th><th class="width_180">Voucher</th><th class="width_180 text_right">Amount</th></tr></thead><tbody></tbody><tfoot></tfoot></table></div>')
				jQuery.each(item, function(y, line) {
					if(typeof line.Amount != "undefined") {
						$("#item_" + itemString[0] + "_line tbody").append('<tr><td>' + line.Date + '</td><td>' + line.User + '</td><td>' + line.Voucher + '</td><td class="text_right">' + (line.Amount) + '</td></tr>');
					}
				});
				$("#item_" + itemString[0] + "_line tfoot").append('<tr><td></td><td></td><td><strong>Subtotal<strong</td><td class="text_right"><strong>' + item.SubtotalString + '</strong></td></tr>');
			});
			$("#inv_adj_detail").append('<div class="table-responsive"><table class="table sortable"><tr><td class="width_180"></td><td class="width_180"></td><td class="width_180"><strong>Grand Total</strong></td><td class="width_180 text_right"><strong>' + inv_adj.Total + '</strong></td></tr></table></div>');
			$.bootstrapSortable(false);
		} else {
			$("#inv_adj_detail").html('');
			$("#inv_adj_detail").html('<center><h4 style="margin-top: 20px;">No Data Found</h4></center>')
		}
	});
}

function refreshInvAdjDates(user_token) {
	$("#inv_adj_detail").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#inv_adj_detail").show();
	var startDateTxt = document.getElementById("invAdjStartDatePicker");
	var endDateTxt = document.getElementById("invAdjEndDatePicker");
	$.get("/src/inventory_adjustments.php?user_token="+user_token+"&company="+hold_company_id + "&start_date=" + startDateTxt.value + "&end_date=" + endDateTxt.value,function(result) {
		var inv_adj = jQuery.parseJSON(result);
		if (inv_adj.count > 0) {
			$("#inv_adj_detail").html('');
			jQuery.each(inv_adj.data, function (x, item) {
				//var itemString = item.Item.split(".");
				var itemString = item.Item.split(/[\.|\/]+/);
				$("#inv_adj_detail").append('<div id="item_' + itemString[0] +'" class="table-responsive"><table id="item_table_' + itemString[0] +'" class="table_sortable"><thead></thead><tbody><tr><td><strong>' + item.Item + ' / ' + item.Name + ' / ' + item.ItemGroup + '</strong></td></tr></tbody><tfoot></tfoot></table></div>');
				$("#inv_adj_detail").append('<div id="item_' + itemString[0] + '_line" class="table-responsive"><table id="item_table_' + itemString[0]+ '_line" class="table sortable"><thead><tr><th class="width_180">Date</th><th class="width_180">User</th><th class="width_180">Voucher</th><th class="width_180 text_right">Amount</th></tr></thead><tbody></tbody><tfoot></tfoot></table></div>')
				jQuery.each(item, function(y, line) {
					if(typeof line.Amount != "undefined") {
						$("#item_" + itemString[0] + "_line tbody").append('<tr><td>' + line.Date + '</td><td>' + line.User + '</td><td>' + line.Voucher + '</td><td class="text_right">' + (line.Amount) + '</td></tr>');
					}
				});
				$("#item_" + itemString[0] + "_line tfoot").append('<tr><td></td><td></td><td><strong>Subtotal<strong</td><td class="text_right"><strong>' + item.SubtotalString + '</strong></td></tr>');
			});
			$("#inv_adj_detail").append('<div class="table-responsive"><table class="table sortable"><tr><td class="width_180"></td><td class="width_180"></td><td class="width_180"><strong>Grand Total</strong></td><td class="width_180 text_right"><strong>' + inv_adj.Total + '</strong></td></tr></table></div>');
			$.bootstrapSortable(false);
		} else {
			$("#inv_adj_detail").html('');
			$("#inv_adj_detail").html('<center><h4 style="margin-top: 20px;">No Data Found</h4></center>')
		}
	});
}

$("body").on("click",".inv_adj_tab",function(event) {
	event.preventDefault();
	var company = $(this).data("tab");
	console.log(company);
	$("#inv_adj_tabs li").removeClass("active");
	$("#inv_adj_company_"+company).addClass("active");
	invAdjData(user_token,company);
});

$("body").on("click",".aging_tab",function(event) {
	event.preventDefault();
	var company = $(this).data("tab");
	console.log(company);
	$("#aging_tabs li").removeClass("active");
	$("#tab_company_"+company).addClass("active");
	agingChart(user_token,company);
});
