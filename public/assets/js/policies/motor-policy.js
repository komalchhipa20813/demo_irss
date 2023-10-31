$(document).ready(function () {
    getRTOCodeCity();
    // Listing User Details
    if ($("#motor-policy_tbl").length) {
        function policy_listing(filterdata) {
            $("#motor-policy_tbl").DataTable({
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
                    data: filterdata,
                    url: aurl + "/motor-policy/listing",
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
                createdRow: function (row, data, dataIndex) {
                    if (data[8] == 3) {
                        $(row).addClass("error");
                    }
                },
            });
        }
        policy_listing({});
    }
    $("body").on("click", "#filter_motor_policy", function () {
        var data = {
            agent: $("#agent").val(),
            name: $("#name").val(),
            branch: $("#branch").val(),
            company: $("#company").val(),
            cheque_no: $("#cheque_no").val(),
            inward_no: $("#inward_no").val(),
            policy_no: $("#policy_no").val(),
            engine_no: $("#engine_no").val(),
            chasis_no: $("#chasis_no").val(),
            registration_no: $("#registration_no").val(),
            product: $("#product").val(),
            policy_start_date: $("#policy_start_date").val(),
            policy_end_date: $("#policy_end_date").val(),
            from_date: $("#from_date").val(),
            end_date: $("#end_date").val(),
        };
        $("#search_criteria").modal("hide");
        $("#motor-policy_tbl").DataTable().destroy();
        policy_listing(data);
    });
    $("body").on("click", "#reset_filter", function () {
        $("#motor_policy_searching_form").trigger("reset");
        $("#agent").val("0").trigger("change");
        $("#branch").val("0").trigger("change");
        $("#company").val("0").trigger("change");
        $("#product").val("0").trigger("change");
        $("#search_criteria").modal("hide");
        $("#motor-policy_tbl").DataTable().destroy();
        policy_listing({});
    });

    $("#filename_employee").change(function (events) {
        var tmppath = URL.createObjectURL(events.target.files[0]);
        $(".empimage")
            .fadeIn("fast")
            .attr("src", URL.createObjectURL(events.target.files[0]));
    });

    if ($("#motor_policy_form").length) {
        jQuery.validator.addMethod("number_val", function (value, element) {
            return (
                this.optional(element) ||
                /^\-?([0-9]+(\.[0-9]+)?|Infinity)$/i.test(value)
            );
        });
        jQuery.validator.addMethod(
            "number_char_val",
            function (value, element) {
                return this.optional(element) || /^[a-zA-Z0-9 ]+$/.test(value);
            }
        );

        jQuery.validator.addMethod(
            "four_digiton_date",
            function (value, element) {

                return (value.length != 4)? false :true;
            }
        );
        $("#motor_policy_form").validate({
            rules: {
                policy_type: {
                    required: true,
                },
                product: {
                    required: true,
                },
                business_date: {
                    required: true,
                },
                agent: {
                    required: true,
                },
                customer: {
                    required: true,
                },
                branch: {
                    required: true,
                },
                pre_company: {
                    required: true,
                },
                code_type:{
                    required: true,  
                },
                company: {
                    required: true,
                },
                product_type: {
                    required: true,
                },
                company_branch_name: {
                    required: true,
                },
                branch_imd: {
                    required: true,
                },
                sub_product: {
                    required: true,
                },
                policy_number: {
                    policy_number_check: true,
                },
                // Vehicle Details
                registration: {
                    required: true,
                    policyCheck: true,
                },
                rto_code_value:{
                    required: true,
                },
                rto_city:{ required: true,},
                engine_no: {
                    required: true,
                    number_char_val: true,
                    minlength: 6,
                },
                chasiss_no: {
                    required: true,
                },
                make_id: {
                    required: true,
                },
                model_id: {
                    required: true,
                },
                variant_id: {
                    required: true,
                },
                cc_gvw_no: {
                    required: true,
                    number_val: true,
                },
                manufacturing_year: {
                    required: true,
                    four_digiton_date:true,
                },
                seating_capacity: {
                    required: true,
                },
                fuel_type: {
                    required: true,
                },
                llpd: {
                    number_val: true,
                },
                total_idv: {
                    number_val: true,
                },
                discount: {
                    number_val: true,
                    max: 99,
                },
                addonpremium: {
                    number_val: true,
                },
                tp: {
                    required: true,
                    number_val: true,
                },
               
                pay_to_owner: {
                    number_val: true,
                },
                od: {
                    required:
                        $("#sub_product").find("option:selected").text() !=
                        "THIRD PARTY",
                    number_val: true,
                },
                gst: {
                    required: true,
                    number_val: true,
                    max: function (value) {
                        return $(".gst_value").prop("checked") ? 50000000 : 99;
                    },
                },
                payment_type: {
                    required: true,
                },
                cash_amount: {
                    number_val: true,
                    compare_amount: true,
                    required: true,
                },
                cheque_bank: {
                    required: true,
                },
                cheque_number: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                },
                cheque_date: {
                    required: true,
                },
                cheque_amount: {
                    number_val: true,
                    compare_amount: true,
                    required: true,
                    number_val: true,
                },
                dd_bank: {
                    required: true,
                },
                dd_number: {
                    required: true,
                },
                dd_date: {
                    required: true,
                },
                dd_amount: {
                    compare_amount: true,
                    required: true,
                    number_val: true,
                },
                transaction_bank: {
                    required: true,
                },
                transaction_date: {
                    required: true,
                },
                online_amount: {
                    number_val: true,
                    compare_amount: true,
                    required: true,
                },
                pre_start_date: {
                    required: true,
                },
                pre_end_date: {
                    required: true,
                },
                pre_company: {
                    required: true,
                },
                previouspolicycompany: {
                    required: true,
                },
            },
            messages: {
                policy_type: {
                    required: "Please Select Policy Type",
                },
                product: {
                    required: "Please Select Product",
                },
                branch: {
                    required: "Please Select Branch",
                },
                business_date: {
                    required: "Please Select Month",
                },
                customer: {
                    required: "Please Select Customer",
                },
                agent: {
                    required: "Please Select Agent ",
                },
                pre_company: {
                    required: "Please Select Previous policy Company",
                },
                code_type:{
                    required: "Please Select Code Type",  
                },
                company: {
                    required: "Please Select Company",
                },
                company_branch_name: {
                    required: "Please Select Company Branch Name",
                },
                branch_imd: {
                    required: "Please Select Branch IMD",
                },
                product_type: {
                    required: "Please Select Product Type",
                },
                policy_number: {
                    policy_number_check: "Policy Number Already Exist",
                },
                registration: {
                    required: "Please Enter Registration Number",
                    policyCheck: "Registration Number Already Exist",
                },
                rto_code_value:{
                    required: "Please Enter RTO Code",
                },
                rto_city:{ required: "Please Enter RTO City",},

                engine_no: {
                    required: "Please Enter Engine Number",
                    number_char_val:
                        "Please Enter Number Value Or Letters Only",
                    minlength:
                        "Your Engin Number Must Be At Least 6 Characters Long",
                },
                chasiss_no: {
                    required: "Please Enter Chasiss Number",
                    number_val: "Please Enter Number Value Only",
                },
                make_id: {
                    required: "Please Select Make",
                },
                model_id: {
                    required: "Please Select Model",
                },
                variant_id: {
                    required: "Please Select Variant",
                },
                cc_gvw_no: {
                    required: "Please Enter CC/GVW Number",
                    number_val: "Please Enter Number Value Only",
                },
                manufacturing_year: {
                    required: "Please Select Month",
                    four_digiton_date:"Please Select Four Digits of Year",

                },
                seating_capacity: {
                    required: "Please Enter Seating Capacity",
                },
                fuel_type: {
                    required: "Please Select Fuel Type",
                },
                llpd: {
                    required: "Please Enter LLPD",
                    number_val: "Please Enter Number Value Only",
                },
                total_idv: {
                    required: "Please Enter Total IDV",
                    number_val: "Please Enter Number Value Only",
                },
                discount: {
                    number_val: "Please Enter Number Value Only",
                },
                ncb: {
                    required: "Please Select NCB",
                },
                addonpremium: {
                    required: "Please Enter Addonpremium",
                    number_val: "Please Enter Number Value Only",
                },
                tp: {
                    required: "Please Enter TP",
                    number_val: "Please Enter Number Value Only",
                },
               
                pay_to_owner: {
                    number_val: "Please Enter Number Value Only",
                },
                od: {
                    required: "Please Enter OD",
                    number_val: "Please Enter Number Value Only",
                },
                gst: {
                    required: "Please Enter GST",
                    number_val: "Please Enter Number Value Only",
                },
                payment_type: {
                    required: "Please Select Payment Type",
                },
                cash_amount: {
                    compare_amount:
                        "Total Premium Amount And Amount Should Be Same",
                    required: "Please Enter Amount",
                    number_val: "Please Enter Number Value Only",
                },
                cheque_bank: {
                    required: "Please Select Bank Name",
                },
                cheque_number: {
                    required: "Please Enter Cheque Number",
                    minlength: "Minimum 6 Digits Required",
                    maxlength: "Maximum 6 Digits required",
                },
                cheque_date: {
                    required: "Please Select Cheque Date",
                },
                cheque_amount: {
                    number_val: "Please Enter Number Value Only",
                    compare_amount:
                        "Total Premium Amount And Amount Should Be Same",
                    required: "Please Enter Cheque Amount",
                },
                dd_bank: {
                    required: "Please Select Bank Name",
                },
                dd_number: {
                    required: "Please Enter Number",
                },
                dd_date: {
                    required: "Please Select Date",
                },
                dd_amount: {
                    required: "Please Enter Amount",
                    compare_amount:
                        "Total Premium Amount And Amount Should Be Same",
                    number_val: "Please Enter Number Value Only",
                },
                transaction_bank: {
                    required: "Please Select Bank Name",
                },
                transaction_date: {
                    required: "Please Select Transaction Date",
                },
                online_amount: {
                    number_val: "Please Enter Number Value Only",
                    compare_amount:
                        "Total Premium Amount And Amount Should Be Same",
                    required: "Please Enter Amount",
                },
                pre_start_date: {
                    required: "Please Select Start Date",
                },
                pre_end_date: {
                    required: "Please Select End Date",
                },
                pre_company: {
                    required: "Please Select Company",
                },
            },
            errorPlacement: function (error, element) {
                if (
                    element.parents("div").hasClass("has-feedback") ||
                    element.hasClass("select2-hidden-accessible")
                ) {
                    error.appendTo(element.parent());
                } else if (
                    element.parents("div").hasClass("uploader") ||
                    element.hasClass("datepicker") ||
                    element.hasClass("business_date") ||
                    element.hasClass("manufacturing_year")
                ) {
                    error.appendTo(element.parent().parent());
                } else if (element.hasClass("form-check-input")) {
                    error.appendTo(element.parent().parent().parent());
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
    }

    /* Policy Already In Data */
    $.validator.addMethod("policyCheck", function (value) {
        var x = 0;
        var id = $(".policy_id").val();
        var x = $.ajax({
            url: aurl + "/motor-policy/policy-check",
            type: "POST",
            async: false,
            data: { policy_no: value, id: id },
        }).responseText;
        if (x != 0 && !$("#newregistration").prop("checked")) {
            if($('input[name="policy_type"]:checked').val() == 'renewal'){
                return true;
            }else{
                return false;
            }
        } else {
            return true
        }
    });

    $.validator.addMethod("policy_number_check", function (value) {
        var x = 0;
        var id = $(".policy_id").val();
        var x = $.ajax({
            url: aurl + "/motor-policy/policy-number",
            type: "POST",
            async: false,
            data: { policy_number: value, id: id },
        }).responseText;
        if (x != 0) {
            return false;
        } else return true;
    });

    /* adding and updating employee data */
    $(".submit_policy").on("click", function (event) {
        event.preventDefault();
        var form = $("#motor_policy_form")[0];
        var formData = new FormData(form);
        if ($("#motor_policy_form").valid()) {
            $.ajax({
                url: aurl + "/motor-policy",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    toaster_message(data.message, data.icon, data.redirect_url);
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

    $(".gst_value").on("change", function () {
        if ($(this).prop("checked") == true) {
            $("#gst").attr("placeholder", " GST Value");
            $("#gst").val("");
        } else if ($(this).prop("checked") == false) {
            $("#gst").attr("placeholder", " GST%");
            $("#gst").val("");
        }
        cal_totalpremium();
    });

    /* change depending on sub product */
    $("#sub_product").on("change", function () {
        sub_product_change($(this).find("option:selected").text());
    });
    if ($(".submit_policy").text() == "Update") {
        get_model_data();
        get_variant_data();
    }
    $("#newregistration").on("change", function () {
        newregistration_change();
    });

    $("#vehical_registration").on("change", function () {
        getRTOCodeCity();
    });

    // export data
    $("#export").click(function (event) {
        var data = {
            agent: $("#agent").val(),
            name: $("#name").val(),
            branch: $("#branch").val(),
            company: $("#company").val(),
            cheque_no: $("#cheque_no").val(),
            inward_no: $("#inward_no").val(),
            policy_no: $("#policy_no").val(),
            engine_no: $("#engine_no").val(),
            chasis_no: $("#chasis_no").val(),
            registration_no: $("#registration_no").val(),
            product: $("#product").val(),
            policy_start_date: $("#policy_start_date").val(),
            policy_end_date: $("#policy_end_date").val(),
            from_date: $("#from_date").val(),
            end_date: $("#end_date").val(),
        };

        $.ajax({
            url: aurl + "/motor-policy/export-data",
            type: "POST",
            data: {
                data: data,
            },
            cache: false,
            beforeSend: function () {
                $("#export").prop("disabled", true);
                $("#export").text("Excel downloading..");
            },
            success: function (response) {
                if (response.status) {
                    var a = document.createElement("a");
                    a.href = response.file;
                    a.download = response.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    $("#export").prop("disabled", false);
                    $("#export").text("Export Motor Policy");
                } else {
                    $("#export").prop("disabled", false);
                    $("#export").text("Export Motor Policy");
                    toaster_message(response.msg, response.icon);
                }
            },
            error: function (ajaxContext) {
                $("#downloadExcel").show();
                $("#ExcelDownloadLoader").hide();
            },
        });
    });
    // end export
});
function newregistration_change(val = "") {
    if ($("#newregistration").prop("checked") == true) {
        $(".tpdate").removeClass("dNone");
        $(".tpdate").show();
        Inputmask("A{2}[ ]9{2}", {
            placeholder: "_",
            greedy: false,
        }).mask("#vehical_registration");

        $(".tp_end_date").val($(".end_date").val());
        $(".tp_start_date").val($("#start_date").val());
    } else if ($("#newregistration").prop("checked") == false) {
        $(".tpdate").addClass("dNone");
        $(".tpdate").hide();
        Inputmask("A{2}[ ]9{2}[ ]A{3}[ ]9{4}", {
            placeholder: "_",
            greedy: true,
        }).mask("#vehical_registration");
        $(".tp_end_date").val(" ");
        $(".tp_start_date").val(" ");
    }
    $("#newregistration").val(val).trigger("maskInput");
}
function cal_totalpremium() {
    var od = $("#od").val() == "" ? 0 : $("#od").val();
    var addpremium =
        $("#addonpremium").val() == "" ? 0 : $("#addonpremium").val();
    var tp = $("#tp").val() == "" ? 0 : $("#tp").val();
    var pay_to_owner =
        $("#pay_to_owner").val() == "" ? 0 : $("#pay_to_owner").val();
    var gst = $("#gst").val() == "" ? 0 : parseInt($("#gst").val());
    var stamp_duty = $("#stamp_duty").val() == "" ? 0 : $("#stamp_duty").val();
    var check = $(".gst_value").prop("checked");
    var total_premium = 0;
    if (check) {
        gst = $("#gst").val() == "" ? 0 : parseInt($("#gst").val());
        total_premium = Math.round(
            parseInt(od) +
                parseInt(addpremium) +
                parseInt(pay_to_owner) +
                parseInt(tp) +
                gst
        );
    } else {
        total_premium =
            parseInt(od) +
            parseInt(addpremium) +
            parseInt(tp) +
            parseInt(pay_to_owner);
        total_premium = Math.round(total_premium + (total_premium / 100) * gst);
    }

    total_premium= total_premium + parseInt(stamp_duty)
    $("#total_premium").val(total_premium);
}

function odValueChange() {
    if ($("#only_od").prop("checked")) {
        $("#tp").attr("disabled", "disabled");
        $("#tp").val(0);
    } else {
        $("#tp").removeAttr("disabled", "disabled");
        $("#tp").val("");
    }
}
function sub_product_change(data) {
    if (data == "COMPREHENSIVE") {
        $(".third_party_disabled").prop("disabled", false);
        $("#addonpremium").prop("disabled", true);
        $("#addonpremium").val("");
    } else if (data == "THIRD PARTY") {
        $(".third_party_disabled").prop("disabled", true);
        $(".third_party_disabled").val("");
    } else {
        $(".third_party_disabled").prop("disabled", false);
    }
}

function getRTOCodeCity()
{
    var code = $('#vehical_registration').val();
        $('#rto_code_value').val('');
        $('#rto_city').val('');
       $('#rto_code_id').val('');
       if(code != '')
       {
            $.ajax({
                url: aurl + "/city/get-rto-code-city",
                type: "POST",
                dataType: "JSON",
                data: {code : code},
                // cache: false,
                // contentType: false,
                // processData: false,
                success: function(data) {
                    if(data.status)
                    {
                        if(data.rtoCode != null)
                        {
                            $('#rto_code_value').val(data.rtoCode.rto_code);
                            $('#rto_city').val(data.rtoCode.name);
                            $('#rto_code_id').val(data.rtoCode.id);
                        }
                        
                    }
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }

}
