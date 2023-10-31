// Listing User Details
if ($("#employee_tbl").length) {
    $("#employee_tbl").DataTable({
        aLengthMenu: [
            [10, 30, 50, -1],
            [10, 30, 50, "All"],
        ],
        iDisplayLength: 10,
        language: {
            search: "",
        },
        ajax: {
            type: "POST",
            url: aurl + "/employee/listing",
        },
        columns: [
            { data: "0" },
            { data: "1" },
            { data: "2" },
            { data: "3" },
            { data: "4" },
            { data: "5" },
            { data: "6" },
            { data: "7" },
        ],
    });
}

$(document).ready(function() {
    $("#filename_employee").change(function(events) {
        var tmppath = URL.createObjectURL(events.target.files[0]);
        $(".empimage")
            .fadeIn("fast")
            .attr("src", URL.createObjectURL(events.target.files[0]));
    });
    $("#employee_form").validate({
        rules: {
            image: {
                extension: "png|jpg|jpeg",
            },
            role: {
                required: true,
            },
            branch: {
                required: true,
            },
            department: {
                required: true,
            },
            designation: {
                required: true,
            },
            email: {
                required: true,
                email: true,
                employeeEmailCheck: true,
            },
            code: {
                required: true,
                employeeCodeCheck: true,
            },
            password: {
                required: true,
                minlength: 8,
                pwcheck: true,
            },
            confirmpassword: {
                required: true,
                equalTo: "#password",
            },
            prefix: {
                required: true,
            },
            first_name: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            middle_name: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            last_name: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            phone: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            address: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            gender: {
                required: true,
            },
            dob: {
                required: true,
            },
            joining_date: {
                required: true,
            },
        },
        messages: {
            image: {
                extension: "Allow only png / jpg / jpeg extension",
            },
           
            role: {
                required: "Please Select Role",
            },
            branch: {
                required: "Please Select Branch",
            },
            department: {
                required: "Please Select Department",
            },
            designation: {
                required: "Please Select Designation",
            },
            email: {
                required: "Please Enter Email",
                employeeEmailCheck: "Email Name Already Exists",
            },
            code: {
                required: "Please Enter Code",
                employeeCodeCheck: "Code Name Already Exists",
            },
            password: {
                required: "Please Enter Password",
                minlength: "Your Password Must Be At Least 8 Characters Long",
                pwcheck: "Please Enter Atleast One Uppercase, Number And Special Character!",
            },
            confirmpassword: {
                required: "This value should not be blank.",
            },
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
            phone: {
                required: "Please Enter Mobile Number.",
                number: "Only Numbers Allow",
                minlength: "Minimum 10 Digits Required",
                maxlength: "Maximum 10 Digits required",
            },
            address: {
                required: "Please Enter Address.",
            },
            gender: {
                required: "Please Select gender.",
            },
            dob: {
                required: "Please Select Date Of Birth.",
            },
            joining_date: {
                required: "Please Select Joining Date.",
            },
        },
        errorPlacement: function(error, element) {
            if (
                element.parents("div").hasClass("has-feedback") ||
                element.hasClass("select2-hidden-accessible")
            ) {
                error.appendTo(element.parent());
            }else if(element.parents("div").hasClass("uploader")||element.hasClass("datepicker")||element.hasClass("dobdatePicker")){
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
    });
    $.validator.addMethod("employeeEmailCheck", function(value) {
        var x = 0;
        var id = $(".employee_id").val();
        var x = $.ajax({
            url: aurl + "/employee/employee-check",
            type: "POST",
            async: false,
            data: { email: value, id: id },
        }).responseText;
        if (x != 0) {
            return false;
        } else return true;
    });
    $.validator.addMethod("employeeCodeCheck", function(value) {
        var x = 0;
        var id = $(".employee_id").val();
        var x = $.ajax({
            url: aurl + "/employee/code-check",
            type: "POST",
            async: false,
            data: { code: value, id: id },
        }).responseText;
        if (x != 0) {
            return false;
        } else return true;
    });
    $.validator.addMethod("pwcheck", function(value, element) {
        return (
            value.match(/[a-z]/) &&
            value.match(/[A-Z]/) &&
            value.match(/[0-9]/) &&
            value.match(/[_!#@$%^&*]/)
        );
    }),
    
    $("#password_form").validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
                pwcheck: true,
            },
            confirmpassword: {
                required: true,
                equalTo: "#password",
            },
        },

        messages: {
            password: {
                required: "Please Enter Password",
                minlength: "Your Password Must Be At Least 8 Characters Long",
                pwcheck: "Please Enter Atleast One Uppercase, Number And Special Character!",
            },
            confirmpassword: {
                required: "This value should not be blank.",
            },
        },

        highlight: function(element) {
            $(element).removeClass("error");
        },
    });




     /* Add Country Modal */
     $("body").on("click", ".change-pwd", function() {
        $("#password_form").validate().resetForm();
        $("#password_form").trigger("reset");
        $("#password_modal").modal("show");
        $(".id").val($(this).data("id"));
        $(".submit_password").text("Save");
    });

    $(".submit_password").on("click", function(event) {
        event.preventDefault();
        var form = $("#password_form")[0];
            var formData = new FormData(form);
            if ($("#password_form").valid()) {
                $.ajax({
                    url: aurl + "/employee/change-password",
                    type: "POST",
                    dataType: "JSON",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#password_modal").modal("hide");
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




        /* adding and updating employee data */
        $(".submit_employee").on("click", function(event) {
            event.preventDefault();
            var form = $("#employee_form")[0];
            var formData = new FormData(form);
            if ($("#employee_form").valid()) {
                $.ajax({
                    url: aurl + "/employee",
                    type: "POST",
                    dataType: "JSON",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#employee_modal").modal("hide");
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

    /* display update employee modal */
    $("body").on("click", ".employee_edit", function(event) {
        var id = $(this).data("id");
        $(".employee_id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/employee/{" + id + "}",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#employee_form").validate().resetForm();
                    $("#employee_form").trigger("reset");
                    $("#title_employee_modal").text("Update employee");
                    $("#employee_modal").modal("show");
                    $(".submit_employee").text("Update employee");
                    $(".name").val(data.name);
                } else {
                    toaster_message(data.message, data.icon, data.redirect_url);
                }
            },
            error: function(request) {
                toaster_message(
                    "Something Went Wrong! Please Try Again.",
                    "error"
                );
            },
        });
    });
});