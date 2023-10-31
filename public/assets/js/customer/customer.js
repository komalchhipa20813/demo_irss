// Listing User Details
if ($("#customer_tbl").length) {
    $("#customer_tbl").DataTable({
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
            url: aurl + "/customer/listing",
            data: function (d) {
                d._token = _token;
                d.customer_code = $("#customer_code").val();
                d.customer_id = $("#customer_id").val();
                d.adharcard_number = $(".adharcard_number").val();
                d.pancard_number = $("#pancard_number").val();
            },
        },
        columns: [
            { data: "no" },
            { data: "customer_code" },
            { data: "customer_name" },
            { data: "address" },
            { data: "adharcard_number" },
            { data: "pancard_number" },
            { data: "action" },
        ],
    });
}

$(document).ready(function () {
    $("#customer_form").validate({
        rules: {
            prefix: {
                required: true,
            },
            company_name: {
                required: true,
            },
            first_name: {
                required: true,
            },
            last_name: {
                required: true,
            },
            mobile_no: {
                number: true,
                minlength: 10,
                maxlength: 10,
            },
            address: {
                required: true,
            },
            aadhaarcard_number: {
                required: function (value) {
                    return ($('#pancard_number').val() != '' || $("#prefix").find("option:selected").text() == "M/S")?false:true;
                },
                customeraadhaarcardCheck: true,
            },
            pancard_number: {
                required: function (value) {
                    return (($("#prefix").find("option:selected").text() == "M/S" && $('#gst_number').val() != "") || $('#aadhaarcard_number').val() != '')?false:true;

                },
                customerPanCardCheck: true,
            },
            gst_number: {
                required: function (value) {
                    return ($("#prefix").find("option:selected").text() == "M/S" && $('#pancard_number').val() == "")?true:false;
                },
            },
        },
        messages: {
            prefix: {
                required: "Please Select Prefix.",
            },
            company_name: {
                required: "Please Enter Company Name",
            },
            first_name: {
                required: "Please Enter First Name.",
            },
            last_name: {
                required: "Please Enter Last Name.",
            },
            mobile_no: {
                number: "Only Numbers Allow",
                minlength: "Minimum 10 Digits Required",
                maxlength: "Maximum 10 Digits required",
            },
            address: {
                required: "Please Enter Address.",
            },
            aadhaarcard_number: {
                required: "Please Enter Aadhar Card Number.",
                customeraadhaarcardCheck: "Aadhaar Card Number Already Exists",
            },
            pancard_number: {
                required: "Please Enter Pan Card Number.",
                customerPanCardCheck: "Pan Card Number Already Exists",
            },
            gst_number:{
                required: "Please Enter GST Number.",
            }
        },
        errorPlacement: function (error, element) {
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
        highlight: function (element) {
            $(element).removeClass("error");
        },
        normalizer: function (value) {
            return $.trim(value);
        },
    });

    $.validator.addMethod("customeraadhaarcardCheck", function (value) {
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

    $.validator.addMethod("customerPanCardCheck", function (value) {
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
    $(".submit_customer").on("click", function (event) {
        event.preventDefault();
        var form = $("#customer_form")[0];
        var formData = new FormData(form);
        if ($("#customer_form").valid()) {
            $.ajax({
                url: aurl + "/customer",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    data.is_customer
                        ? customer_toaster_message(
                              data.message,
                              data.icon,
                              data.id
                          )
                        : toaster_message(
                              data.message,
                              data.icon,
                              data.redirect_url
                          );
                },
                error: function (request) {
                    toaster_message(
                        "Something Went Wrong! Please Try Again.",
                        "error"
                    );
                },
            });
        }
    });

    /* display update employee modal */
    $("body").on("click", ".employee_edit", function (event) {
        var id = $(this).data("id");
        $(".employee_id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/employee/{" + id + "}",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function (data) {
                if (data.status) {
                    $("#customer_form").validate().resetForm();
                    $("#customer_form").trigger("reset");
                    $("#title_employee_modal").text("Update employee");
                    $("#employee_modal").modal("show");
                    $(".submit_employee").text("Update employee");
                    $(".name").val(data.name);
                } else {
                    toaster_message(data.message, data.icon, data.redirect_url);
                }
            },
            error: function (request) {
                toaster_message(
                    "Something Went Wrong! Please Try Again.",
                    "error"
                );
            },
        });
    });

    $(".prefix").on("change", function () {
        prefix_fields();
    });
    prefix_fields();
});

function prefix_fields() {
    var prefix = $(".prefix").val();
    var update_customer = $("#update_customer").val();

    if (update_customer == "2") {
        if (prefix == "M/S") {
            $(".ms-prefix").html("");
            $(".first_name").html(
                '<div class="mb-3"><label for="first_name" class="  control-label">Company Name <span class="text-danger"> * </span></label><input type="text" class="form-control" name="company_name" id="company_name" value="" autocomplete="off" placeholder="Enter Company Name"></div>'
            );
        } else {
            $(".first_name").html(
                '<div class="mb-3"><label for="first_name" class="  control-label">First Name <span class="text-danger"> * </span></label><input type="text" class="form-control" name="first_name" id="first_name" value="" autocomplete="off" placeholder="Enter First Name"></div>'
            );
            $(".middle_name").html(
                '<div class="mb-3"> <label for="middle_name" class="  control-label">Middle Name </label><input type="text" class="form-control" name="middle_name" id="middle_name" value="" autocomplete="off" placeholder="Enter middle Name"></div>'
            );
            $(".last_name").html(
                '<div class="mb-3"><label for="last_name" class="  control-label">Last Name <span class="text-danger"> * </span></label><input type="text" class="form-control" name="last_name" id="last_name" value="" autocomplete="off" placeholder="Enter Last Name"></div>'
            );
        }
    } else if (update_customer == "1") {
        var customer_id = $("#customer_id").val();

        $.ajax({
            url: aurl + "/customer/get-customer",
            type: "POST",
            dataType: "JSON",
            async: false,
            data: {
                id: customer_id,
            },
            success: function (data) {
                if (data.status) {
                    if (prefix == "M/S") {
                        $company_name =
                            data.customer.prefix == "M/S"
                                ? data.customer.first_name
                                : "";
                        $(".first_name").html(
                            '<div class="mb-3"><label for="first_name" class="  control-label">Company Name <span class="text-danger"> * </span></label><input type="text" class="form-control" name="company_name" id="company_name" value="' +
                                $company_name +
                                '" autocomplete="off" placeholder="Enter Company Name"></div>'
                        );
                        $(".middle_name").html("");
                        $(".last_name").html("");
                    } else {
                        var first_name =
                            data.customer.prefix != "M/S"
                                ? data.customer.first_name
                                : "";
                        var middle_name =
                            data.customer.prefix != "M/S"
                                ? data.customer.middle_name
                                : "";
                        var last_name =
                            data.customer.prefix != "M/S"
                                ? data.customer.last_name
                                : "";

                        $(".first_name").html(
                            '<div class="mb-3"><label for="first_name" class="  control-label">First Name <span class="text-danger"> * </span></label><input type="text" class="form-control" name="first_name" id="first_name" value="' +
                                first_name +
                                '" autocomplete="off" placeholder="Enter First Name"></div>'
                        );
                        var middle_name =
                            middle_name == null ? "" : middle_name;
                        $(".middle_name").html(
                            '<div class="mb-3"> <label for="middle_name" class="  control-label">Middle Name </label><input type="text" class="form-control" name="middle_name" id="middle_name" value="' +
                                middle_name +
                                '" autocomplete="off" placeholder="Enter middle Name"></div>'
                        );
                        $(".last_name").html(
                            '<div class="mb-3"><label for="last_name" class="  control-label">Last Name <span class="text-danger"> * </span></label><input type="text" class="form-control" name="last_name" id="last_name" value="' +
                                last_name +
                                '" autocomplete="off" placeholder="Enter Last Name"></div>'
                        );
                    }
                }
            },
            error: function (request) {
                toaster_message(
                    "Something Went Wrong! Please Try Again.",
                    "error"
                );
            },
        });
    }
}

function clear_searching_data() {
    $("#customer_code").val("");
    $("#customer_id").val("");
    $(".adharcard_number").val("");
    $("#pancard_number").val("");
    $("#search_criteria").modal("hide");
    $("#customer_tbl").DataTable().ajax.reload();
}

function searching_data() {
    $("#search_criteria").modal("hide");
    $("#customer_tbl").DataTable().ajax.reload();
}
