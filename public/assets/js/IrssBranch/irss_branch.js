/*DataTable*/
var listing = $("#retinue-branch_tbl").DataTable({
    aLengthMenu: [
        [10, 30, 50, -1],
        [10, 30, 50, "All"],
    ],
    iDisplayLength: 10,
    ajax: {
        type: "POST",
        url: aurl + "/retinue-branch/listing",
    },
    columns: [
        { data: "no" },
        { data: "name" },
        { data: "inward" },
        { data: "country" },
        { data: "state" },
        { data: "city" },
        { data: "address" },
        { data: "action" },
    ],
});

$(document).ready(function() {
    /* Validation Of IRSS Branch Form */
    $("#irssBranch_form").validate({
        rules: {
            IrssBranch_name: {
                required: true,
                branchcheck: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            address: {
                required: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            country_name: {
                required: true,
            },
            state_name: {
                required: true,
            },
            city_name: {
                required: true,
            },
            IrssBranch_rto_code: {
                required: true,
                branchRTOcheck: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
            IrssBranch_inward_code:{
                required: true,
                branchrInwardcheck: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            }
        },
        messages: {
            IrssBranch_name: {
                required: "Please Enter IRSS Branch Name",
            },
            address: {
                required: "Please Enter Address",
            },
            country_name: {
                required: "Please Select Country Name",
            },
            state_name: {
                required: "Please Select State Name",
            },
            city_name: {
                required: "Please Select City Name",
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
        "branchcheck",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var city = $(".city_name").val();
            var x = $.ajax({
                url: aurl + "/retinue-branch/branch-check",
                type: "POST",
                async: false,
                data: { name: value, id: id, city_id: city },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "IRSS Branch Name Already Exists"
    );
    $.validator.addMethod(
        "branchRTOcheck",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var city = $(".city_name").val();
            var x = $.ajax({
                url: aurl + "/retinue-branch/branch-rto-check",
                type: "POST",
                async: false,
                data: { name: value, id: id ,city_id: city},
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "IRSS Branch Name Already Exists"
    );

    $.validator.addMethod(
        "branchrInwardcheck",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var city = $(".city_name").val();
            var x = $.ajax({
                url: aurl + "/retinue-branch/branch-inward-check",
                type: "POST",
                async: false,
                data: { name: value, id: id ,city_id: city},
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "IRSS Branch Inward code Already Exists"
    );


    /*Display Add IRSS Branch Modal */
    $("body").on("click", ".add_IrssBranch", function() {
        $("#irssBranch_form").validate().resetForm();
        $("#irssBranch_form").trigger("reset");
        $("#irssBranch_modal").modal("show");
        $(".id").val($(this).data("id"));
        $(".country_name").val("0").trigger("change");
        $(".state_name").val("0").trigger("change");
        $("#title_irssBranch_modal").text("Add IRSS Branch");
        $(".submit_irss_Branch").text("Add IRSS Branch");
    });

    /* Adding And Updating IRSS Branch Data */
    $(".submit_irss_Branch").click(function(event) {
        event.preventDefault();
        var form = $("#irssBranch_form")[0];
        var formData = new FormData(form);
        if ($("#irssBranch_form").valid()) {
            $.ajax({
                url: aurl + "/retinue-branch",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#irssBranch_modal").modal("hide");
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

    /* Display Update IRSS Branch Modal*/
    $("body").on("click", ".edit_irss_branch", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
    event.preventDefault();
        $.ajax({
            url: aurl + "/retinue-branch/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#irssBranch_form").validate().resetForm();
                    $("#irssBranch_form").trigger("reset");
                    $("#title_irssBranch_modal").text("Update IRSS Branch");
                    $("#irssBranch_modal").modal("show");
                    $(".submit_irss_Branch").text("Update IRSS Branch");
                    $("#IrssBranch_name").val(data.data.name);
                    $("#address").val(data.data.address);
                    $("#IrssBranch_inward_code").val(data.data.policy_inward_code);
                    fullAddress(data);
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
