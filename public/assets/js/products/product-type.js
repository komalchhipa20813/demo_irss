//Listing Of Sub Products
if ($("#product-type_tbl").length) {
    $("#product-type_tbl").DataTable({
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
            url: aurl + "/product-type/listing",
        },
        columns: [
            { data: "id" },
            { data: "products" },
            { data: "type" },
            { data: "action" },
        ],
    });
}
$(document).ready(function() {
    /* Validation Of Sub Product Form */
    $("#product_type_form").validate({
        rules: {
            product: {
                required: true,
            },
            type: {
                required: true,
                product_type_check: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },
        messages: {
            product: {
                required: "Please Select Product",
            },
            type: {
                required: "Please Enter Product Type",
                product_type_check: "Product Type Already Exists",
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

    // Sub Product Already In Data
    $.validator.addMethod(
        "product_type_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var product = $(".product").val();
            var x = $.ajax({
                url: aurl + "/product-type/check-product-type",
                type: "POST",
                async: false,
                data: { type: value, id: id, product: product },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Product Type Already Exists"
    );

    /* Sub Product Modal Show */
    $("body").on("click", ".add_product_type", function() {
        $("#product_type_form").validate().resetForm();
        $("#product_type_form").trigger("reset");
        $(".list_policies").val("0").trigger("change");
        $(".product").val("0").trigger("change");
        $("#product_type_modal").modal("show");
        $(".id").val($(this).data("id"));
        $("#title_product_type_modal").text("Add Product Type");
        $(".submit_product_type").text("Add Product Type");
    });

    /* Add Or Update Sub Product Data */
    $(".submit_product_type").click(function(event) {
        event.preventDefault();
        var form = $("#product_type_form")[0];
        var formData = new FormData(form);
        if ($("#product_type_form").valid()) {
            $.ajax({
                url: aurl + "/product-type",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#product_type_modal").modal("hide");
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

    /* Display Update Sub Product Data */
    $("body").on("click", ".edit_product_type", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/product-type/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#product_type_form").trigger("reset");
                    $("#product_type_form").validate().resetForm();
                    $("#title_product_type_modal").text("Update Product Type");
                    $("#product_type_modal").modal("show");
                    $(".submit_product_type").text("Update Product Type");
                    $.each(data.data.product, function(key, value) {
                        $(
                            ".policy_type option[value=" +
                            value.policy_type +
                            "]"
                        ).prop("selected", true);
                    });

                    var html = "";
                    $.each(data.data.product, function(key, value) {
                        html +=
                            '<option value="' +
                            value.id +
                            '">' +
                            value.name +
                            "</option>";
                    });

                    $(".product").html(html);
                    $(
                        ".product option[value=" +
                        data.data.product_type.product_id +
                        "]"
                    ).prop("selected", true);

                    $(".form-select").select2({
                        dropdownParent: $(".sub_product_modal"),
                        width: "100%",
                    });

                    $("#type").val(data.data.product_type.type);
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