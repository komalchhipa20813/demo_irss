
/*DataTable*/
$("#product-model_tbl").DataTable({
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
        url: aurl + "/product-model/listing",
    },
    columns: [{ data: "no" }, { data: "product_name" },{ data: "make_name" }, { data: "name" },{ data: "action" }],
});

$(document).ready(function() {
    /* Validation Of Country Form */
    $("#model_form").validate({
        rules: {
            product: {
                required: true,
            },
            make_id: {
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
            name: {
                required: "Please Enter Model Name",
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
            var make_id = $("#make_id").val();
            var x = $.ajax({
                url: aurl + "/product-model/model-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    product_id:product_id,
                    make_id:make_id,
                    id: id,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Model Name Already Exists"
    );

    /* Add Country Modal */
    $("body").on("click", ".add_model", function() {
        $("#model_form").validate().resetForm();
        $("#model_form").trigger("reset");
        $("#model_modal").modal("show");
        $('.make_id').html(' <option value="0" selected disabled>First select Product</option>');
        $(".product option[value=0]").prop("selected", true);
        $(".make_id option[value=0]").prop("selected", true);
        $(".id").val($(this).data("id"));
        $("#title_model_modal").text("Add Model");
        $(".submit_model").text("Add Model");
    });

   /* Adding And Updating Model Modal */
    $(".submit_model").click(function(event) {
        event.preventDefault();
        var form = $("#model_form")[0];
        var formData = new FormData(form);
        if ($("#model_form").valid()) {
            $.ajax({
                url: aurl + "/product-model",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#model_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update Product Model Modal*/
    $("body").on("click", ".edit_model", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
         $.ajax({
            url: aurl + "/product-model/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#model_form").trigger("reset");
                    $("#model_form").validate().resetForm();
                    $("#model_modal").modal("show");
                    $("#title_model_modal").text("Update Model");
                    $(".submit_model").text("Update Model");
                    $("#name").val(data.data.model.name);
                    $(".product option[value="+data.data.model.make.product_id+"]").prop("selected", true);
                    makeAddress(data);
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
