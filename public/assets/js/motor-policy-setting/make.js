
/*DataTable*/
$("#make-product_tbl").DataTable({
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
        url: aurl + "/make-product/listing",
    },
    columns: [{ data: "no" }, { data: "product_name" }, { data: "name" },{ data: "action" }],
});

$(document).ready(function() {
    /* Validation Of Country Form */
    $("#make_form").validate({
        rules: {
            product: {
                required: true,
            },
            name: {
                required: true,
                make_check: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },

        messages: {
            product: {
                required: "Please Select Product",
            },
            name: {
                required: "Please Enter Make Name",
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
        "make_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var product_id = $(".product").val();
            var x = $.ajax({
                url: aurl + "/make-product/make-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    product_id:product_id,
                    id: id,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Make Name Already Exists"
    );

    /* Add Country Modal */
    $("body").on("click", ".add_make", function() {
        $("#make_form").validate().resetForm();
        $("#make_form").trigger("reset");
        $("#make_modal").modal("show");
        $(".product option[value=0]").prop("selected", true);
        $(".id").val($(this).data("id"));
        $("#title_make_modal").text("Add Make");
        $(".submit_make").text("Add make");
    });

   /* Adding And Updating Make Modal */
    $(".submit_make").click(function(event) {
        event.preventDefault();
        var form = $("#make_form")[0];
        var formData = new FormData(form);
        if ($("#make_form").valid()) {
            $.ajax({
                url: aurl + "/make-product",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#make_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update Country Modal*/
    $("body").on("click", ".edit_make", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/make-product/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#make_form").trigger("reset");
                    $("#make_form").validate().resetForm();
                    $("#make_modal").modal("show");
                    $("#title_make_modal").text("Update Make");
                    $(".submit_make").text("Update Make");
                    $("#name").val(data.data.make.name);
                    $(".product option[value="+data.data.make.product_id+"]").prop("selected", true);
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
