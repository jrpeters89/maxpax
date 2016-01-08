$("body").on("click", "#main_menu li ul li a", function (event) {
    companyInfo(' ', 1);
    $("sales_link").addClass("menu_99");
    appSetup(99,function () {
        console.log("Page Changed");
        $.getScript("/js/pages/documents.js", function () {
            console.log("Documents Script Loaded");
            changePage(company.default_page);
        });
    });
});