/*DataTable*/
$("#state_tbl").DataTable({
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
        url: aurl + "/state/listing",
    },
    columns: [
        { data: "no" },
        { data: "country_name" },
        { data: "state_name" },
        { data: "action" },
    ],
});

$(document).ready(function() {
    /* Validation Of State Form */
    $("#state_form").validate({
        rules: {
            country_name: {
                required: true,
            },
            state_name: {
                required: true,
                state_check: true,
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
                required: "Please Enter State Name",
            },
        },
        errorPlacement: function(error, element) {
            if (element.parents("div").hasClass("has-feedback") || element.hasClass("select2-hidden-accessible")) {
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
        "state_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var country_name = $(".country_name").val();
            var x = $.ajax({
                url: aurl + "/state/state-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    id: id,
                    country_name: country_name,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "State Name Already Exists"
    );

    /* Add State Modal */
    $("body").on("click", ".add_state", function() {
        $("#state_form").validate().resetForm();
        $("#state_form").trigger("reset");
        $(".country_name").val("0").trigger("change");
        $("#state_modal").modal("show");
        $(".id").val($(this).data("id"));
        $("#title_state_modal").text("Add State");
        $(".submit_state").text("Add State");
    });

    /* Adding And Updating State Modal */
    $(".submit_state").click(function(event) {
        event.preventDefault();
        var form = $("#state_form")[0];
        var formData = new FormData(form);
        if ($("#state_form").valid()) {
            $.ajax({
                url: aurl + "/state",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#state_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update State modal*/
    $("body").on("click", ".edit_state", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/state/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#state_form").trigger("reset");
                    $("#state_form").validate().resetForm();
                    $("#title_state_modal").text("Update State");
                    $("#state_modal").modal("show");
                    $(".submit_state").text("Update State");
                    $(".country_name option[value=" + data.data.country_id + "]").prop("selected", true);
                    $("#state_name").val(data.data.name);
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