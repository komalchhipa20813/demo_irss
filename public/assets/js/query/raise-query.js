

$(document).ready(function () {
      // Listing User Details
      if ($("#raise-query_tbl").length) {
        function policy_listing(filterdata) {
            $("#raise-query_tbl").DataTable({
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
                    data: filterdata,
                    url: aurl + "/raise-query/listing",
                },
                columns: [
                    { data: "0" },
                    { data: "1" },
                    { data: "2" },
                    { data: "3" },
                    { data: "4" },
                    { data: "5" },
                    { data: "6" },
                    { data: "7" },
                    { data: "8" },
                    { data: "9" },
                    { data: "10" },
                    { data: "11" },
                ],
                createdRow: function (row, data, dataIndex) {
                    if (data[8] == 3) {
                        $(row).addClass("error");
                    }
                },
            });
        }
        policy_listing({});
    }

    $("body").on("click", "#filter_query", function () {
        var data = {
            agent: $("#agent_code").val(),
            company: $("#company").val(),
            inward_no: $("#inward_no").val(),
            paased_days: $("#days_passed").val(),
            product: $("#product").val(),
            from_date: $("#from_date").val(),
            end_date: $("#end_date").val(),
        };

        $("#search_criteria").modal("hide");
        $("#raise-query_tbl").DataTable().destroy();
        policy_listing(data);
    });
    $("body").on("click", "#reset_filter", function () {
        $("#raise_query_searching_form").trigger("reset");
        $("#company").val("0").trigger("change");
        $("#product").val("0").trigger("change");
        $("#search_criteria").modal("hide");
        $("#raise-query_tbl").DataTable().destroy();
        policy_listing({});
    });

    // update statu with solve query
    $("body").on("click", ".solved-query-status", function () {
        $("#status_update_form").trigger("reset");
        var id = $(this).data("id");
        $('#raise_query_id').val(id);
        $("#status_update").modal("show");
    
    });
    $("#status_update_form").validate({
        rules: {
            close_date: {
                required: true,
            },
        },
        messages: {
            close_date: {
                required: 'Please Select Query Solved Date',
            },
        },
        errorPlacement: function (error, element) {
            if (
                element.parents("div").hasClass("has-feedback") ||
                element.hasClass("select2-hidden-accessible")
            ) {
                error.appendTo(element.parent());
            } else if (
                element.parents("div").hasClass("uploader") ||
                element.hasClass("datepicker") ||
                element.hasClass("business_date") ||
                element.hasClass("manufacturing_year")
            ) {
                error.appendTo(element.parent().parent());
            } else if (element.hasClass("form-check-input")) {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).removeClass("error");
        },
        normalizer: function (value) {
            return $.trim(value);
        },
    });

    /* adding and updating Query data */
    $(".save_status_update").on("click", function (event) {
        event.preventDefault();
        var form = $("#status_update_form")[0];
        var formData = new FormData(form);
        if ($("#status_update_form").valid()) {
            $.ajax({
                url: aurl + "/raise-query/change-status",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data.status)
                    {
                        $("#status_update").modal("hide");
                        toaster_message(data.message, data.icon);
                    }
                    else{
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    }
                },
                error: function (request) {
                    toaster_message(
                        "Something Went Wrong! Please Try Again.",
                        "error"
                    );
                },
            });
        }
    });



    // form validation
    $("#raise_query_form").validate({
        rules: {
            policy_type: {
                required: true,
            },
            policy_id: {
                required: true,
            },
            raised_on: {
                required: true,
            },
            details: {
                required: true,
            },
        },
        messages: {
            policy_type: {
                required: "Please Select Policy",
            },
            policy_id: {
                required: "Please Select Inward No.",            },
            raised_on: {
                required: "Please Select Date",
            },
            details: {
                required: "Please Enter Details",           },
        },
        errorPlacement: function (error, element) {
            if (
                element.parents("div").hasClass("has-feedback") ||
                element.hasClass("select2-hidden-accessible")
            ) {
                error.appendTo(element.parent());
            } else if (
                element.parents("div").hasClass("uploader") ||
                element.hasClass("datepicker") ||
                element.hasClass("business_date") ||
                element.hasClass("manufacturing_year")
            ) {
                error.appendTo(element.parent().parent());
            } else if (element.hasClass("form-check-input")) {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element) {
            $(element).removeClass("error");
        },
        normalizer: function (value) {
            return $.trim(value);
        },
    });


    //get inWARD NUMBER
    $('body').on("change","#policy_type", function() {
        getInwardNo();
    });

     /* adding and updating Query data */
     $(".submit_query").on("click", function (event) {
        event.preventDefault();
        var form = $("#raise_query_form")[0];
        var formData = new FormData(form);
        if ($("#raise_query_form").valid()) {
            $.ajax({
                url: aurl + "/raise-query",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    toaster_message(data.message, data.icon, data.redirect_url);
                },
                error: function (request) {
                    toaster_message(
                        "Something Went Wrong! Please Try Again.",
                        "error"
                    );
                },
            });
        }
    });

    // export data
    $("#export").click(function (event) {
        var data = {
            agent: $("#agent_code").val(),
            company: $("#company").val(),
            inward_no: $("#inward_no").val(),
            paased_days: $("#days_passed").val(),
            product: $("#product").val(),
            from_date: $("#from_date").val(),
            end_date: $("#end_date").val(),
        };

        $.ajax({
            url: aurl + "/raise-query/export-raise-query",
            type: "POST",
            data: {
                data: data,
            },
            cache: false,
            beforeSend: function () {
                $("#export").prop("disabled", true);
                $("#export").text("Excel downloading..");
            },
            success: function (response) {
                if (response.status) {
                    var a = document.createElement("a");
                    a.href = response.file;
                    a.download = response.name;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    $("#export").prop("disabled", false);
                    $("#export").text("Export Raise Query");
                } else {
                    $("#export").prop("disabled", false);
                    $("#export").text("Export Raise Query");
                    toaster_message(response.msg, response.icon);
                }
            },
            error: function (ajaxContext) {
                $("#downloadExcel").show();
                $("#ExcelDownloadLoader").hide();
            },
        });
    });
    // end export

   
    if($('#update_query').val() == 1)
    {
        getInwardNo();
    }
   
});

function getInwardNo()
{
    var policy_type= $('#policy_type').val();
    var raise_query_id= $('#raise_query_id').val();
    $.ajax({
        url: aurl + "/raise-query/get-policy-data",
        type: "POST",
        data: {
            policy_type: policy_type,
            raise_query_id:raise_query_id,
        },
        beforeSend: function(msg){
            var html = "<option> loading... </option>";
                $("#policy_id").html(html);
        },
        dataType: "json",
        success: function(result) {

            if(result.status)
            {
                var html ='';

                console.log(result.exit_policy_id);
                if(result.exit_policy_id != '')
                {
                     html =''; 
                }
                else
                {
                    html ="<option selected disabled value='0'>Please Select</option>";
                }
                $("#policy_id").html(html);
                $.each(result.policy, function(key, value) {
                    var selected_data = (value.id == result.exit_policy_id) ? 'selected' : '';
                    $("#policy_id").append('<option  value="' +value.id +'" '+ selected_data +'>' +value.inward_no +"</option>");
                });
            }
            
        }
    });
}