// Listing fdo Details
if ($("#generated-outward_tbl").length) {
    function loadListing(filterdata){
        $("#generated-outward_tbl").DataTable({
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
                url: aurl + "/generated-outward/listing",
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
    loadListing({});
}
$(document).ready(function() {
    /* Adding And Updating generated outward Data */
    $('body').on('click','.upload',function(event) {
        id=($(this).data('id'))
        event.preventDefault();
        var form = $("#Upload_copy_form"+id)[0];
        var formData = new FormData(form);
        var file = $('#generated_outward_copy'+id).val().trim();
        if(file){
            $.ajax({
                url: aurl + "/generated-outward/"+id,
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    toaster_message("Something Went Wrong! Please Try Again.","error");
                },
            });
        }else{
            toaster_message("Please Upload file First. ","error");
        }
    });
    $('body').on("click", ".filter_generated_outward", function(event){
        event.preventDefault();
        var data = {
            'outward_status':$('#outward_status').val(),
            'branch':$('#branch').val(),
            'company':$('#company').val(),
            'company_branch_name':$('.company_branch_name').val(),
            'branch_imd':$('.branch_imd').val(),
            'outward_no':$('#outward_no').val(),
            'from_date':$('#from_date').val(),
            'to_date':$('#to_date').val(),
        }
        $('#search_criteria').modal('hide');
        $("#generated-outward_tbl").DataTable().destroy();
        loadListing(data);
    });
    $('body').on("click", ".reset_filter", function(event){
        event.preventDefault();
        $("#generated_outward_filter_section").trigger("reset");
        $('.select2').val(0).trigger("change");
        $('#search_criteria').modal('hide');
        $("#generated-outward_tbl").DataTable().destroy();
        loadListing({});
    });
});
