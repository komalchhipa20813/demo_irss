// Listing agent Details
if($("#agent_tbl").length){
    function loadListing(filterdata){
        $("#agent_tbl").DataTable({
            processing: true,
            serverSide: true,
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
                data:filterdata,
                url: aurl + "/agent/listing",
            },
            columns: [
                { data: '0' },
                { data: '1' },
                { data: '2' },
                { data: '3' },
                { data: '4' },
                { data: '5' },
                { data: '6' },
            ],
        });
    }
    loadListing({});
}

// Listing agent document Details
if($("#document_tbl").length){
    $("#document_tbl").DataTable({
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
            url: aurl + "/agent/document/listing",
        },
        columns: [
            { data: '0' },
            { data: '1' },
            { data: '2' },
            { data: '3' },
            { data: '4' },
        ],
    });
}
$(document).ready(function(){
    /* display profile photo */
    $('#filename_agent').change(function(events){
        $("#agent_image_preview").fadeIn("fast").attr('src',URL.createObjectURL(events.target.files[0]));
    });
    /* select and disabled service branch */
    $('.service_branch').change(function(){
        var valueSelected=$(this).val();

        if(valueSelected[0] == 0)
        {
            $('.select2-selection__choice').next('.select2-selection__choice').remove();
            $('.branch_val').prop('disabled', true);
        }
        else
        {
            $('.branch_val').prop('disabled', false);
        }
    });
    if($("#agent_form").length){
        $("#agent_form").validate({
            rules: {
                branch:{
                    required: true,
                },
                fdo:{
                    required: true,
                },
                email:
                {
                    email:true,
                    agentEmailCheck:true,
                },
                secondary_email:
                {
                    email:true,
                },
                prefix: {
                    required: true,
                },
                first_name: {
                    required: true,
                },
                middle_name: {
                    required: true,
                },
                last_name: {
                    required:true,
                },
                phone:
                {
                    required:true,
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                },
                secondary_phone:
                {
                    number: true,
                    minlength: 10,
                    maxlength: 10,
                },
                office_address: {
                    required:true,
                },
                residential_address: {
                    required:true,
                },
                gender: {
                    required:true,
                },
                dob: {
                    required:true,
                },
                joining_date: {
                    required:true,
                },
                sales_manager:{
                    required:true,
                },
                adharcard_number:{
                    required:true,
                    adharcard_number:true,
                },
                pancard_number:{
                    required:true,
                    pancard_number:true,
                }
            },
            messages: {
                branch:{
                    required:"Please Select Branch",
                },
                fdo:{
                    required:"Please Select FDO",
                },
                designation:{
                    required:"Please Select Designation",
                },
                email:
                {
                    agentEmailCheck:"Email Name Already Exists",
                },
                prefix:{
                    required:"Please Select Prefix",
                },
                first_name: {
                    required:"Please Enter First Name.",
                },
                middle_name: {
                    required:"Please Enter Middle Name.",
                },
                last_name: {
                    required:"Please Enter Last Name.",
                },
                phone:
                {
                    required:"Please Enter Mobile Number.",
                    number:'Only Numbers Allow',
                    minlength:'Minimum 10 Digits Required',
                    maxlength:'Maximum 10 Digits required'
                },
                secondary_phone:
                {
                    number:'Only Numbers Allow',
                    minlength:'Minimum 10 Digits Required',
                    maxlength:'Maximum 10 Digits required'
                },
                office_address: {
                    required:"Please Enter Office Address",
                },
                residential_address: {
                    required:"Please Enter Residential Address.",
                },
                gender: {
                    required:"Please Select gender.",
                },
                dob: {
                    required:"Please Select Date Of Birth.",
                },
                joining_date: {
                    required:"Please Select Joining Date.",
                },
                sales_manager:{
                    required:"Please Select Sales Manager.",
                },
                adharcard_number:{
                    required: "Please Enter Adhar Card Number",
                    adharcard_number:"Adhar Card Number Already Exists",
                },
                pancard_number:{
                    required: "Please Enter Pan Card Number",
                    pancard_number:"Pan Card Number Already Exists",
                }
            },
            errorPlacement: function(error, element) {
                if (element.parents("div").hasClass("has-feedback") || element.hasClass("select2-hidden-accessible")) {
                    error.appendTo(element.parent());
                }else if(element.parents("div").hasClass("uploader")||element.hasClass("datepicker")||element.hasClass("dobdatePicker")){
                    error.appendTo(element.parent().parent());
                }else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).removeClass("error");
            },
        });
        $.validator.addMethod(
            "agentEmailCheck",
            function(value) {
                var x = 0;
                var id = $(".agent_id").val();
                var x = $.ajax({
                    url: aurl + "/agent/agent-check",
                    type: "POST",
                    async: false,
                    data: { email: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );
        $.validator.addMethod(
            "adharcard_number",
            function(value) {
                var x = 0;
                var id = $(".fdo_id").val();
                var x = $.ajax({
                    url: aurl + "/agent/adharcard_number",
                    type: "POST",
                    async: false,
                    data: { adharcard_number: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );
        $.validator.addMethod(
            "pancard_number",
            function(value) {
                var x = 0;
                var id = $(".fdo_id").val();
                var x = $.ajax({
                    url: aurl + "/agent/pancard_number",
                    type: "POST",
                    async: false,
                    data: { pancard_number: value, id: id},
                }).responseText;
                if (x != 0) {
                    return false;
                } else return true;
            },
        );
        $.validator.addMethod("pwcheck",function(value, element) {
            return (value.match(/[a-z]/) && value.match(/[A-Z]/) && value.match(/[0-9]/) && value.match(/[_!#@$%^&*]/));
        })
    }
    if($("#document_form").length){
        $("#document_form").validate({
            rules: {
                document_file: {
                    required: true,
                    extension: "png|jpg|jpeg|pdf"
                },
                document_number:{
                    required: true,
                },
            },
            messages: {
                document_file:{
                    required:"Please Upload Document Photo",
                },
                document_number:{
                    required:"Please Select Document Number",
                },
            },
            errorPlacement: function(error, element) {
                if (element.parents("div").hasClass("has-feedback") || element.hasClass("select2-hidden-accessible")) {
                    error.appendTo(element.parent());
                }else if(element.parents("div").hasClass("uploader")||element.hasClass("datepicker")){
                    error.appendTo(element.parent().parent());
                }else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).removeClass("error");
            }
        });
    }
    $('.document_type').change(function(){
        $('.document_detail').show(true);
    });

    /* adding and updating agent data */
    $(".submit_agent").on("click", function(event){
        event.preventDefault();
        var form = $('#agent_form')[0];
        var formData = new FormData(form);
        if($("#agent_form").valid()){
            $.ajax({
                url: aurl + "/agent",
                type: 'POST',
                dataType: "JSON",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toaster_message(data.message,data.icon,data.redirect_url);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });
    /* adding and updating document data */
    $('body').on("click", ".add_document", function(event){
        event.preventDefault();
        var form = $('#document_form')[0];
        var formData = new FormData(form);
        formData.append("document_name", $('#document_type').find(':selected').data('name'))

        if($("#document_form").valid()){
            $.ajax({
                url: aurl + "/agent/document",
                type: 'POST',
                dataType: "JSON",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.status){
                        $("#document_form").trigger("reset");
                        $(".document_type").val(0).trigger("change");
                        $('.document_type option[value='+data.document_type+']').prop('disabled', true)
                        document_toaster_message(data.message,data.icon,data.redirect_url);
                    }
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

    /* deleting document data */
    $('body').on("click", ".document_delete", function(event){
        var id = $(this).data("id");
        $.ajax({
            url: aurl + "/agent/document/delete/"+id+"/",
            type: "POST",
            dataType: "JSON",
            success: function(data){
                document_toaster_message(data.message,data.icon);
            },
            error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
        });
    });

    $('body').on("click", ".filter_agent_record", function(event){
        event.preventDefault();
        var data = {
            'fdo_code':$('#fdo_code').val(),
            'agent_code':$('#agent_code').val(),
            'agent_name':$('#agent_name').val(),
            'business_category':$('#business_category').val(),
            'home_branch':$('#home_branch').val(),
            'sales_manager':$('#sales_manager').val(),
            'pancard_no':$('#pancard_no').val(),
            'aadharcard_no':$('#aadharcard_no').val(),
            'mobile_no':$('#mobile_no').val(),
        }
        $('#search_criteria').modal('hide');
        $("#agent_tbl").DataTable().destroy();
        loadListing(data);
    });
    $('body').on("click", ".reset_filter", function(event){
        event.preventDefault();
        $("#agent_filter_section").trigger("reset");
        $('.select2').val(0).trigger("change");
        $("#agent_filter_section input").val("");
        $('#search_criteria').modal('hide');
        $("#agent_tbl").DataTable().destroy();
        loadListing({});
    });

   
    
    /* fetching service branch of fdos */
    $("#fdo").on("change", function() {
        fdo();
    });
    /* fetching sales manager of service branch */
    $('body').on("change","#branch", function() {
        var branch_id = this.value;
        branch_change(branch_id);
    });

    /*edit agent popup data */
    if($('.agent_edit').val() == '1')
    {
        fdo();
       var branch_id= $('.exit_branch').val();
        branch_change(branch_id);
    }

    $.validator.addMethod("pwcheck", function(value, element) {
        return (
            value.match(/[a-z]/) &&
            value.match(/[A-Z]/) &&
            value.match(/[0-9]/) &&
            value.match(/[_!#@$%^&*]/)
        );
    }),
    
    $("#password_form").validate({
        rules: {
            password: {
                required: true,
                minlength: 8,
                pwcheck: true,
            },
            confirmpassword: {
                required: true,
                equalTo: "#password",
            },
        },

        messages: {
            password: {
                required: "Please Enter Password",
                minlength: "Your Password Must Be At Least 8 Characters Long",
                pwcheck: "Please Enter Atleast One Uppercase, Number And Special Character!",
            },
            confirmpassword: {
                required: "This value should not be blank.",
            },
        },

        highlight: function(element) {
            $(element).removeClass("error");
        },
    });


    /* Add Country Modal */
    $("body").on("click", ".change-pwd", function() {
        $("#password_form").validate().resetForm();
        $("#password_form").trigger("reset");
        $("#password_modal").modal("show");
        $(".id").val($(this).data("id"));
        $(".submit_password").text("Save");
    });

    $(".submit_password").on("click", function(event) {
        event.preventDefault();
        var form = $("#password_form")[0];
            var formData = new FormData(form);
            if ($("#password_form").valid()) {
                $.ajax({
                    url: aurl + "/agent/change-password",
                    type: "POST",
                    dataType: "JSON",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $("#password_modal").modal("hide");
                        toaster_message(
                            data.message,
                            data.icon,
                            data.redirect_url
                        );
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
});

function fdo()
{
    var fdo_id = $('#fdo').val();
        $.ajax({
            url: aurl + "/fdo/get-data",
            type: "POST",
            data: {
                fdo_id: fdo_id,
            },
            dataType: "json",
            success: function(result) {
                var html = "<option selected disabled value='0'>Please ";
                html +=
                    result.branch.length == 0 ?
                    fdo_id == 0 ?
                    "First Select FDO" :
                    "First Enter Service Branch" :
                    "Select";
                html += "</option>";
                $("#branch").html(html);

                var exit_id=$('.exit_branch').val();
                
                $.each(result.branch, function(key, value) {
                    var selected =(exit_id == value.id)?'selected':'';
                    $("#branch").append('<option value="' +value.id +'" '+selected+'>' +value.name +"</option>");
                });
            },
            error: function(request) {
                toaster_message("Something Went Wrong! Please Try Again.", "error");
            },
        });
}

function branch_change(branch_id)
{
    
        $.ajax({
            url: aurl + "/retinue-branch/get-data",
            type: "POST",
            data: {
                branch_id: branch_id,
            },
            dataType: "json",
            success: function(result) {
                var html = "<option selected disabled value='0'>Please ";
                html +=
                    result.sales_manager.length == 0 ?
                    branch_id == 0 ?
                    "First Select Home Branch" :
                    "First Enter Sales Manager" :
                    "Select";
                html += "</option>";
                $("#sales_manager").html(html);
                var exit_id=$('.exit_sales_manager').val();
                console.log(exit_id);
                $.each(result.sales_manager, function(key, value) {
                    var selected =(exit_id == value.id)?'selected':'';
                    $("#sales_manager").append('<option value="' +value.id +'" '+selected+'>' +value.first_name+' '+value.middle_name+' '+value.last_name+"</option>");
                });
            },
            error: function(request) {
                toaster_message("Something Went Wrong! Please Try Again.", "error");
            },
        }); 
}

