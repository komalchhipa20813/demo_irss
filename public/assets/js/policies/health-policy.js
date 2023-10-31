$(document).ready(function(){
    // Listing health policy Details
    if($("#health-policy_tbl").length){
        function policy_listing($data){
            $("#health-policy_tbl").DataTable({
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
                    data:$data,
                    url: aurl + "/health-policy/listing",
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
                "createdRow": function (row, data, dataIndex) {
                    if(data[7]==3){
                        $(row).addClass("error");
                    }
                }
            });
        }
        policy_listing({});
    }
    /* filter policy */
    $('body').on('click','#filter_health_policy',function(){
        $data = {  'agent': $('#agent').val(),
                'name':$('#name').val(),
                'branch':$('#branch').val(),
                'company':$('#company').val(),
                'cheque_no':$('#cheque_no').val(),
                'inward_no':$('#inward_no').val(),
                'policy_no':$('#policy_no').val(),
                'product':$('#product').val(),
                'policy_start_date':$('#policy_start_date').val(),
                'policy_end_date':$('#policy_end_date').val(),
                'from_date':$('#from_date').val(),
                'end_date':$('#end_date').val()
            }
        $('#search_criteria').modal('hide');
        $('#health-policy_tbl').DataTable().destroy();
        policy_listing($data);
    });
    $('body').on('click','#reset_filter',function(){
        $("#health_policy_searching_form").trigger("reset");
        $("#agent").val("0").trigger("change");
        $("#branch").val("0").trigger("change");
        $("#company").val("0").trigger("change");
        $("#product").val("0").trigger("change");
        $('#search_criteria').modal('hide');
        $('#health-policy_tbl').DataTable().destroy();
        policy_listing({});
    });
    // Listing health policy members
    if($("#member_tbl").length){
        function policy_listing($data){
            $("#member_tbl").DataTable({
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
                    data:$data,
                    url: aurl + "/health-policy/add-member-listing",
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
        policy_listing({});
    }
    if($("#health_policy_form").length){
        jQuery.validator.addMethod("number_val", function(value, element) {
            return (this.optional(element) ||/^\-?([0-9]+(\.[0-9]+)?|Infinity)$/i.test(value));
        });
        $.validator.addMethod("policy_number_check", function(value) {
            var x = 0;
            var id = $(".policy_id").val();
            var x = $.ajax({
                url: aurl + "/health-policy/policy-number",
                type: "POST",
                async: false,
                data: { policy_number: value, id: id },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        });
        $("#health_policy_form").validate({
            rules: {
                policy_type: {
                    required: true,
                },
                proposal_dob: {
                    required: true,
                },
                product: {
                    required: true,
                },
                branch: {
                    required: true,
                },
                business_date: {
                    required: true,
                },
                customer: {
                    required: true,
                },
                agent: {
                    required: true,
                },
                code_type:{
                    required: true,  
                },
                company: {
                    required: true,
                },
                company_branch_name: {
                    required: true,
                },
                branch_imd: {
                    required: true,
                },
                sub_product:{
                    required: true,
                },
                product_type:
                {
                    required: true,
                },
                policy_number:{
                    policy_number_check: true,
                },
                sum_insured: {
                    required: true,
                    number_val: true,
                },
                od: {
                    required: true,
                    number_val: true,
                },
                gst: {
                    required: true,
                    number_val: true,
                    max: function(value) {
                        return $(".chkgstvalue").prop("checked") ?50000000 :99;
                    },
                },
                discount:{
                    number_val: true,
                    max:99
                },
                payment_type:
                {
                    required:true,
                },
                cash_amount: {
                    number_val: true,
                    compare_amount: true,
                    required:true,
                },
                cheque_bank: {
                    required: true,
                },
                cheque_number: {
                    required:true,
                    minlength:6,
                    maxlength:6
                },
                cheque_date: {
                    required: true,
                },
                cheque_amount: {
                    required: true,
                    compare_amount: true,
                    number_val: true,
                },
                dd_bank: {
                    required: true,
                },
                dd_number: {
                    required: true,
                },
                dd_date: {
                    required: true,
                },
                dd_amount: {
                    required: true,
                    compare_amount: true,
                    number_val: true,
                },
                transaction_bank: {
                    required: true,
                },
                transaction_date: {
                    required: true,
                },
                online_amount:{
                    number_val: true,
                    compare_amount: true,
                    required : true,
                },
                previouspolicycompany: {
                    required: true,
                },
                pre_start_date:{
                    required : true,
                },
                pre_end_date:{
                    required : true,
                },
                pre_company:{
                    required : true,
                },

            },
            messages: {
                policy_type: {
                    required: "Please Select Policy Type",
                },
                proposal_dob: {
                    required: "Please Select Proposal DOB",
                },
                product: {
                    required: "Please Select Product",
                },
                branch: {
                    required: "Please Select Branch",
                },
                business_date:{
                    required:"Please Select Year And Month",
                },
                customer: {
                    required: "Please Select Customer",
                },
                agent: {
                    required: "Please Select Agent",
                },
                policy_number:{
                    policy_number_check: "Policy Number Already Exist",
                },
                code_type:{
                    required: "Please Select Code Type",  
                },
                company: {
                    required: "Please Select Company",
                },
                company_branch_name: {
                    required: "Please Select Company Branch Name",
                },
                branch_imd: {
                    required: "Please Select Branch IMD",
                },
                
                sub_product: {
                    required: "Please Select Sub Product",
                },
                product_type: {
                    required: "Please Select Product Type",
                },
                sum_insured:{
                    required:"Please Enter Sum Insured",
                    number_val: "Please Enter Number Value Only",
                    maxlength:10

                },
                od: {
                    required: "Please Enter OD",
                    number_val: "Please Enter Number Value Only",
                },
                gst: {
                    required: "Please Enter GST",
                    number_val: "Please Enter Number Value Only",
                },
                discount:{
                    number_val: "Please Enter Number Value Only",
                },
                payment_type: {
                    required: "Please Select Payment Type",
                },
                cash_amount: {
                    required: "Please Enter Amount",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    number_val: "Please Enter Number Value Only",
                },
                cheque_bank: {
                    required: "Please Select Bank Name",
                },
                cheque_number: {
                    required: "Please Enter Cheque Number",
                    minlength:'Minimum 6 Digits Required',
                    maxlength:'Maximum 6 Digits required'
                },
                cheque_date: {
                    required: "Please Select Cheque Date",
                },
                cheque_amount: {
                    number_val: "Please Enter Number Value Only",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    required: "Please Enter Cheque Amount",
                },
                dd_bank: {
                    required: "Please Select Bank Name",
                },
                dd_number: {
                    required: "Please Enter Number",
                },
                dd_date: {
                    required: "Please Select Date",
                },
                dd_amount: {
                    required: "Please Enter Amount",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    number_val: "Please Enter Number Value Only",
                },
                transaction_bank: {
                    required: "Please Select Bank Name",
                },
                transaction_date: {
                    required: "Please Select Transaction Date",
                },
                online_amount: {
                    number_val: "Please Enter Number Value Only",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    required: "Please Enter Amount",
                },
                previouspolicycompany: {
                    required: "Please Select Previous Policy Company",
                },
                pre_start_date:{
                    required :"Please Select Start Date"
                },
                pre_end_date:{
                    required :"Please Select End Date"
                },
                pre_company:{
                    required :"Please Select Previous Company"
                },
            },
            errorPlacement: function(error, element) {
                if (
                    element.parents("div").hasClass("has-feedback") ||
                    element.hasClass("select2-hidden-accessible")
                ) {
                    error.appendTo(element.parent());
                } else if (
                    element.parents("div").hasClass("uploader") ||
                    element.hasClass("datepicker") ||
                    element.hasClass("business_date")
                ) {
                    error.appendTo(element.parent().parent());
                } else if (element.hasClass("form-check-input")) {
                    error.appendTo(element.parent().parent().parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).removeClass("error");
            },
            normalizer: function(value) {
                return $.trim(value);
            },
        });
    }
    /* adding and updating health policy data */
    $(".submit_policy").on("click", function(event) {
        event.preventDefault();
        var form = $("#health_policy_form")[0];
        var formData = new FormData(form);
        if ($("#health_policy_form").valid()) {
            $.ajax({
                url: aurl + "/health-policy",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toaster_message(data.message, data.icon, data.redirect_url);
                },
                error: function(request) {
                    toaster_message("Something Went Wrong! Please Try Again.","error");
                },
            });
        }
    });

    $('body').on("change", "#od", function(event){
        total_premium()
    });
    $('body').on("change", "#stamp_duty", function(event){
        total_premium()
    });
    $("body").on("change", "#gst", function(event) {
        total_premium();
    });
    /* validation of add member */
    if($("#addMemberForm").length){
        $("#addMemberForm").validate({
            rules: {
                relation: {
                    required: true,
                },
                name: {
                    required: true,
                },
                sum_insured: {
                    required: true,
                    number_val: true,
                },
                birthdate: {
                    required: true,
                },
    
            },
            messages: {
                relation: {
                    required: "Please Select Relation",
                },
                name: {
                    required: "Please Enter Name",
                },
                sum_insured: {
                    required: "Please Enter Sum Insured",
                    number_val: "Please Enter Number Value Only",
                },
                birthdate: {
                    required: "Please Select Birth Date",
                },
    
            },
            highlight: function(element) {
                $(element).removeClass("error");
            },
            normalizer: function(value) {
                return $.trim(value);
            },
        });
    }
    /* Adding Member Data */
    $("#btnaddmember").on("click", function(event) {
        event.preventDefault();
        var form = $("#addMemberForm")[0];
        var formData = new FormData(form);
        if ($("#addMemberForm").valid()) {
            $.ajax({
                url: aurl + "/health-policy/store-member",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#addMemberForm").trigger("reset");
                    $("#relation").val("0").trigger("change");
                    add_member_toaster_message(data.message,data.icon);
                },
                error: function(request) {
                    toaster_message("Something Went Wrong! Please Try Again.","error");
                },
            });
        }
    });
    /* Deleting Member Detail data */
    $("body").on("click", ".member_delete", function(event) {
        var id = $(this).attr("data-id");
        $.ajax({
            url: aurl + "/health-policy/delete/" + id + "/",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                add_member_toaster_message(data.message, data.icon);
            },
            error: function(request) {
                toaster_message("Something Went Wrong! Please Try Again.","error");
            },
        });
    });
    $("#add_member").click(function() {
        $(".member_relation_wrapper").toggle(this.checked);
    });
    /* display existing member data to update policy */
    if($('.submit_policy').text() == 'Update' && $('#add_member').val()==1)
    {
        $(".member_relation_wrapper").toggle(this.checked);
        var id = $('#policy_id').val();
        $.ajax({
            url: aurl + "/health-policy/get-member-data/" + id + "/",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                if(data){
                    $("#member_tbl").DataTable().ajax.reload();
                }else{
                    add_member_toaster_message(data.message, data.icon);
                }
            },
            error: function(request) {
                toaster_message("Something Went Wrong! Please Try Again.","error");
            },
        });

    }

    // export data
    $("#export").click(function(event){

        var data = {  
                'agent': $('#agent').val(),
                'name':$('#name').val(),
                'branch':$('#branch').val(),
                'company':$('#company').val(),
                'cheque_no':$('#cheque_no').val(),
                'inward_no':$('#inward_no').val(),
                'policy_no':$('#policy_no').val(),
                'product':$('#product').val(),
                'policy_start_date':$('#policy_start_date').val(),
                'policy_end_date':$('#policy_end_date').val(),
                'from_date':$('#from_date').val(),
                'end_date':$('#end_date').val()
            }

        
        $.ajax({
                url: aurl + "/health-policy/export-data",
                type: "POST",
                data: {
                        data:data,
                      },
                cache: false,
                 beforeSend: function() {

                     $("#export").prop('disabled', true);
                     $("#export").text('Excel downloading..');
                    },
                success: function(response) {
                        if(response.status)
                        {
                            var a = document.createElement("a");
                            a.href = response.file; 
                            a.download = response.name;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                                    $("#export").prop('disabled', false);
                                    $("#export").text('Export Health Policy');
                        }
                        else
                        {
                            $("#export").prop('disabled', false);
                                    $("#export").text('Export Health Policy');
                                    toaster_message(response.msg,response.icon);
                        }
                    
                      },
                      error: function (ajaxContext) {
                        $("#downloadExcel").show();
                        $("#ExcelDownloadLoader").hide();
                      },
            });

    });
});

function total_premium() {
    var od=parseInt($('#od').val());
    var gst=parseInt($('#gst').val());
    var stamp_duty = $("#stamp_duty").val() == "" ? 0 : parseInt($("#stamp_duty").val());

    if(!isNaN(od)&&!isNaN(gst)){
        value = parseInt($('.chkgstvalue').prop('checked')?gst:(gst*od/100))
        $('#total_premium').val(Math.round(od+value+stamp_duty));
    }else{
        $('#total_premium').val('');
    }
}
function gstValueChange(){
    total_premium()
    $('#gst').attr("placeholder", ($('.chkgstvalue').prop('checked'))?"Enter GST":"Enter GST in %");
}


