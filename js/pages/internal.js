$("body").on("click", "#main_menu li ul li a", function (event) {

    companyInfo(user_token, 2);

});

function updateInternal(user_token) {
    console.log("Internal Button Clicked");
    companyInfo(user_token, 1);

}