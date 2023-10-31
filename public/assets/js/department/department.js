// Listing Department Details
$("#department_tbl").DataTable({
    aLengthMenu: [
        [10, 30, 50, -1],
        [10, 30, 50, "All"],
    ],
    iDisplayLength: 10,

    ajax: {
        type: "POST",
        url: aurl + "/department/listing",
    },
    columns: [
        { data: "no" },
        { data: "name" },
        { data: "action"}
    ],
});

$(document).ready(function(){
    // Display Add Department Modal
    $("body").on("click", ".department_modal_btn", function() {
        $("#department_form").validate().resetForm();
        $("#department_modal").modal("show");
        $("#department_form").trigger("reset");
        $(".department_id").val($(this).data("id"));
        $("#depatment_modal_title").text("Add Department");
        $("#add_department_btn").text("Add Department");
    });

    // Check Availability Of The Department
    $.validator.addMethod("existing_department",function(value) {
        var d = 0;
        var id = $('.department_id').val();
        var name=$("#department_name").val();
        var d = $.ajax({
            url: aurl + "/department/check-department",
            type: 'POST',
            async: false,
            data: {name:name,id:id},
        }).responseText;
        if (d != 0){ return false; }else return true;
    });

    // Validate Department Form
    $('#department_form').validate({
        rules: {
            department_name:{
                required:true,
                existing_department: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },
        messages: {
            department_name:{
                required:"Please Enter Department Name",
                existing_department:"Department Name Already Exists"
            }
        },
        highlight: function(element) {
            $(element).removeClass("error");
        }
    });

    // Add Or Update Department data
    $('#add_department_btn').on('click',function(event){
        if($("#department_form").valid()){
            event.preventDefault();
            var form = $('#department_form')[0];
            var formData = new FormData(form);
            $.ajax({
                url:aurl+"/department",
                method:"POST",
                dataType:'JSON',
                data:formData,
                contentType: false,
                processData:false,
                success:function(data){
                    $('#department_modal').modal('hide');
                    toaster_message(data.message,data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            })
        }
    });

    // Show Update Department Modal
    $('body').on("click", ".edit_department", function(event){
        var id = $(this).data("id");
        $('.department_id').val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/department/"+id+"/",
            type: "GET",
            dataType: "JSON",
            data: {id:id},
            success: function(data){
                if (data.status) {
                    $("#department_form").validate().resetForm();
                    $("#department_form").trigger('reset');
                    $('#department_modal').modal('show');
                    $('#depatment_modal_title').text("Update Department");
                    $('.add_department_btn').text("Update Department");
                    $('.department_name').val(data.data.name);
                } else {
                    toaster_message(data.message,data.icon);
                }
            },
            error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
        });
    });
});
