function userList(user_token) {
	$("#user_list").html('<div id="loading"><img src="images/spin.gif" /></div>');
	$("#user_container").show();
	$.get("/src/users.php?act=list&user_token="+user_token,function(result) {
		var users = jQuery.parseJSON(result);
		if(users.active == true) {
			$("#user_list").html("");
			jQuery.each( users.list, function( i, val ) {
				if(users.company == 1) {
					var second_row = '<p>'+val.company_name+'</p>';
				} else {
					var second_row = '<p>'+val.email+'</p>';
				}
				$("#user_list").append('<a href="#" id="user_'+val.user_id+'" class="list-group-item user_item" data-user="'+val.user_id+'"><h4>'+val.first+' '+val.last+'</h4>'+second_row+'<div class="edit_contain"><i class="glyphicon glyphicon-pencil"></i></div></a>');
			});
		} else {
			$("#user_list").html("No Users Available");
		}
	});
}

$("body").on("click","#new_user",function(event) {
	event.preventDefault();
	$("#newUser").modal("show");
});

$('#newUser').on('show.bs.modal', function (e) {
	if(company_list == false) {
		$.get("/src/company.php?act=list&user_token="+user_token,function(result) {
			var companies = jQuery.parseJSON(result);
			if(companies.active == true) {
				$(".company_list").html("");
				jQuery.each(companies.options, function( i, val ) {
					$(".company_list").append('<option value="'+val.id+'">'+val.name+'</option>');
				});
				company_list = true;
			} else {
				alert("You do not have the required permissions");
				$("#newUser").modal("hide");
			}
		});
	}
});

$("body").on("click","#delete_user",function(event) {
	event.preventDefault();
	var user_id = $("#update_user_id").val();
	if(confirm("Are you sure you want to delete this user?") == true) {
		$.post("/src/users.php?act=delete&user_token="+user_token,{user_id:user_id},function(result) {
			if(result == true) {
				$("#user_"+user_id).hide();
			} else {
				alert(result);
			}
		});
		$("#updateUser").modal("hide");
	} else {
		//$("#updateUser").modal("hide");
	}
});

$("#new_user_form").submit(function(event) {
	event.preventDefault();
	$("#new_user_submit").html("Adding...").attr("disabled",true);
	var data = JSON.stringify(jQuery('#new_user_form').serializeArray());
	$.post('/src/register.php?act=create&user_token='+user_token,data, function(result) {
		var create = jQuery.parseJSON(result);
		if(create.success == true) {
			$("#user_list").append('<a href="#" id="user_'+create.user_id+'" class="list-group-item user_item" data-user="'+create.user_id+'"><h4>'+create.new_name+'</h4><p>'+create.email+'</p><div class="edit_contain"><i class="glyphicon glyphicon-pencil"></i></div></a>');
			$("#newUser").modal("hide");
		} else {
			alert(create.error_msg);
		}
		$("#new_user_submit").html("Add User").removeAttr("disabled");
	});
});

$("body").on("click",".user_item",function(event) {
	event.preventDefault();
	var user_id = $(this).data("user");
	$("#update_user_id").val(user_id);
	if(company_list == false) {
		$.get("/src/company.php?act=list&user_token="+user_token,function(result) {
			var companies = jQuery.parseJSON(result);
			if(companies.active == true) {
				$(".company_list").html("");
				jQuery.each(companies.options, function( i, val ) {
					$(".company_list").append('<option value="'+val.id+'">'+val.name+'</option>');
				});
				company_list = true;
			} else {
				alert("You do not have the required permissions");
				$("#updateUser").modal("hide");
			}
		});
	}
	$.get("/src/users.php?act=info&user_token="+user_token,{user_id:user_id},function(result) {
		var user = jQuery.parseJSON(result);
		if(user.active == true) {
			$("#user_first_update").val(user.info.first);
			$("#user_last_update").val(user.info.last);
			$("#user_email_update").val(user.info.email);
			$("#company_id_update").val(user.info.company);
			$("#access_level_update").val(user.info.access_level);
			$("#updateUser").modal("show");
		} else {
			alert("You do not have the required permissions");
			$("#updateUser").modal("hide");
		}
	});
});

$("#update_user_form").submit(function(event) {
	event.preventDefault();
	$("#update_user_submit").html("Updating...").attr("disabled",true);
	var data = JSON.stringify(jQuery('#update_user_form').serializeArray());
	$.post('/src/register.php?act=update&user_token='+user_token,data, function(result) {
		var update = jQuery.parseJSON(result);
		if(update.success == true) {
			$("#user_"+update.user_id+" h4").html(update.new_name);
			$("#user_"+update.user_id+" p").html(update.email);
			$("#updateUser").modal("hide");
		} else {
			alert(update.error_msg);
		}
		$("#update_user_submit").html("Update User").removeAttr("disabled");
	});
});