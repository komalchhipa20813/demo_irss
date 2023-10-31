$(document).ready(function() {
    $(".fresh_policy_type").on("change", function() {
        fresh_policy_type()
    });

    if($('.submit_policy').text() == 'Update')
    {
        fresh_policy_type();
        has_policy_check();
        get_product_data();
        get_company_name();
        get_company_branch_name();
        payment_type();
        
        $('#policy_copy').prop('disabled',false)
    }
    $("#policy_number").on(
        "input propertychange",
        event => $('#policy_copy').prop('disabled',event.currentTarget.value == "")
    );
    /* sync policy */
    $('body').on("click", ".sync_policy", function(event){
        var policy = $(this).data("policy");
        event.preventDefault();
        $.ajax({
            url: aurl + "/sync",
            type: "POST",
            data: {policy:policy},
            dataType: "JSON",
            beforeSend: function() {
                $(".sync_policy").prop('disabled', true);
                $('#loader_bg').show();
            },
            success: function(data){
                $('#loader_bg').hide();
                $(".sync_policy").prop('disabled', false);
                toaster_message(data.message,data.icon);
            },
            error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
        });
    });
});
function select_all_policy(obj) {

    if (obj.checked) {
        $(".checkbox").each(function() {
            $(this).prop("checked", "checked");
            $(this).parent().addClass("checked");
        });
    } else {
        $('.checkbox').each(function() {
            this.checked = false;
            $(this).parent().removeClass("checked");
        });
    }
}

function single_unselected(obj)
{
    var i=0;
    var tbl_id= $(obj).parent().parent().parent().parent().attr('id');
    var table = $('#'+tbl_id).dataTable();
    var total_data= table.fnGetData().length;
    var limit=$('select[name="'+ tbl_id+'_length"]').val();
    $(".checkbox:checked").each(function()
    {
      i++;
    });

    if(total_data < limit && i == total_data )
    {
        $('#select_all').each(function() {
            $(this).prop("checked", "checked");
            $(this).parent().removeClass("checked");
        });
    }
    else if(i == limit){
        $('#select_all').each(function() {
            $(this).prop("checked", "checked");
            $(this).parent().removeClass("checked");
        });
    }
    else
    {
        $('#select_all').each(function() {
            this.checked = false;
            $(this).parent().removeClass("checked");
        });
    }
}
    

function fresh_policy_type()
{
    var data=$('input[name="policy_type"]:checked').val();

    if(data == 'fresh'){
        $('#has_policy_div').removeClass("dNone");
        $('#Previews_without_fresh').addClass("dNone");
        $('#pre_policy_number_div').addClass("dNone");
    }else if(data == 'renewal'){
        $("#has_policy").prop("checked", false);
        $('#pre_policy_number_div').removeClass("dNone");
        $('#previwe_policy_no').removeClass("dNone");
        $('#Previews_without_fresh').removeClass("dNone");
        $('#pre_policy_div').addClass("dNone");
        $('#has_policy_div').addClass("dNone");
    }else if(data == 'port renewal'){
        $("#has_policy").prop("checked", false);
        $('#pre_policy_number_div').removeClass("dNone");
        $('#previwe_policy_no').removeClass("dNone");
        $('#Previews_without_fresh').removeClass("dNone");
        $('#pre_policy_div').addClass("dNone");
        $('#has_policy_div').addClass("dNone");
    }else{
        $('#has_policy_div').removeClass("dNone");
        $('#Previews_without_fresh').addClass("dNone");
    }
}
function has_policy_check()
{
    if($(".vehicle").prop("checked") == true){
        $('#pre_policy_div').removeClass("dNone");
    }
    else if($(".vehicle").prop("checked") == false){
        $('#pre_policy_div').addClass("dNone");
    }
}
function addYears(date) {
    var start_date=$('#start_date').val();
    var policy_tenure=$('#policy_tenure').val();
    if(start_date !='' && policy_tenure != '')
    {
        var full_year=(policy_tenure == 'ABOVE15YRS' || policy_tenure == 'SHORT') ? '' : policy_tenure;
        $('.end_date').datepicker({
            format: "dd-mm-yyyy",
            todayHighlight: true,
            autoclose: true,
        });
        if(full_year!=''){
            $('#end_date').prop('readonly',true);
            var start_date = start_date.split("-").reverse().join("-");
            var date=new Date(start_date);
            var year = date.getFullYear();
            var month = date.getMonth();
            var day = date.getDate()-1;
            var c = new Date(year + parseInt(policy_tenure), month, day);
            $('#end_date').datepicker('setDate',c);
        }else{
            $('#end_date').prop('readonly',false);
        }
    }
}

