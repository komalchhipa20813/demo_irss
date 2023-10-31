/* ajax set up */
$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
    },
});
$(function() {
    $.validator.addMethod("pwcheck",function(value, element) {
        return (value.match(/[a-z]/) && value.match(/[A-Z]/) && value.match(/[0-9]/) && value.match(/[_!#@$%^&*]/));
    }, 'Please enter a valid password');
    $.validator.addMethod("oldcheck",function(value) { 
        var x = 0;
        var x = $.ajax({
            url: aurl + "/change-password/old-password-check",
            type: 'POST',
            async: false,
            data: {password:value},
        }).responseText; 
        if (x != 0){ return false; }else return true;
    }, 'Please Enter Currect Password');
    $(".change_password_from").validate({
        rules: {
            oldpassword:
            {
                required: true,
                // minlength : 8,
                oldcheck : true,
            },
            password:
            {
                required: true,
                minlength : 8,
                pwcheck : true,
            },
            confirmpassword:
            {
                required: true,
                minlength : 8,
                equalTo : "#password"
            },

        },
    });
    $(document).on('click','.submit_change_password',function(e){
        e.preventDefault();
        if ($(".change_password_from").valid()) {
            var form = $(".change_password_from")[0];
            var formData = new FormData(form);
            $.ajax({
                type: "post",
                url: aurl + "/change-password",
                data: formData,
                datatype: "json",
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.status == true) {
                        toaster_message(data.message,data.icon,data.redirect_url);
                    } else {
                        $("#old_password_error").text(data.message);
                        $("#new_password_error").text(data.new_message);
                    }
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });
});