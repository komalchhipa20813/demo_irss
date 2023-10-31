
/*DataTable*/
$("#country_tbl").DataTable({
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
        url: aurl + "/country/listing",
    },
    columns: [{ data: "no" }, { data: "name" }, { data: "action" }],
});

$(document).ready(function() {
    /* Validation Of Country Form */
    $("#country_form").validate({
        rules: {
            country_name: {
                required: true,
                country_check: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },

        messages: {
            country_name: {
                required: "Please Enter Country Name",
            },
        },

        highlight: function(element) {
            $(element).removeClass("error");
        },
    });

    $.validator.addMethod(
        "country_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var x = $.ajax({
                url: aurl + "/country/country-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    id: id,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Country Name Already Exists"
    );

    /* Add Country Modal */
    $("body").on("click", ".add_country", function() {
        $("#country_form").validate().resetForm();
        $("#country_form").trigger("reset");
        $("#country_modal").modal("show");
        $(".id").val($(this).data("id"));
        $("#title_country_modal").text("Add Country");
        $(".submit_country").text("Add Country");
    });

    /* Adding And Updating Country Modal */
    $(".submit_country").click(function(event) {
        event.preventDefault();
        var form = $("#country_form")[0];
        var formData = new FormData(form);
        if ($("#country_form").valid()) {
            $.ajax({
                url: aurl + "/country",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#country_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update Country Modal*/
    $("body").on("click", ".edit_country", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/country/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#country_form").trigger("reset");
                    $("#country_form").validate().resetForm();
                    $("#country_modal").modal("show");
                    $("#title_country_modal").text("Update Country");
                    $(".submit_country").text("Update Country");
                    $("#country_name").val(data.data.country.name);
                } else {
                    toaster_message(data.message, data.icon);
                }
            },
            error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
        });
    });
});
