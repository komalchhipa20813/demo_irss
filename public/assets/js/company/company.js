/*DataTable*/
var listing = $("#company_tbl").DataTable({
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
        url: aurl + "/company/listing",
    },
    columns: [
        { data: "no" },
        { data: "name" },
        { data: "country" },
        { data: "state" },
        { data: "city" },
        { data: "address" },
        { data: "action" },
    ],
});

$(document).ready(function() {
    /* Validation Of Company Form */
    $("#company_form").validate({
        rules: {
            company_name: {
                required: true,
                companycheck: true,
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
                required: "Please Enter Company Name",
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
        highlight: function(element) {
            $(element).removeClass("error");
        },
    });

    $.validator.addMethod(
        "companycheck",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var city = $(".city_name").val();
            var x = $.ajax({
                url: aurl + "/company/company-name-check",
                type: "POST",
                async: false,
                data: { name: value, id: id, city_id: city },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Company Name Already Exists"
    );

    /*Display Add Company Modal */
    $("body").on("click", ".add_Company", function() {
        $("#company_form").validate().resetForm();
        $("#company_form").trigger("reset");
        $("#company_modal").modal("show");
        $(".id").val($(this).data("id"));
        $(".country_name").val("0").trigger("change");
        $(".state_name").val("0").trigger("change");
        $("#title_company_modal").text("Add Company");
        $(".submit_company").text("Add Company");
    });

    /* Adding And Updating Company Data */
    $(".submit_company").click(function(event) {
        event.preventDefault();
        var form = $("#company_form")[0];
        var formData = new FormData(form);
        if ($("#company_form").valid()) {
            $.ajax({
                url: aurl + "/company",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#company_modal").modal("hide");
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

    /* Display Update Company Modal*/
    $("body").on("click", ".edit_company", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/company/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#company_form").validate().resetForm();
                    $("#company_form").trigger("reset");
                    $("#title_company_modal").text("Update Company");
                    $("#company_modal").modal("show");
                    $(".submit_company").text("Update Company");
                    $("#company_name").val(data.data.name);
                    $("#address").val(data.data.address);
                    $(
                        ".country_name option[value=" +
                        data.data.city.state.country.id +
                        "]"
                    ).prop("selected", true);
                    var html = "";
                    $.each(data.data.states, function(key, value) {
                        html +=
                            '<option value="' +
                            value.id +
                            '">' +
                            value.name +
                            "</option>";
                    });
                    $(".state_name").html(html);
                    $(
                        ".state_name option[value=" +
                        data.data.city.state_id +
                        "]"
                    ).prop("selected", true);
                    var html = "";
                    $.each(data.data.cities, function(key, value) {
                        html +=
                            '<option value="' +
                            value.id +
                            '">' +
                            value.name +
                            "</option>";
                    });
                    $(".city_name").html(html);
                    $(
                        ".city_name option[value=" + data.data.city_id + "]"
                    ).prop("selected", true);
                    $(".form-select").select2({
                        dropdownParent: $(".select"),
                        width: "100%",
                    });
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