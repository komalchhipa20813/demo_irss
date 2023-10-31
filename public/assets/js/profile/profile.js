$(document).ready(function() {
    $("#profile_form").validate({
        rules: {
            prefix: {
                required: true,
            },
            first_name: {
                required: true,
            },
            middle_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            mobile_no: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10,
            },
            dob:{
                required: true,
            },
            address: {
                required: true,
            },
            aadhaarcard_number: {
                required: true,
                customeraadhaarcardCheck: true,
            },
            pancard_number: {
                required: true,
                customerPanCardCheck: true,
            },
        },
        messages: {
            prefix: {
                required: "Please Select Prefix",
            },
            first_name: {
                required: "Please Enter First Name.",
            },
            middle_name: {
                required: "Please Enter Middle Name.",
            },
            last_name: {
                required: "Please Enter Last Name.",
            },
            mobile_no: {
                required: "Please Enter Mobile Number.",
                number: "Only Numbers Allow",
                minlength: "Minimum 10 Digits Required",
                maxlength: "Maximum 10 Digits required",
            },
            address: {
                required: "Please Enter Address.",
            },
            aadhaarcard_number: {
                required: "Please Enter Adhar Card Number",
                customeraadhaarcardCheck: "Aadhaar Card Number Already Exists",
            },
            pancard_number: {
                required: "Please Enter Pan Card Number",
                customerPanCardCheck: "Pan Card Number Already Exists",
            },
        },
        errorPlacement: function(error, element) {
            if (
                element.parents("div").hasClass("has-feedback") ||
                element.hasClass("select2-hidden-accessible")
            ) {
                error.appendTo(element.parent());
            } else if (
                element.parents("div").hasClass("uploader") ||
                element.hasClass("datepicker")
            ) {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
        normalizer: function(value) {
            return $.trim(value);
        },
    });

    $.validator.addMethod("customeraadhaarcardCheck", function(value) {
        var x = 0;
        var id = $(".customer_id").val();
        var x = $.ajax({
            url: aurl + "/customer/customer-check",
            type: "POST",
            async: false,
            data: { adharcard_number: value, id: id },
        }).responseText;
        if (x != 0) {
            return false;
        } else return true;
    });

    $.validator.addMethod("customerPanCardCheck", function(value) {
        var x = 0;
        var id = $(".customer_id").val();
        var x = $.ajax({
            url: aurl + "/customer/customer-pan-card-check",
            type: "POST",
            async: false,
            data: { pan_card: value, id: id },
        }).responseText;
        if (x != 0) {
            return false;
        } else return true;
    });

    /* adding and updating employee data */
    $(".submit_customer").on("click", function(event) {
        event.preventDefault();
        var form = $("#profile_form")[0];
        var formData = new FormData(form);
        if ($("#profile_form").valid()) {
            $.ajax({
                url: aurl + "/updateProfile",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    data.is_customer ?
                        customer_toaster_message(data.message, data.icon) :
                        toaster_message(
                            data.message,
                            data.icon,
                            data.redirect_url
                        );
                },
                error: function(request) {
                    toaster_message(
                        "Something Went Wrong! Please Try Again.",
                        "error"
                    );
                },
            });
        }
    });
});
