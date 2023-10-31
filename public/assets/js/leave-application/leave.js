
/*DataTable*/
if ($("#leave-application_tbl").length == 1) {
$("#leave-application_tbl").DataTable({
    processing: true,
    scrollX: false,
    // "sScrollX": "100%",
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
        url: aurl + "/leave-application/listing",
        "data": function(d){
         d.status = $('#status').val();
        },
    },
    columns: [{ data: "from_to_date" }, 
              { data: "type" },
              { data: "reason" },
              // { data: "child_status" },
              // { data: "parent_status" },
              { data: "editORrollback" },
              // { data: "rollback" }
              ],


            
});
}


$(document).ready(function() {
    /* Validation Of City Form */
    $("#leave_form").validate({
        rules: {
            leave_type: {
                required: true,
            },
            from_date: {
                required: true,
                fromdate_check:true,
            },
            to_date: {
                required: true,
                todate_check:true,
            },
            leave_type_day: {
                required: true,
            },
            leave_reason: {
                required: true,
            },
        },
        messages: {
            leave_type: {
                required: "Please Select Leave",
            },
            from_date: {
                required: "Please Select From Date",
            },
            to_date: {
                required: "Please Select To Date",
            },
            leave_type_day: {
                required: "Please Select Leave Type Day",
            },
            leave_reason: {
                required: "Please Enter Leave Reason",
            },

        },
        errorPlacement: function(error, element) {
            if (element.parents("div").hasClass("has-feedback") || element.hasClass("select2-hidden-accessible")) {
                error.appendTo(element.parent());
            } else if (
                element.parent().hasClass("uploader") ||
                element.parents().hasClass("input-group")
            ) {
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
        "fromdate_check",
        function(value) {
            var x = true;
            var end_date= $("#to_date").val();
            if(end_date != '')
		   {
		      if(new Date(value) > new Date(end_date))
		      {
		        x=false;
		      }
		   }

		   return x;
            
        },
        "Please From date choose less than To Date"
    );

    $.validator.addMethod(
        "todate_check",
        function(value) {
            var x = true;
            var start_date= $("#from_date").val();
            if(start_date != '')
		   {
		      if(new Date(start_date) > new Date(value))
		    {
		       x=false;
		    }
		   }

		   return x;
            
        },
        "Please To date choose greater than From Date"
    );

    /* Adding And Updating City Modal */
    $(".submit_leave").click(function(event) {
        event.preventDefault();
        var form = $("#leave_form")[0];
        var formData = new FormData(form);
        if ($("#leave_form").valid()) {
            $.ajax({
                url: aurl + "/leave-application",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#leave_form").validate().resetForm();
                    $("#leave_form").trigger("reset");
                    $("#leave_type").val('0').trigger("change");
                    $("#leave_reason").text('');
                    $("#leave_type_day").val('0').trigger("change");
                    $("#work_handover_user_id").val('0').trigger("change");

                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message('Something Went Wrong! Please Try Again.', 'error');
                },
            });
        }
    });

     $(".clear_btn").click(function(event) {
            $("#leave_form").validate().resetForm();
     	      $("#leave_type").val('0').trigger("change");
		       $("#from_date").val('');
		  $("#to_date").val('');
		  $("#leave_type_day").val('0').trigger("change");
		  $("#work_handover_user_id").val('0').trigger("change");
		   $("#leave_reason").val('');

     });

        /* Display Update City Modal*/
    $("body").on("click", ".edit_leave", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/leave-application/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#leave_form").trigger("reset");
                    $("#leave_form").validate().resetForm();
                    $(".leave_type option[value='"+data.data.leave.leave_type+"']").prop("selected", true);
                    $(".leave_type_day option[value='"+data.data.leave.leave_type_day+"']").prop("selected", true);

                    if(data.data.leave.work_handover_user_id!='' && data.data.leave.work_handover_user_id != null)
                    {
                        $(".work_handover option[value='"+data.data.leave.work_handover_user_id+"']").prop("selected", true);
                    }
                    else
                    {
                        $(".work_handover option[value='0']").prop("selected", true);
                    }

                    $('#from_date').val(data.data.from_date);
                    $('#to_date').val(data.data.to_date);
                    $('#leave_reason').text(data.data.leave.leave_reason);
                  // modal_dropdown();

                } else {
                    toaster_message(data.message, data.icon);
                }
            },
            error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
        });
    });

});

$("#status").on("change", function() {
    $("#leave-application_tbl").DataTable().ajax.reload();
});

function rollback_status(obj)
{
            var rollback = 4;
            var id=obj.id;

            $.ajax({
                url: aurl +"/leave-application/change-status-leave",
                type: 'POST',
                data: {
                  _token: _token,
                    id: id,
                    rollback:rollback
                },

                 dataType: "JSON",
                success: function(data)
                {
                    if (data.status)
                    {
                        toaster_message(data.message,data.icon);
                    }
                }
            });
}

