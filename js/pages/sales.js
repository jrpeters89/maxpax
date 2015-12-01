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
						ticks: [[0, "Not Due"],[1,"Current"],[2,"30 Days"],[3,"60 Days"],[4,"90 Days"],[5,"180 & Over"]]
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
			jQuery.each( aging_data, function( t,  data) {
				$("#aging_detail").append('<div id="aging_group_'+t+'" class="aging_group table-responsive"><h3>'+aging_times[t]+'</h3></div>');
				cur_cust = 0;
				jQuery.each( data.items, function( cust,  items) {
					$("#aging_group_"+t+"").append('<h4>'+cust+'</h4><table id="aging_table_'+t+'_'+cur_cust+'" class="table sortable"><thead><tr><th data-defaultsort="asc" data-dateformat="MM/DD/YYYY">Due Date</th><th>Voucher/Invoice</th><th class="text_right">Amount</th></tr></thead><tbody></tbody><tfoot></foot></table>');
					cust_total = parseFloat("0.00");
					jQuery.each( items, function( i,  item) {
						$("#aging_table_"+t+"_"+cur_cust+" tbody").append('<tr><td class="width_180">'+item.DueDate+'</td><td>'+item.InvoiceId+'</td><td class="text_right">'+item.AmountCur+'</td></tr>');
						cust_total = parseFloat(cust_total) + parseFloat(item.RawNum);
					});
					$("#aging_table_"+t+"_"+cur_cust+" tfoot").append('<tr><td colspan="2"><strong>TOTAL</strong></td><td class="text_right"><strong>'+numeral(cust_total).format('0,0.00')+'</strong></td><td></tr>');
					cur_cust++;
				});
				//$("#aging_table_"+t+" tfoot").append('<tr><td colspan="2"><strong>TOTAL</strong></td><td class="text_right"><strong>'+data.amount+'</strong></td><td></tr>');
			});
			$.bootstrapSortable(false);
		} else {
			$("#chart_box").html('');
			$("#aging_detail").html('<center><h4 style="margin-top: 20px;">No Data Found</h4></center>')
		}
	});
}

$("body").on("click",".aging_tab",function(event) {
	event.preventDefault();
	var company = $(this).data("tab");
	$("#aging_tabs li").removeClass("active");
	$("#tab_company_"+company).addClass("active");
	agingChart(user_token,company);
});
