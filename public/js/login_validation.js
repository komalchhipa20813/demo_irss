$(function() {
    /* ajax set up */
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $(".login_from").validate({
        rules: {
            email: "required",
            password: {
                required: true,
                // passwordCheck: true,
            },
            login_type: {
                required: true,
            },
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
        messages: {
            email: "Please Enter Email Address",
            password: {
                required: "Please Enter Password",
                passwordCheck: "Please Enter Valid Password ",
            },
            login_type: {
                required: "Please Select Log In Type",
            },
        },
        errorPlacement: function(error, element) {
            if (element.hasClass("form-check-input")) {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
    });
    $.validator.addMethod("passwordCheck", function(value) {
        var email = $("#userEmail").val();
        var x = $.ajax({
            url: aurl + "/login/password-check",
            type: "POST",
            async: false,
            data: { password: value, email: email },
        }).responseText;
        if (x == 0) {
            return false;
        } else return true;
    });
});