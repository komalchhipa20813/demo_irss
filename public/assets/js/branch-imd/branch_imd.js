/*DataTable*/
var listing = $("#branch-imd_tbl").DataTable({
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
        url: aurl + "/branch-imd/listing",
    },
    columns: [
        { data: "no" },
        { data: "name" },
        { data: "company_branch_name" },
        { data: "branch_imb" },
        { data: "action" },
    ],
});

$(document).ready(function() {
    /* Validation Of Branch Imd Form */
    $("#branch_imd_form").validate({
        rules: {
            company_name: {
                required: true,
            },
            company_branch_name: {
                required: true,
            },
            branch_imd_name: {
                required: true,
                branch_imd_check: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },

        messages: {
            company_name: {
                required: "Please Select Company Name",
            },
            company_branch_name: {
                required: "Please Select Company Branch Name",
            },
            branch_imd_name: {
                required: "Please Enter Company Branch Imd Name",
            },
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
        errorPlacement: function(error, element) {
            if (element.parents("div").hasClass("has-feedback") || element.hasClass("select2-hidden-accessible")) {
                error.appendTo(element.parent());
            } else {
                error.insertAfter(element);
            }
        },
    });

    $.validator.addMethod(
        "branch_imd_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var company_branch_name = $(".company_branch_name").val();
            var x = $.ajax({
                url: aurl + "/branch-imd/branch-imd-check",
                type: "POST",
                async: false,
                data: {
                    name: value,
                    id: id,
                    company_branch_name: company_branch_name,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Branch Imd Name Already Exists"
    );

    /* Add Branch Imd Modal */
    $("body").on("click", ".add_branch_imd", function() {
        $("#branch_imd_form").validate().resetForm();
        $("#branch_imd_form").trigger("reset");
        $(".company_name").val("0").trigger("change");
        $(".company_branch_name").val("0").trigger("change");
        $("#branch_imd_modal").modal("show");
        $(".id").val($(this).data("id"));
        $("#title_branch_imd_modal").text("Add Branch Imd");
        $(".submit_branch_imd").text("Add Branch Imd");
    });

    /* Adding And Updating Branch Imd Modal */
    $(".submit_branch_imd").click(function(event) {
        event.preventDefault();
        var form = $("#branch_imd_form")[0];
        var formData = new FormData(form);
        if ($("#branch_imd_form").valid()) {
            $.ajax({
                url: aurl + "/branch-imd",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#branch_imd_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update Branch Imd Modal*/
    $("body").on("click", ".edit_branch_Imd", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/branch-imd/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#branch_imd_form").trigger("reset");
                    $("#branch_imd_form").validate().resetForm();
                    $("#title_branch_imd_modal").text("Update Branch Imd");
                    $("#branch_imd_modal").modal("show");
                    $(".submit_branch_imd").text("Update Branch Imd");
                    $(".company_name option[value=" +data.data.branchImd.company_id +"]").prop("selected", true);
                    var html = "";
                    $.each(data.data.companyBranch, function(key, value) {
                        html +='<option value="' +value.id +'">' +value.name +"</option>";});
                    $(".company_branch_name").html(html);
                    $(".Company_branch_id option[value=" +data.data.branchImd.company_branch_id +"]").prop("selected", true);
                    $(".form-select").select2({
                        dropdownParent: $(".select"),
                        width: "100%",
                    });
                    $("#branch_imd_name").val(data.data.branchImd.name);
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