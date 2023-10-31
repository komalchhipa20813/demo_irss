/*DataTable*/
var listing = $("#company-branch_tbl").DataTable({
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
        url: aurl + "/company-branch/listing",
    },
    columns: [
        { data: "no" },
        { data: "name" },
        { data: "company_branch_name" },
        { data: "country" },
        { data: "state" },
        { data: "city" },
        { data: "address" },
        { data: "action" },
    ],
});

$(document).ready(function() {
    /* Validation Of Company Branch Form */
    $("#company_branch_form").validate({
        rules: {
            company_name: {
                required: true,
            },
            company_branch_name: {
                required: true,
                company_branch_check: true,
            },
            address: {
                required: true,
            },
            country_name: {
                required: true,
            },
            state_name: {
                required: true,
            },
            city_name: {
                required: true,
            },
        },

        messages: {
            company_name: {
                required: "Please Select Company Name",
            },
            company_branch_name: {
                required: "Please Enter Company Branch Name",
            },
            address: {
                required: "Please Enter Address",
            },
            country_name: {
                required: "Please Select Country Name",
            },
            state_name: {
                required: "Please Select State Name",
            },
            city_name: {
                required: "Please Select City Name",
            },
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
        errorPlacement: function(error, element) {
            if (
                element.parents("div").hasClass("has-feedback") ||
                element.hasClass("select2-hidden-accessible")
            ) {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
        normalizer: function(value) {
            return $.trim(value);
        },
    });
    $.validator.addMethod(
        "company_branch_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var company_name = $(".company_name").val();
            var city = $(".city_name").val();
            var x = $.ajax({
                url: aurl + "/company-branch/company-branch-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    id: id,
                    company_name: company_name,
                    city_id: city,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Company Branch Name Already Exists"
    );

    /* Add Company Branch Modal */
    $("body").on("click", ".add_companyBranch", function() {
        $("#company_branch_form").validate().resetForm();
        $("#company_branch_form").trigger("reset");
        $(".company_name").val("0").trigger("change");
        $("#companyBranch_modal").modal("show");
        $(".id").val($(this).data("id"));
        $(".country_name").val("0").trigger("change");
        $(".state_name").val("0").trigger("change");
        $("#title_company_branch_modal").text("Add Company Branch");
        $(".submit_companyBranch").text("Add Company Branch");
    });

    /* Adding And Updating Company Branch Modal */
    $(".submit_companyBranch").click(function(event) {
        event.preventDefault();
        var form = $("#company_branch_form")[0];
        var formData = new FormData(form);
        if ($("#company_branch_form").valid()) {
            $.ajax({
                url: aurl + "/company-branch",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#companyBranch_modal").modal("hide");
                    toaster_message(data.message, data.icon);
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

    /* Display Update Company Branch Modal*/
    $("body").on("click", ".edit_company_branch", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/company-branch/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#company_branch_form").validate().resetForm();
                    $("#company_branch_form").trigger("reset");
                    $("#companyBranch_modal").modal("show");
                    $("#title_company_branch_modal").text(
                        "Update Company Branch"
                    );
                    $(".submit_companyBranch").text("Update Company Branch");
                    $("#company_branch_name").val(data.data.name);
                    $("#address").val(data.data.address);
                    $(
                        ".company_name option[value=" +
                        data.data.company_id +
                        "]"
                    ).prop("selected", true);
                    fullAddress(data);
                } else {
                    toaster_message(data.message, data.icon);
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