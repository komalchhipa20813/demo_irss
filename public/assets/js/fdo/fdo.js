// Listing fdo Details
if ($("#fdo_tbl").length) {
    function loadListing(filterdata){
        $("#fdo_tbl").DataTable({
            processing: true,
            serverSide: true,
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
                data:filterdata,
                url: aurl + "/fdo/listing",
            },
            columns: [
                { data: "0" },
                { data: "1" },
                { data: "2" },
                { data: "3" },
                { data: "4" },
                { data: "5" },
                { data: "6" },
            ],
        });
    }
    loadListing({});
}

// Listing FDO document Details
if ($("#document_tbl").length) {
    $("#document_tbl").DataTable({
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
            url: aurl + "/fdo/document/listing",
        },
        columns: [
            { data: "0" },
            { data: "1" },
            { data: "2" },
            { data: "3" },
            { data: "4" },
        ],
    });
}

$(document).ready(function() {
    /* display profile photo */
    $("#filename_fdo").change(function(events) {
        $("#fdo_image_preview").fadeIn("fast").attr("src", URL.createObjectURL(events.target.files[0]));
    });
    /* select and disabled service branch */
    $(".service_branch").change(function() {
        var valueSelected = $(this).val();

        if (valueSelected[0] == 0) {
            $(".select2-selection__choice")
                .next(".select2-selection__choice")
                .remove();
            $(".branch_val").prop("disabled", true);
        } else {
            $(".branch_val").prop("disabled", false);
        }
    });
    if ($("#fdo_form").length) {
        $("#fdo_form").validate({
            rules: {
                /* image: {
                    required: window.location.href.split("/")[4] == "create",
                    extension: "png|jpg|jpeg",
                }, */
                'code':{
                    required:true,
                    code:true,
                },
                "service_branch[]": {
                    required: true,
                },
                branch: {
                    required: true,
                },
                /* email: {
                    required: true,
                    email: true,
                    fdoEmailCheck: true,
                }, */
                secondary_email: {
                    email: true,
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
                /* business_category: {
                    required: true,
                }, */
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
                secondary_phone: {
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                    normalizer: function(value) {
                        return $.trim(value);
                    },
                },
                office_address: {
                    required: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    },
                },
                residential_address: {
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
                adharcard_number:{
                    required:true,
                    adharcard_number:true,
                },
                pancard_number:{
                    required:true,
                    pancard_number:true,
                }
            },
            messages: {
                /* image: {
                    required: "Please Upload FDO Photo",
                }, */
                'code':{
                    required:"Please Enter FDO Code",
                    code:"FDO Code Already Exists",
                },
                "service_branch[]": {
                    required: "Please Select Service Branch",
                },
                branch: {
                    required: "Please Select Branch",
                },
                /* business_category: {
                    required: "Please Select Business Category",
                }, */
                designation: {
                    required: "Please Select Designation",
                },
                /* email: {
                    required: "Please Enter Email",
                    fdoEmailCheck: "Email Name Already Exists",
                }, */
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
                secondary_phone: {
                    number: "Only Numbers Allow",
                    minlength: "Minimum 10 Digits Required",
                    maxlength: "Maximum 10 Digits required",
                },
                office_address: {
                    required: "Please Enter Office Address",
                },
                residential_address: {
                    required: "Please Enter Residential Address.",
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
                adharcard_number:{
                    required: "Please Enter Adhar Card Number",
                    adharcard_number:"Adhar Card Number Already Exists",
                },
                pancard_number:{
                    required: "Please Enter Pan Card Number",
                    pancard_number:"Pan Card Number Already Exists",
                }
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
        $.validator.addMethod(
            "fdoEmailCheck",
            function(value) {
                var x = 0;
                var id = $(".fdo_id").val();
                var x = $.ajax({
                    url: aurl + "/fdo/fdo-check",
                    type: "POST",
                    async: false,
                    data: { email: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );
        $.validator.addMethod(
            "code",
            function(value) {
                var x = 0;
                var id = $(".fdo_id").val();
                var x = $.ajax({
                    url: aurl + "/fdo/fdo-code",
                    type: "POST",
                    async: false,
                    data: { code: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );$.validator.addMethod(
            "adharcard_number",
            function(value) {
                var x = 0;
                var id = $(".fdo_id").val();
                var x = $.ajax({
                    url: aurl + "/fdo/adharcard_number",
                    type: "POST",
                    async: false,
                    data: { adharcard_number: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );
        $.validator.addMethod(
            "pancard_number",
            function(value) {
                var x = 0;
                var id = $(".fdo_id").val();
                var x = $.ajax({
                    url: aurl + "/fdo/pancard_number",
                    type: "POST",
                    async: false,
                    data: { pancard_number: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );
        $.validator.addMethod("pwcheck",function(value, element) {
            return (value.match(/[a-z]/) && value.match(/[A-Z]/) && value.match(/[0-9]/) && value.match(/[_!#@$%^&*]/));
        })
    }
    if ($("#document_form").length) {
        $("#document_form").validate({
            rules: {
                document_file: {
                    required: true,
                    extension: "png|jpg|jpeg|pdf",
                },
                document_number: {
                    required: true,
                },
            },
            messages: {
                document_file: {
                    required: "Please Upload Document Photo",
                },
                document_number: {
                    required: "Please Select Document Number",
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
        });
    }
    $(".document_type").change(function() {
        $(".document_detail").show(true);
    });

    /* adding and updating fdo data */
    $(".submit_fdo").on("click", function(event) {
        event.preventDefault();
        var form = $("#fdo_form")[0];
        var formData = new FormData(form);
        if ($("#fdo_form").valid()) {
            $.ajax({
                url: aurl + "/fdo",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toaster_message(data.message,data.icon,data.redirect_url);
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
    /* adding and updating document data */
    $("body").on("click", ".add_document", function(event) {
        event.preventDefault();
        var form = $("#document_form")[0];
        var formData = new FormData(form);
        formData.append(
            "document_name",
            $("#document_type").find(":selected").data("name")
        );

        if ($("#document_form").valid()) {
            $.ajax({
                url: aurl + "/fdo/document",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.status) {
                        $("#document_form").trigger("reset");
                        $(".document_type").val(0).trigger("change");
                        $('.document_type option[value='+data.document_type+']').prop('disabled', true)
                        document_toaster_message(data.message,data.icon,data.redirect_url);
                    }
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

    /* deleting document data */
    $("body").on("click", ".document_delete", function(event) {
        var id = $(this).data("id");
        $.ajax({
            url: aurl + "/fdo/document/delete/" + id + "/",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                document_toaster_message(data.message, data.icon);
            },
            error: function(request) {
                toaster_message(
                    "Something Went Wrong! Please Try Again.",
                    "error"
                );
            },
        });
    });

    $('body').on("click", ".filter_fdo_record", function(event){
        event.preventDefault();
        var data = {
            'fdo_code':$('#fdo_code').val(),
            'business_category':$('#business_category').val(),
            'home_branch':$('#home_branch').val(),
            'sales_manager':$('#sales_manager').val(),
            'pancard_no':$('#pancard_no').val(),
            'aadharcard_no':$('#aadharcard_no').val(),
            'mobile_no':$('#mobile_no').val(),
        }
        $('#search_criteria').modal('hide');
        $("#fdo_tbl").DataTable().destroy();
        loadListing(data);
    });
    $('body').on("click", ".reset_filter", function(event){
        event.preventDefault();
        $("#agent_filter_section").trigger("reset");
        $('.select2').val(0).trigger("change");
        $("#agent_filter_section input").val("");
        $('#search_criteria').modal('hide');
        $("#fdo_tbl").DataTable().destroy();
        loadListing({});
    });
    $.validator.addMethod("pwcheck",function(value, element) {
        return (value.match(/[a-z]/) && value.match(/[A-Z]/) && value.match(/[0-9]/) && value.match(/[_!#@$%^&*]/));
    })


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
                    url: aurl + "/fdo/change-password",
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
});