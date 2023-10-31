/*DataTable*/
if ($("#leave-application_tbl").length == 1) {
$("#leave-application_tbl").DataTable({
    processing: true,
    scrollX: false,
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
    columns: [{ data: "user_name" },
    			{ data: "from_to_date" }, 
              { data: "type" },
              { data: "reason" },
              { data: "status" },
              ],


            
});
}

$('body').on("click", ".status", function(event){
  event.preventDefault();
  	 var id = $(this).data('id');
  	 var status = $(this).data('status');

  	 $.ajax({
                url: aurl +"/leave-application/change-status-leave",
                type: 'POST',
                data: {
                  _token: _token,
                    id: id,
                    rollback:status
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

  });

$("#status").on("change", function() {
    $("#leave-application_tbl").DataTable().ajax.reload();
});
