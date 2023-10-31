/* datatable */
$("#document-type_tbl").DataTable({
    aLengthMenu: [
        [10, 30, 50, -1],
        [10, 30, 50, "All"],
    ],
    iDisplayLength: 10,
    ajax: {
        type: "POST",
        url: aurl + "/document-type/listing",
    },
    columns: [
        { data: "no" },
        { data: "name" },
        { data: "action"}
    ],
});

$(document).ready(function(){
    /* Display Add Document Type Modal */
    $("body").on("click", ".document_type_modal_btn", function() {
        $("#document_type_form").validate().resetForm();
        $("#document_type_form").trigger("reset");
        $("#document_type_modal").modal("show");
        $('.document_type_id').val($(this).data('id'));
        $("#depatment_modal_title").text("Add Document Type");
        $("#add_document_type_btn").text("Add Document Type");
    });

    //  Validation For Already Exists Document Type
    $.validator.addMethod("existing_document_type",function(value) {
        var id = $('.document_type_id').val();
        var name=$("#name").val();
        var d = $.ajax({
            url: aurl + "/document-type/check-document-type",
            type: 'POST',
            async: false,
            data: {name:name,id:id},
        }).responseText;
        if (d != 0){ return false; }else return true;
    });
    // Validation Document Type Form
    $('#document_type_form').validate({
        rules: {
            name:{
                required:true,
                existing_document_type: true,
                normalizer: function(value) {
                    return $.trim(value);
                },
            },
        },
        messages: {
            name:{
                required:"Please Enter Document Type Name",
                existing_document_type:"Document Type Name Already Exists"
            }
        },
        highlight: function(element) {
            $(element).removeClass("error");
        }
    });

    // Add Or Update Document Type
    $('#add_document_type_btn').on('click',function(event){
        event.preventDefault();
        var form = $('#document_type_form')[0];
        var formData = new FormData(form);
        if($("#document_type_form").valid()){
            $.ajax({
                url:aurl+"/document-type",
                method:"POST",
                dataType:'JSON',
                data:formData,
                contentType: false,
                processData:false,
                success:function(data){
                    $('#document_type_modal').modal('hide');
                    toaster_message(data.message,data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    // Display Update Document Type Modal
    $('body').on("click", ".edit_document_type", function(event){
        event.preventDefault();
        var id = $(this).data("id");
        $('.document_type_id').val(id);
        $.ajax({
            url: aurl + "/document-type/"+id+"/",
            type: "GET",
            dataType: "JSON",
            data: {id:id},
            success: function(data){
                if (data.status) {
                    $("#document_type_form").validate().resetForm();
                    $("#document_type_form").trigger('reset');
                    $('#document_type_modal').modal('show');
                    $('#document_type_modal_title').text("Update Document Type");
                    $('.add_document_type_btn').text("Update Document Type");
                    $('.name').val(data.data.name);
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
