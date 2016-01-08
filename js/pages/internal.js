$("body").on("click", "#main_menu li ul li a", function (event) {

    companyInfo(user_token, 2);

});

function updateInternal(user_token, company_id) {
    console.log("Internal Button Clicked");
    $("#main_menu li").removeClass("active");
    $(".page_container").hide();
    companyInfo(user_token, company_id);

}

function updateInternalDocuments(user_token, company_id) {
    documentList(user_token, company_id);
}