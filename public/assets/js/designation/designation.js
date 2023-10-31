// Listing Designation Details
$("#designation_tbl").DataTable({
    aLengthMenu: [
        [10, 30, 50, -1],
        [10, 30, 50, "All"],
    ],
    iDisplayLength: 10,
    ajax: {
        type: "POST",
        url: aurl + "/designation/listing",
    },
    columns: [
        { data: "no" },
        { data: "name" },
        { data: "action"}
    ],
});
$(document).ready(function(){
     // Validation For Existing Designation Name
     $.validator.addMethod("existing_designation",function(value) {
        var x = 0;
        var id = $('.designation_id').val();
        var name=$("#name").val();
        var x = $.ajax({
            url: aurl + "/designation/check-designation",
            type: 'POST',
            async: false,
            data: {name:name,id:id},
        }).responseText;
        if (x != 0){ return false; }else return true;
    },);

    // Validate Designation Form
    $('#designation_form').validate({
        rules: {
            name:{
                required: true,
                existing_designation: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },
        messages: {
            name:{
                required:"Please Enter Designation Name",
                existing_designation:"Designation Name Already Exists",
            }
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
    });

    // Display Add Designation Modal
    $('body').on("click", ".add_designation", function(){
        $("#designation_form").validate().resetForm();
        $("#designation_form").trigger('reset');
        $('#designation_modal').modal('show');
        $('.designation_id').val($(this).data('id'));
        $('#designation_modal_title').text("Add Designation");
        $('.submit_designation').text("Add Designation");
    });

    // Add Or Update Designation Data
    $(".submit_designation").on("click", function(event){
        event.preventDefault();
        var form = $('#designation_form')[0];
        var formData = new FormData(form);
        if($("#designation_form").valid()){
            $.ajax({
                url: aurl + "/designation",
                type: 'POST',
                dataType: "JSON",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#designation_modal').modal('hide');
                    toaster_message(data.message,data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    // Display Update Designation Modal
    $('body').on("click", ".edit_designation", function(event){
        var id = $(this).data("id");
        $('.designation_id').val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/designation/"+id+"/edit",
            type: "GET",
            dataType: "JSON",
            data: {id:id},
            success: function(data){
                if(data.status){
                    $("#designation_form").validate().resetForm();
                    $("#designation_form").trigger('reset');
                    $('#designation_modal').modal('show');
                    $('#designation_modal_title').text("Update Designation");
                    $('.submit_designation').text("Update Designation");
                    $('.name').val(data.data.name);
                }else{
                    toaster_message(data.message,data.icon);
                }
            },
            error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
        });
    });
})