jQuery.validator.addMethod("compare_amount", function(value, element) {
    var payment_type = $("#payment_type").val();
    var total_premium =eval($("#total_premium").val());
    switch (payment_type) {
        case "1":
            var cash_amount = eval($('#cash_amount').val());
            return total_premium == cash_amount ? true : false;
        break;
        case "2":
            var cheque_amount = (typeof $("#cheque_amount").val() === 'undefined' || $("#cheque_amount").val() == '') ? 0 : eval($('#cheque_amount').val());
            var cheque_amount_1 = (typeof $("#cheque_amount_1").val() === 'undefined' || $("#cheque_amount_1").val() == '') ? 0 : eval($('#cheque_amount_1').val());
            return total_premium == cheque_amount + cheque_amount_1 ? true : false;
        break;
        case "3":
            var dd_amount = (typeof $("#dd_amount").val() === 'undefined' || $("#dd_amount").val() == '') ? 0 : eval($('#dd_amount').val());
            var dd_amount_1 = (typeof $("#dd_amount_1").val() === 'undefined' || $("#dd_amount_1").val() == '') ? 0 : eval($('#dd_amount_1').val());
            return(total_premium == dd_amount + dd_amount_1) ? true : false;
        break;
        case "4":
            var online_amount = (typeof $("#online_amount").val() === 'undefined' || $("#online_amount").val() == '') ? 0 : eval($('#online_amount').val());
            var online_amount_1 =(typeof $("#online_amount_1").val() === 'undefined' || $("#online_amount_1").val() == '') ? 0 : eval($('#online_amount_1').val());
            return total_premium == online_amount + online_amount_1 ? true : false;
        break;
        case "5":
            var cash_amount = (typeof $("#cash_amount").val() === 'undefined' || $("#cash_amount").val() == '') ? 0 : eval($('#cash_amount').val());
            var cheque_amount = (typeof $("#cheque_amount").val() === 'undefined' || $("#cheque_amount").val() == '') ? 0 : eval($('#cheque_amount').val());
            return(total_premium == cash_amount + cheque_amount) ? true : false;
        break;
        case "6":
            var cheque_amount = (typeof $("#cheque_amount").val() === 'undefined' || $("#cheque_amount").val() == '') ? 0 : eval($('#cheque_amount').val());
            var dd_amount = (typeof $("#dd_amount").val() === 'undefined' || $("#dd_amount").val() == '') ? 0 : eval($('#dd_amount').val());
            return total_premium == cheque_amount + dd_amount ? true : false;
        break;
        case "7":
            var cash_amount = (typeof $("#cash_amount").val() === 'undefined' || $("#cash_amount").val() == '') ? 0 : eval($('#cash_amount').val());
            var dd_amount = (typeof $("#dd_amount").val() === 'undefined' || $("#dd_amount").val() == '') ? 0 : eval($('#dd_amount').val());
            return total_premium == cash_amount + dd_amount ? true : false;
        break;
        case "8":
            var cash_amount = (typeof $("#cash_amount").val() === 'undefined' || $("#cash_amount").val() == '') ? 0 : eval($('#cash_amount').val());
            var online_amount = (typeof $("#online_amount").val() === 'undefined' || $("#online_amount").val() == '') ? 0 : eval($('#online_amount').val());
            return total_premium == cash_amount + online_amount ? true : false;
        break;
        case "9":
            var online_amount = (typeof $("#online_amount").val() === 'undefined' || $("#online_amount").val() == '') ? 0 : eval($('#online_amount').val());
            var cheque_amount = (typeof $("#cheque_amount").val() === 'undefined' || $("#cheque_amount").val() == '') ? 0 : eval($('#cheque_amount').val());
            return total_premium == online_amount + cheque_amount ? true : false;
        break;
        case "10":
            var online_amount = (typeof $("#online_amount").val() === 'undefined' || $("#online_amount").val() == '') ? 0 : eval($('#online_amount').val());
            var dd_amount = (typeof $("#dd_amount").val() === 'undefined' || $("#dd_amount").val() == '') ? 0 : eval($('#dd_amount').val());
            return total_premium == online_amount + dd_amount ? true : false;
        break;
        default:
          return false;
    }
});

$("body").on("click", ".cancel", function(event) {
    $('#policy_id').val($(this).data("id"));
    $('#policy_cancel_policy_modal').modal('show')
});
if($("#policy_cancel_form").length){
    $("#policy_cancel_form").validate({
        rules: {
            reason: {
                required: true,
            },

        },
        messages: {
            reason: {
                required: "Please Enter Reason",
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
if($("#policy_copy_form").length){
    $("#policy_copy_form").validate({
        rules: {
            policy_copy: {
                required: true,
            },

        },
        messages: {
            policy_copy: {
                required: "Please Upload Copy",
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
$("body").on("click", ".submit_cancel", function(event) {
    event.preventDefault();
    var form = $("#policy_cancel_form")[0];
    var formData = new FormData(form);
    if ($("#policy_cancel_form").valid()) {
        $('#policy_cancel_policy_modal').modal('hide')
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: "btn btn-success",
                cancelButton: "btn btn-danger me-2",
            },
            buttonsStyling: false,
        });
    
        swalWithBootstrapButtons
        .fire({
            title: "Are you sure? You want to cancel it!",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                $.ajax({
                    url: aurl + "/" + currentLocation + "/cancel",
                    type: "POST",
                    dataType: "JSON",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
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
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire(
                    "Cancelled",
                    "Your Data Is Safe",
                    "info"
                );
            }
        });
    }
});
$("body").on("click", ".submit_policy_copy", function(event) {
    event.preventDefault();
    var form = $("#policy_copy_form")[0];
    var formData = new FormData(form);
    console.log(currentLocation.split('-')[0])
    formData.append('module', currentLocation.split('-')[0]);
    console.log(formData)
    if ($("#policy_copy_form").valid()) {
        $.ajax({
            url: aurl + "/policy-copy/upload",
            type: "POST",
            dataType: "JSON",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
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