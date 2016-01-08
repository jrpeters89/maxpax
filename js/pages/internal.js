$("body").on("click", "#main_menu li ul li a", function (event) {

    companyInfo(user_token, 1);

});

function updateInternal(user_token) {

    companyInfo(user_token, 1);
}