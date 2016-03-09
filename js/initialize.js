var hold_company_id;

$("body").on("click", "#main_menu li a", function (event) {
    event.preventDefault();
    var page = $(this).data("link");
    $("#main_menu li").removeClass("active");
    $(".page_container").hide();
    changePage(page);
    if ($(document).width() < 768) {
        $(".navbar-toggle").click();
    }
});

function changePage(page) {
    if (page == "logout") {
        logout();
    } else {
        $("#" + page + "_link").addClass("active");
        $("#" + page + "_container").show();
        if (page == "production") {
            productionCheck(user_token);
        } else if (page == "inventory") {
            inventoryCheck(user_token, hold_company_id);
        } else if (page == "opensales") {
            $("#opensales_tabs li").removeClass("active");
            $("#tab_list").addClass("active");
            opensalesCheck(user_token);
        } else if (page == "documents") {
            documentList(user_token, hold_company_id);
        } else if (page == "user") {
            userList(user_token);
        } else if (page == "aging") {
            agingChart(user_token);
        } else if (page == "sales") {
            salesData(user_token);
        } else if (page == "shipments") {
            shippingData(user_token, hold_company_id);
        } else if (page == "inv_trans") {
            //$("#inv_trans_link").addClass("active");
            inventoryTransactions(user_token, hold_company_id);
        } else if (page == "inv_quarantine") {
            invQuarantineCheck(user_token, hold_company_id);
        } else if (page == "packaging_inventory") {
            packagingCheck(user_token, hold_company_id);
        } else if (page == "prod_trans") {
            productionTransactions(user_token);
        } else if (page == "recv_trans") {
            receivingTransactions(user_token, hold_company_id);
        } else {

            //Do Nothing
        }
    }
}

function companyInfo(user_token, company_id) {
    console.log("Company Id is " + company_id);
    hold_company_id = company_id;
    $.get("/src/company.php?user_token=" + user_token + "&company_id=" + company_id, function (result) {
        var company = jQuery.parseJSON(result);
        console.log("Company Id returned is " + company.id);
        $("#company_logo").html('<img src="images/' + company.logo + '_logo.png" style="height: 20px; width: 162px;" />');
        if (company.active == true) {
            appSetup(company, function () {
                console.log("Page Changed");
                $.getScript("/js/pages/documents.js", function () {
                    console.log("Documents Script Loaded");
                    changePage(company.default_page);
                });
            });
        } else {
            logout();
        }
    });
}

function appSetup(company, callback) {

    var callback_active = false;
    $(".menu_1").fadeOut();
    $(".menu_2").fadeOut();
    $(".menu_3").fadeOut();
    $(".menu_4").fadeOut();
    $(".menu_5").fadeOut();
    $(".menu_6").fadeOut();
    $(".menu_7").fadeOut();
    $(".menu_8").fadeOut();
    $(".menu_" + company.id).fadeIn();

    console.log("Documents is " + company.documents);

    if (company.documents > 0) {
        $(".documents").fadeIn();
    } else {
        //$(".documents, #documents_container").remove();
        $(".documents").fadeOut();
        $("#documents_container").fadeOut();
    }
    if (company.access > 0) {
        $.getScript("/js/pages/users.js", function () {
            console.log("Admin Script Loaded")
        });
        $(".admin").fadeIn();
    } else {
        $(".admin, .admin_container").remove();
    }

    if (company.id > 0) {
        if (company.id == 99) {
            $.getScript("/js/jquery.flot.js", function () { //Load Main Chart JS First
                console.log("Flot Charts Loaded");
                getScripts(["/js/pages/internal.js", "/js/pages/sales.js", "/js/jquery.flot.resize.js", "/js/jquery.flot.time.js", "/js/jquery.flot.axislabels.js"], function () {
                    console.log("Internal Scripts Loaded");
                    callback();
                });
            });
        } else if (company.id == 8) {
            getScripts(["/js/pages/wf-young.js"], function () {
                console.log("WF Young Scripts Loaded");
                callback();
            });
        }
        else if (company.id == 7) {
            getScripts(["/js/pages/gopicnic.js"], function () {
                console.log("GoPicnic Scripts Loaded");
                callback();
            });
        } else if (company.id == 6) {
            getScripts(["/js/pages/energems.js"], function () {
                console.log("Energems Scripts Loaded");
                callback();
            });
        } else if (company.id == 5) {
            $.getScript("/js/pages/adm.js", function () {
                console.log("ADM Script Loaded");
                callback();
            });
        } else if (company.id == 4) {
            $.getScript("/js/pages/portion-pac.js", function () {
                console.log("Portion Pac Script Loaded");
                callback();
            });
        } else if (company.id == 3) {
            $.getScript("/js/pages/nourish.js", function () {
                console.log("Nourish Script Loaded");
                callback();
            });
        } else if (company.id == 1) {
            $.getScript("/js/jquery.flot.js", function () { //Load Main Chart JS First
                console.log("Flot Charts Loaded");
                getScripts(["/js/pages/sales.js", "/js/jquery.flot.resize.js", "/js/jquery.flot.time.js", "/js/jquery.flot.axislabels.js"], function () {
                    console.log("Sales Scripts Loaded");
                    callback();
                });
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

$("body").on("click", "#logout_btn", function (event) {
    event.preventDefault();
    logout();
});

function getScripts(scripts, callback) {
    var progress = 0;
    var internalCallback = function () {
        console.log(scripts[progress] + " Loaded");
        if (++progress == scripts.length) {
            callback();
        }
    };

    scripts.forEach(function (script) {
        console.log("Loading script: " + script);
        $.getScript(script, internalCallback);
    });
};

function logout() {
    Cookies.expire("user_token");
    window.location.href = "login.html";
}
