/* Datatable */
if ($("#product_tbl").length) {
    $("#product_tbl").DataTable({
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
            url: aurl + "/product/listing",
        },
        columns: [
            { data: "id" },
            { data: "policy type" },
            { data: "name" },
            { data: "action" },
        ],
    });
}
$(document).ready(function() {
    /* Validation Of Product Form */
    if ($("#product_form").length) {
        $("#product_form").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 35,
                    productCheck: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    },
                },
                policy_type: {
                    required: true,
                },
                "companies[]": "required",
            },
            errorPlacement: function(label, element) {
                if (element.attr("type") == "checkbox") {
                    label.insertAfter(element.closest(".check"));
                } else if (
                    element.parents("div").hasClass("has-feedback") ||
                    element.hasClass("select2-hidden-accessible")
                ) {
                    label.appendTo(element.parent());
                } else {
                    label.insertAfter(element);
                }
            },
            messages: {
                name: {
                    required: "Please Enter Product Name",
                    productCheck: "Product Name Already Exists",
                },
                policy_type: {
                    required: "Please Select Policy Type",
                },
                "companies[]": "Please Select Companies For Product",
            },

            highlight: function(element) {
                $(element).removeClass("error");
            },
        });

        /* Product Already In Data */
        $.validator.addMethod("productCheck", function(value) {
            var x = 0;
            var id = $(".product_id").val();
            var policy_type = $(".policy_type").val();
            var x = $.ajax({
                url: aurl + "/product/product-check",
                type: "POST",
                async: false,
                data: { name: value, id: id, policy_type: policy_type },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        });
    }
    /* Add Or Update Product Data */
    $(".submit_product").on("click", function(event) {
        event.preventDefault();
        var form = $("#product_form")[0];
        var formData = new FormData(form);
        if ($("#product_form").valid()) {
            $.ajax({
                url: aurl + "/product",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toaster_message(data.message, data.icon, data.redirect_url);
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

    /* Select All Companies */
    $("#selectall").change(function() {
        var status = this.checked;
        $(".companies").each(function() {
            this.checked = status;
        });
    });
    $(".companies").change(function() {
        if (this.checked == false) {
            $("#selectall")[0].checked = false;
        }
        if ($(".companies:checked").length == $(".companies").length) {
            $("#selectall")[0].checked = true;
        }
    });
    /* checked select all while update data if all companies are selected */
    if (
        $(".companies:checked").length &&
        $(".companies:checked").length == $(".companies").length
    ) {
        $("#selectall")[0].checked = true;
    }
});