
/*DataTable*/
$("#bank_tbl").DataTable({
    aLengthMenu: [
        [10, 30, 50, -1],
        [10, 30, 50, "All"],
    ],
    iDisplayLength: 10,

    ajax: {
        type: "POST",
        url: aurl + "/bank/listing",
    },
    columns: [{ data: "no" }, { data: "name" }, { data: "action" }],
});

$(document).ready(function() {
    /* Validation Of Bank Form */
    $("#bank_form").validate({
        rules: {
            name: {
                required: true,
                bank_check: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },

        messages: {
            name: {
                required: "Please Enter Bank Name",
            },
        },

        highlight: function(element) {
            $(element).removeClass("error");
        },
    });

    $.validator.addMethod(
        "bank_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var x = $.ajax({
                url: aurl + "/bank/bank-check",
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
        "Bank Name Already Exists"
    );

    /* Add Bank Modal */
    $("body").on("click", ".add_bank", function() {
        $("#bank_form").validate().resetForm();
        $("#bank_form").trigger("reset");
        $("#bank_modal").modal("show");
        $(".id").val($(this).data("id"));
        $("#title_bank_modal").text("Add Bank");
        $(".submit_bank").text("Add Bank");
    });

    /* Adding And Updating Country Modal */
    $(".submit_bank").click(function(event) {
        event.preventDefault();
        var form = $("#bank_form")[0];
        var formData = new FormData(form);
        if ($("#bank_form").valid()) {
            $.ajax({
                url: aurl + "/bank",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#bank_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update Country Modal*/
    $("body").on("click", ".edit_bank", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/bank/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#bank_form").trigger("reset");
                    $("#bank_form").validate().resetForm();
                    $("#bank_modal").modal("show");
                    $("#title_bank_modal").text("Update Bank");
                    $(".submit_bank").text("Update Bank");
                    $("#name").val(data.data.bank.name);
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
