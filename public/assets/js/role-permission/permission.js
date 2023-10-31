/* datatable */
$('#permission_tbl').DataTable({
    "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "All"]
    ],
    "iDisplayLength": 10,
    "language": {
        search: ""
    },
    'ajax': {
        type:'POST',
        url: aurl + "/permission/listing",
    },
    'columns': [
        { data: '0' },
        { data: '1' },
        { data: '2' },
    ]
});

$(document).ready(function() {
    /* Validation Of Permission */
    $("#permission_form").validate({
        rules: {
            name: {
                required: true,
                maxlength: 35,
                permissionCheck: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
        messages: {
            name: {
                required: "Please Enter Permission Name",
                permissionCheck: "Permission Name Already Exists",
            },
        },
    });
    $.validator.addMethod("permissionCheck", function(value) {
        var id = $(".permission_id").val();
        var x = $.ajax({
            url: aurl + "/permission/permission-check",
            type: "POST",
            async: false,
            data: { name: value, id: id },
        }).responseText;
        if (x != 0) {
            return false;
        } else return true;
    });

    /* Display Add Permission Modal */
    $("body").on("click", ".add_permission", function() {
        $("#permission_form").validate().resetForm();
        $("#permission_form").trigger('reset');
        $('#permission_modal').modal('show');
        $('.permission_id').val($(this).data('id'));
        $('#title_permission_modal').text("Add Permission");
        $('.submit_permission').text("Save Permission");
    });

    /* Adding And Updating Permission Data */
    $(".submit_permission").on("click", function(event) {
        event.preventDefault();
        var form = $("#permission_form")[0];
        var formData = new FormData(form);
        if ($("#permission_form").valid()) {
            $.ajax({
                url: aurl + "/permission",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#permission_modal").modal("hide");
                    toaster_message(data.message, data.icon, data.redirect_url);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Display Update Permission Modal */
    $("body").on("click", ".permission_edit", function(event) {
        var id = $(this).data("id");
        $(".permission_id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/permission/{" + id + "}",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#permission_form").validate().resetForm();
                    $("#permission_form").trigger('reset');
                    $('#title_permission_modal').text("Update Permission");
                    $('#permission_modal').modal('show');
                    $('.submit_permission').text("Update Permission");
                    $('.name').val(data.name);
                }else{
                    toaster_message(data.message,data.icon,data.redirect_url);
                }
            },
            error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
        });
    });
});