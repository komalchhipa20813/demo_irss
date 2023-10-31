/*DataTable*/
$("#city_tbl").DataTable({
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
        url: aurl + "/city/listing",
    },
    columns: [
        { data: "no" },
        { data: "country_name" },
        { data: "state_name" },
        { data: "city_name" },
        { data: "rto_code" },
        { data: "action" },
    ],
});

$(document).ready(function() {
    /* Validation Of City Form */
    $("#city_form").validate({
        rules: {
            country_name: {
                required: true,
            },
            state_name: {
                required: true,
            },
            city: {
                required: true,
                city_check: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            rto_code: {
                required: true,
                city_rto_check: true,
                rtoCode_patten_check:true,
                maxlength: 4,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },
        messages: {
            country_name: {
                required: "Please Select Country Name",
            },
            state_name: {
                required: "Please Select State Name",
            },
            city: {
                required: "Please Enter City Name",
            },
            rto_code: {
                required: "Please Enter City RTO Code",
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
        "city_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var state = $(".state_name").val();
            var x = $.ajax({
                url: aurl + "/city/city-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    id: id,
                    state: state,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "City Name Already Exists"
    );

    $.validator.addMethod(
        "rtoCode_patten_check",
        function(value) {
            var x = 0;
            var x = $.ajax({
                url: aurl + "/city/rto-code-patten-check",
                type: "POST",
                async: false,
                data: {
                    rto_code: value,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "RTO Code Patten Is Not Match , Example GJ05/GJ11"
    );

    $.validator.addMethod(
        "city_rto_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var state = $(".state_name").val();
            var city = $("#city").val();
            var x = $.ajax({
                url: aurl + "/city/city-rto-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    id: id,
                    state: state,
                    city: city,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "City RTO Code Already Exists"
    );

    /* Add City Modal */
    $("body").on("click", ".add_city", function() {
        $("#city_form").validate().resetForm();
        $("#city_form").trigger("reset");
        $(".country_name").val("0").trigger("change");
        $(".state_name").val("0").trigger("change");
        $("#city_modal").modal("show");
        $(".id").val($(this).data("id"));
        $("#title_city_modal").text("Add City");
        $(".submit_city").text("Add City");
    });

    /* Adding And Updating City Modal */
    $(".submit_city").click(function(event) {
        event.preventDefault();
        var form = $("#city_form")[0];
        var formData = new FormData(form);
        if ($("#city_form").valid()) {
            $.ajax({
                url: aurl + "/city",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#city_modal").modal("hide");
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

    /* Display Update City Modal*/
    $("body").on("click", ".edit_city", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/city/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#city_form").trigger("reset");
                    $("#city_form").validate().resetForm();
                    $("#title_city_modal").text("Update City");
                    $("#city_modal").modal("show");
                    $(".submit_city").text("Update City");
                    stateAddress(data);
                    $("#city").val(data.data.city.name);
                    $("#rto_code_id").val(data.data.city.rto_code);
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