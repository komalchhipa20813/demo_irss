$(document).ready(function() {
    // Listing sme policy Details
    if($("#sme-policy_tbl").length){
        function policy_listing($data){
            $("#sme-policy_tbl").DataTable({
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
                    url: aurl + "/sme-policy/listing",
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
    $('body').on('click','#filter_sme_policy',function(){
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
            $('#sme-policy_tbl').DataTable().destroy();
            policy_listing($data);
    });
    $('body').on('click','#reset_filter',function(){
        $("#sme_policy_searching_form").trigger("reset");
        $("#agent").val("0").trigger("change");
        $("#branch").val("0").trigger("change");
        $("#company").val("0").trigger("change");
        $("#product").val("0").trigger("change");
        $('#search_criteria').modal('hide');
        $('#sme-policy_tbl').DataTable().destroy();
        policy_listing({});
    });
    if($(".sme_policy_form").length){
        jQuery.validator.addMethod("number_val", function(value, element) {
            return this.optional(element) || /^\-?([0-9]+(\.[0-9]+)?|Infinity)$/i.test(value);
        });
        $.validator.addMethod("policy_number_check", function(value) {
            var x = 0;
            var id = $(".policy_id").val();
            var x = $.ajax({
                url: aurl + "/sme-policy/policy-number",
                type: "POST",
                async: false,
                data: { policy_number: value, id: id },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        });
        $(".sme_policy_form").validate({
            rules: {
                product: {
                    required: true,
                },

                business_date:{
                    required: true,
                },
                customer:{
                    required: true,
                },
                policyrenewstatus: {
                    required: true,
                },
                agent: {
                    required: true,
                },
                customer_select: {
                    required: true,
                },
                irss_branch: {
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
                sum_insured: {
                    required: true,
                    number_val: true,
                },
                od: {
                    required: true,
                    number_val: true,
                },
                terrorism: {
                    number_val: true,
                },
                gst: {
                    required:true,
                    number_val: true,
                    max:function(value) {
                        return $('.chkgstvalue').prop('checked')?50000000:99
                    },
                },
                discount:{
                    number_val: true,
                    max:99
                },
                total_premium: {
                    required: true,
                },
                payment_type: {
                    required: true,
                },
                policy_type: {
                    required: true,
                },
                policyprm: {
                    required: true,
                    number_val: true,
                },
                policy_number:{
                    policy_number_check: true,
                },
                policyterrorism: {
                    required: true,
                    number_val: true,
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
                    required:true,
                },
                cheque_number: {
                    required:true,
                    minlength:6,
                    maxlength:6
                },
                cheque_date:{
                    required:true
                },
                cheque_amount:{
                    required:true,
                    compare_amount: true,
                    number_val: true,
                },
                dd_bank: {
                    required:true
                },
                dd_number:{
                    required: true,
                },
                dd_date:{
                    required: true
                },
                dd_amount:{
                    required: true,
                    compare_amount: true,
                    number_val: true,
                },
                transaction_bank:{
                    required : true
                },
                transaction_date:{
                    required:true
                },
                online_amount:{
                    number_val: true,
                    compare_amount: true,
                    required : true,
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
                previouspolicycompany:{
                    required : true,
                },
                co_sharing_policy_type:{
                    required :true
                },
                occupancies:{
                    required :true
                },
            },
            messages: {
                product: {
                    required: "Please Select Product",
                },
                business_date: {
                    required: "Please Select Year And Month",
                },
                customer:{
                    required:"Please Select Customer",
                },
                policyrenewstatus: {
                    required: "Please Select Policy Renew Status",
                },
                agent: {
                    required: "Please Select Agent",
                },
                customer_select: {
                    required: "Please Select Customer",
                },

                irss_branch: {
                    required: "Please Select IRSS Branch",
                },
                previouspolicycompany: {
                    required: "Please Select Previous Policy Company",
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
                sum_insured: {
                    required: "Please Enter Sum Insured",
                    number_val: "Please Enter Number Value Only",
                },
                od: {
                    required: "Please Enter OD",
                    number_val: "Please Enter Number Value Only",
                },
                terrorism: {
                    required: "Please Enter Terrorism Premium",
                    number_val: "Please Enter Number Value Only",
                },
                gst: {
                    required: "Please Enter GST",
                    number_val: "Please Enter Number Value Only",
                },
                discount:{
                    number_val: "Please Enter Number Value Only",
                },
                total_premium: {
                    required: "Please Enter Total Premium",
                },
                payment_type: {
                    required: "Please Select Payment Type",
                },
                policy_type: {
                    required: "Please Select Policy Type",
                },
                policyprm: {
                    required: "Please Enter Policy Premium",
                },
                policy_number:{
                    policy_number_check: "Policy Number Already Exist",
                },
                policyterrorism: {
                    required: "Please Enter Policy Terrorism Premium",
                },
                share: {
                    required: "Please Enter Share",
                },
                payment_type:
                {
                    required:"Please Select Payment Type",
                },
                cash_amount: {
                    required:"Please Enter Amount",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    number_val: "Please Enter Number Value Only",
                },
                cheque_bank: {
                    required:"Please Select Bank Name",
                },
                cheque_number: {
                    required:"Please Enter Cheque Number",
                    minlength:'Minimum 6 Digits Required',
                    maxlength:'Maximum 6 Digits required'
                },
                cheque_date:{
                    required:"Please Select Cheque Date"
                },
                cheque_amount:{
                    number_val: "Please Enter Number Value Only",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    required: "Please Enter Cheque Amount"
                },
                dd_bank: {
                    required:"Please Select Bank Name"
                },
                dd_number:{
                    required: "Please Enter Number"
                },
                dd_date:{
                    required :"Please Select Date"
                },
                dd_amount:{
                    required :"Please Enter Amount",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    number_val: "Please Enter Number Value Only",
                },
                transaction_bank:{
                    required : "Please Select Bank Name"
                },
                transaction_date:{
                    required :"Please Select Transaction Date"
                },
                online_amount:{
                    number_val: "Please Enter Number Value Only",
                    compare_amount:"Total Premium Amount And Amount Should Be Same",
                    required :"Please Enter Amount"
                },
                pre_start_date:{
                    required :"Please Select Start Date"
                },
                pre_end_date:{
                    required :"Please Select End Date"
                },
                pre_company:{
                    required :"Please Select Company"
                },
                co_sharing_policy_type:{
                    required :"Please Select Company"
                },
                occupancies:{
                    required :"Please Enter Occupancies"
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
                    element.hasClass("datepicker")||element.hasClass("business_date")
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

    /* Adding And Updating Sme Policy Data */
    $(".submit_policy").on("click", function(event) {
        event.preventDefault();
        var form = $(".sme_policy_form")[0];
        var formData = new FormData(form);
        var poData = $(".co_sharing_details_form").serializeArray();
        for (var i=0; i<poData.length; i++)
        formData.append(poData[i].name, poData[i].value);
        if ($(".sme_policy_form").valid()) {
                $.ajax({
                url: aurl + "/sme-policy",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#employee_modal").modal("hide");
                    toaster_message(data.message, data.icon, data.redirect_url);
                },
                error: function(request) {
                    toaster_message(
                        "Somethings Went Wrong! Please Try Again.",
                        "error"
                    );
                },
            });
        }
    });
    if($("#co_sharing_details_form").length){
        $("#co_sharing_details_form").validate({
            rules: {
                cosharecompany: {
                    required: true,
                },
                share: {
                    required: true,
                },
            },
            messages: {
                cosharecompany: {
                    required: "Please Enter Company Name",
                },
                share: {
                    required: "Please Enter Share",
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
            normalizer: function(value) {
                return $.trim(value);
            },
        });
    }

    // Listing Co-Sharing Details
    if ($("#co_sharing_detail").length) {
        $("#co_sharing_detail").DataTable({
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
                url: aurl + "/sme-policy/co-sharing/listing",
            },
            columns: [
                { data: "0" },
                { data: "1" },
                { data: "2" },
                { data: "3" },
                { data: "4" },
                { data: "5" },
                { data: "6" },
            ],
        });
    }
    /* Adding Co-Sharing Data */
    $("body").on("click", "#btnaddcosharing", function(event) {
        event.preventDefault();
        var form = $("#co_sharing_details_form")[0];
        var formData = new FormData(form);
        formData.append(
            "company_name",
            $( "#cosharecompany option:selected" ).text()
        );

        if ($("#co_sharing_details_form").valid()) {
            $.ajax({
                url: aurl + "/sme-policy/co-sharing-detail",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.status){
                        co_sharing_toaster_message(data.message,data.icon,data.data);
                        $("#cosharecompany").val(0).trigger("change");
                    }else{
                        toaster_message(data.message,data.icon);
                    }
                },
                error: function(request) {
                    toaster_message("Something Went Wrong! Please Try Again.","error");
                },
            });
        }
    });

    /* Deleting Co-Sharing Detail data */
    $("body").on("click", ".co_sharing_delete", function(event) {
        var id = $(this).data("id");
        $.ajax({
            url: aurl + "/sme-policy/co-sharing/delete/" + id + "/",
            type: "POST",
            dataType: "JSON",
            success: function(data) {
                co_sharing_toaster_message(data.message, data.icon,data.data);
            },
            error: function(request) {
                toaster_message(
                    "Something Went Wrong! Please Try Again.",
                    "error"
                );
            },
        });
    });
    $('body').on("change", "#od", function(event){
        total_premium()
    });
    $('body').on("change", "#stamp_duty", function(event){
        total_premium()
    });
    $('body').on("change", "#gst", function(event){
        total_premium()
    });
    $('body').on("change", "#terrorism", function(event){
        total_premium()
    });
    $('body').on("change", "#policyprm", function(event){
        co_sharing_values()
    });
    $('body').on("change", "#policyterrorism", function(event){
        co_sharing_values()
    });
    $('body').on("change", "#share", function(event){
        co_sharing_values()
    });
    $('body').on("change", "#Bshare", function(event){
        co_sharing_values()
    });
    $(".co_sharing").click(function() {
        $("#cosharedetails").toggle(this.checked);
    });
    $(".policy_type").on("change", function() {
        $("#od").val('');
        $("#terrorism").val('');
        $("#gst").val('');
        $("#total_premium").val('');
        $("#policyprm").val('');
        $("#policyterrorism").val('');
        if (this.value == "2" || this.value == "3") {
            $('.share_leader').hide();
            $('#gst_div').hide();
            $("#addcoshring").show();
            $("#coshringTbl").show();
        } else {
            $('#gst_div').show();
            $('.share_leader').show();
            $("#addcoshring").hide();
            $("#coshringTbl").hide();
        }
    });

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
                url: aurl + "/sme-policy/export-data",
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
                    console.log(response);
                        if(response.status)
                        {
                            var a = document.createElement("a");
                            a.href = response.file; 
                            a.download = response.name;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            $("#export").prop('disabled', false);
                            $("#export").text('Export SME Policy');
                        }
                        else
                        {
                            $("#export").prop('disabled', false);
                            $("#export").text('Export SME Policy');
                            toaster_message(response.msg,response.icon);
                        }
                    
                      },
                      error: function (ajaxContext) {
                        $("#downloadExcel").show();
                        $("#ExcelDownloadLoader").hide();
                      },
            });

    });
    //end export
});
function gstValueChange(){
    total_premium()
    $('#gst').attr("placeholder", ($('.chkgstvalue').prop('checked'))?"Enter GST":"Enter GST in %");
}
function total_premium() {
    var od=parseInt($('#od').val());
    var gst=parseInt($('#gst').val());
    var stamp_duty = $("#stamp_duty").val() == "" ? 0 : parseInt($("#stamp_duty").val());
    var terrorism=parseInt($('#terrorism').val());
    if(!isNaN(od)){
        gst_value=!isNaN(gst)?gst:0;
        terrorism_value=!isNaN(terrorism)?terrorism:0;
        value = $('.chkgstvalue').prop('checked')?gst_value:(gst_value*(od+terrorism_value)/100);
        $('#total_premium').val(Math.round(od+terrorism_value+value+stamp_duty));
    }else{
        $('#total_premium').val('');
    }

}
function co_sharing_values() {
    var policy_type=$('.policy_type').val();
    var policyprm=parseInt($('#policyprm').val());
    var policyterrorism=parseInt($('#policyterrorism').val());
    var share=parseInt($('#share').val());
    var Bshare=parseInt($('#Bshare').val());
    if(!isNaN(policyprm)&&!isNaN(policyterrorism)&&!isNaN(share)&&policy_type==1){
        $("#od").val(policyprm*share/100);
        $("#terrorism").val(policyterrorism*share/100);
        total_premium()
    }
    else if(!isNaN(policyprm)&&!isNaN(policyterrorism)&&!isNaN(Bshare)&&policy_type==2||policy_type==3){
        $("#Bpolicyprm").val(policyprm*Bshare/100);
        $("#Bpolicyterrorism").val(policyterrorism*Bshare/100);
    }
}


