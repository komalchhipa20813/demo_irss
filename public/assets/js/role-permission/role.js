/* datatable */
if($('#role_tbl').length){
    $('#role_tbl').DataTable({
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
            url: aurl + "/role/listing", 
        },
        'columns': [
            { data: '0' },
            { data: '1' },
            { data: '2' },
        ]
    });
}
  
$(document).ready(function(){
    if($("#role_form").length){
        $("#role_form").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 35,
                    rolecheck: true,
                    normalizer: function(value) {
                        return $.trim(value);
                    },
                },
                "permission[]":"required",
            },
            highlight: function(element) {
                $(element).removeClass("error");
            },
            messages: {
                title: {
                    required: "Please Enter Role Title",
                    rolecheck: "Role Name Already Exists",
                },
                "permission[]": "Please Select At Least One Permission For Role!",
            },
            errorPlacement: function (label, element) {
                if(element.attr("type") == "checkbox"){
                    label.insertAfter(element.closest(".check")); 
                }else{
                    label.insertAfter(element);
                }
            },
        });
        $.validator.addMethod(
            "rolecheck",
            function(value) {
                var x = 0;
                var id = $(".role_id").val();
                var x = $.ajax({
                    url: aurl + "/role/role-check",
                    type: "POST",
                    async: false,
                    data: { title: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );
    }

    /* adding and updating role data */    
    $(".submit_role").on("click", function(event){
        event.preventDefault();
        var form = $('#role_form')[0];
        var formData = new FormData(form);
        if($("#role_form").valid()){   
            $.ajax({
                url: aurl + "/role",
                type: 'POST',
                dataType: "JSON",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('#role_modal').modal('hide');
                    toaster_message(data.message,data.icon,data.redirect_url);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* Select All Permissions */
    $("#selectall").change(function(){
        var status = this.checked;
        $('.permission').each(function(){
            this.checked = status;
        });
    });
    /* Change Select All According Checked Or Unchecked */
    $('.permission').change(function(){
        if(this.checked == false){
            $("#selectall")[0].checked = false;
        }
        if ($('.permission:checked').length == $('.permission').length ){ 
            $("#selectall")[0].checked = true;
        }
    });
    /* Checked Select All While Update Data If All Permission Are Selected */
    if ($('.permission:checked').length && $('.permission:checked').length == $('.permission').length ){ 
        $("#selectall")[0].checked = true;
    }
});