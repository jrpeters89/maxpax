$("body").on("click","#main_menu li a",function(event) {
	event.preventDefault();
	var page = $(this).data("link");
	$("#main_menu li").removeClass("active");
	$(".page_container").hide();
	changePage(page);
	if($(document).width() < 768) {
		$(".navbar-toggle").click();
	}
});

function changePage(page) {
	if(page == "logout") {
		logout();
	} else {
		$("#"+page+"_link").addClass("active");
		$("#"+page+"_container").show();
		if(page == "production") {
			productionCheck(user_token);
		} else if(page == "inventory") {
			inventoryCheck(user_token);
		} else if (page == "opensales") {
			$("#opensales_tabs li").removeClass("active");
			$("#tab_list").addClass("active");
			opensalesCheck(user_token);
		} else if(page == "documents") {
			documentList(user_token);
		} else if (page == "user") {
			userList(user_token);
		} else if (page == "sales") {
			salesData(user_token);
		} else {
			//Do Nothing
		}
	}
}

function companyInfo(user_token) {
	$.get("/src/company.php?user_token="+user_token,function(result) {
		var company = jQuery.parseJSON(result);
		$("#company_logo").html('<img src="images/'+company.logo+'_logo.png" style="height: 20px; width: 162px;" />');
		if(company.active == true) {
			appSetup(company, function() {
				console.log("Page Changed");
				$.getScript("/js/pages/documents.js", function(){
					console.log("Documents Script Loaded");
					changePage(company.default_page);
				});
			});
		} else {
			logout();
		}
	});
}

function appSetup(company,callback) {

	var callback_active = false;
	$(".menu_"+company.id).fadeIn();
	if(company.documents > 0) {
		$(".documents").fadeIn();
	} else {
		$(".documents, #documents_container").remove();
	}
	if(company.access > 0) {
		$.getScript("/js/pages/users.js", function(){ console.log("Admin Script Loaded") });
		$(".admin").fadeIn();
	} else {
		$(".admin, .admin_container").remove();
	}

	if(company.id > 0) {
		if (company.id == 4) {
			$.getScript("/js/pages/portion-pac.js", function(){
				console.log("Portion Pac Script Loaded");
				callback();
			});
		} else if(company.id == 3) {
			$.getScript("/js/pages/nourish.js", function(){
				console.log("Nourish Script Loaded");
				callback();
			});
		} else if (company.id == 1) {
			$.getScript("/js/pages/sales.js", function(){
				console.log("Sales Script Loaded");
				callback();
			});
		} else {
			//No Scripts to Load
			callback();
		}
	} else {
		//Do nothing
	}
	console.log("Setup Complete");
}

$("body").on("click","#logout_btn",function(event) {
	event.preventDefault();
	logout();
});

function logout() {
	Cookies.expire("user_token");
	window.location.href = "login.html";
}
