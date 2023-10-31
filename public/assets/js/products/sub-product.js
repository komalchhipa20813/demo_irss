//Listing Of Sub Products
if ($("#sub-product_tbl").length) {
    $("#sub-product_tbl").DataTable({
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
            url: aurl + "/sub-product/listing",
        },
        columns: [
            { data: "id" },
            { data: "products" },
            { data: "subproducts_name" },
            { data: "action" },
        ],
    });
}
$(document).ready(function() {
    /* Validation Of Sub Product Form */
    $("#sub_product_form").validate({
        rules: {
            sub_product: {
                required: true,
            },
            sub_product_name: {
                required: true,
                sub_product_check: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },
        messages: {
            sub_product: {
                required: "Please Select Product",
            },
            sub_product_name: {
                required: "Please Enter Sub Product Name",
                sub_product_check: "Sub Product Name Already Exists",
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
        "sub_product_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var product = $(".product").val();
            var x = $.ajax({
                url: aurl + "/sub-product/check-sub-product",
                type: "POST",
                async: false,
                data: { name: value, id: id, product: product },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Sub Product Name Already Exists"
    );

    /* Sub Product Modal Show */
    $("body").on("click", ".add_sub_product", function() {
        $("#sub_product_form").validate().resetForm();
        $("#sub_product_form").trigger("reset");
        $(".list_policies").val("0").trigger("change");
        $(".product").val("0").trigger("change");
        $("#sub_product_modal").modal("show");
        $(".id").val($(this).data("id"));
        $("#title_sub_product_modal").text("Add Sub Product");
        $(".submit_sub_product").text("Add Sub Product");
    });

    /* Add Or Update Sub Product Data */
    $(".submit_sub_product").click(function(event) {
        event.preventDefault();
        var form = $("#sub_product_form")[0];
        var formData = new FormData(form);
        if ($("#sub_product_form").valid()) {
            $.ajax({
                url: aurl + "/sub-product",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#sub_product_modal").modal("hide");
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
    $("body").on("click", ".edit_sub_product", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/sub-product/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#sub_product_form").trigger("reset");
                    $("#sub_product_form").validate().resetForm();
                    $("#title_sub_product_modal").text("Update Sub Product");
                    $("#sub_product_modal").modal("show");
                    $(".submit_sub_product").text("Update Sub Product");

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
                        data.data.subProduct.product_id +
                        "]"
                    ).prop("selected", true);

                    $(".form-select").select2({
                        dropdownParent: $(".sub_modal"),
                        width: "100%",
                    });

                    $("#sub_product_name").val(data.data.subProduct.name);
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