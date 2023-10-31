
/*DataTable*/
$("#product-variant_tbl").DataTable({
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
        url: aurl + "/product-variant/listing",
    },
    columns: [{ data: "no" }, { data: "product_name" },{ data: "make_name" },{ data: "model_name" }, { data: "name" },{ data: "action" }],
});

$(document).ready(function() {
    /* Validation Of Country Form */
    $("#variant_form").validate({
        rules: {
            product: {
                required: true,
            },
            make_id: {
                required: true,
            },
            model_id: {
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
            make_id: {
                required: "Please Select Make",
            },
            model_id: {
                required: "Please Select Model",
            },
            name: {
                required: "Please Enter Variant",
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
            var model_id = $("#model_id").val();
            var x = $.ajax({
                url: aurl + "/product-variant/variant-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    model_id:model_id,
                    id: id,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Variant Already Exists"
    );

    /* Add Country Modal */
    $("body").on("click", ".add_variant", function() {
        $("#variant_form").validate().resetForm();
        $("#variant_form").trigger("reset");
        $("#variant_modal").modal("show");
        $('.make_id').html(' <option value="0" selected disabled>First select Product</option>');
        $('.model_id').html(' <option value="0" selected disabled>First select Product Make</option>');
        $(".product option[value=0]").prop("selected", true);
        $(".make_id option[value=0]").prop("selected", true);
        $(".model_id option[value=0]").prop("selected", true);
        $(".id").val($(this).data("id"));
        $("#title_variant_modal").text("Add Variant");
        $(".submit_variant").text("Add Variant");
    });

   /* Adding And Updating Model Modal */
    $(".submit_variant").click(function(event) {
        event.preventDefault();
        var form = $("#variant_form")[0];
        var formData = new FormData(form);
        if ($("#variant_form").valid()) {
            $.ajax({
                url: aurl + "/product-variant",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#variant_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! sPlease Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update Product Model Modal*/
    $("body").on("click", ".edit_variant", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
         $.ajax({
            url: aurl + "/product-variant/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#variant_form").trigger("reset");
                    $("#variant_form").validate().resetForm();
                    $("#variant_modal").modal("show");
                    $("#title_variant_modal").text("Update Variant");
                    $(".submit_variant").text("Update Variant");
                    $("#name").val(data.data.variant.name);
                    modelAddress(data);
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
