// Listing fdo Details
if ($("#generate-outward_tbl").length) {
    function loadListing(filterdata) {
        $("#generate-outward_tbl").DataTable({
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
                url: aurl + "/generate-outward/listing",
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
            ],
        });
    }
}

$(document).ready(function () {
    if ($("#generate_outward_form").length) {
        $("#generate_outward_form").validate({
            rules: {
                branch: {
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
                to_date: {
                    required: true,
                },
                from_date: {
                    required: true,
                },
            },
            messages: {
                branch: {
                    required: "Please Select Branch",
                },
                company: {
                    required: "Please Select Company",
                },
                company_branch_name: {
                    required: "Please Select Company Branch",
                },
                branch_imd: {
                    required: "Please Select Company Branch",
                },
                to_date: {
                    required: "Please Enter To Date",
                },
                from_date: {
                    required: "Please Enter From Date",
                },
            },
            errorPlacement: function (error, element) {
                if (
                    element.parents("div").hasClass("has-feedback") ||
                    element.hasClass("select2-hidden-accessible")
                ) {
                    error.appendTo(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function (element) {
                $(element).removeClass("error");
            },
        });
    }

    /* generate outward */
    $(".generate_outward").on("click", function (event) {
        event.preventDefault();
        var data = {};
        data["comman_data"] = {
            branch: $("#branch").val(),
            company: $("#company").val(),
            company_branch_name: $(".company_branch_name").val(),
            branch_imd: $("#branch_imd").val(),
            from_date: $("#from_date").val(),
            to_date: $("#to_date").val(),
        };
        var selected_data_no = new Array();
        $(".checkbox:checked").each(function () {
            var sr = $(this).attr("data-id");
            selected_data_no.push(sr);
        });
        data["inward_no"] = selected_data_no;
        if (
            $("#generate_outward_form").valid() &&
            selected_data_no.length != 0
        ) {
            $.ajax({
                url: aurl + "/generate-outward/pdf",
                type: "POST",
                data: data,
                xhrFields: {
                    responseType: 'blob'
                },
                beforeSend: function() {
                    $(".generate_outward").prop('disabled', true);
                    $(".generate_outward").text('PDF downloading..');
                },
                success: function (data, status, xhr) {
                    $(".generate_outward").prop('disabled', false);
                    $(".generate_outward").text('Generate Outward');
                    const fileName = xhr.getResponseHeader('x-filename');
                    var blob = new Blob([data]);
                    var link = document.createElement("a");
                    link.href = window.URL.createObjectURL(blob);
                    link.download = fileName;
                    link.click();
                    listingPendingPolicy();
                },
                error: function (request) {
                    toaster_message(
                        "Something Went Wrong! Please Try Again.",
                        "error"
                    );
                },
            });
        } else {
            error_toaster_message("Please Select Records", "error", "");
        }
    });

    /* search for outward */
    $("body").on("click", ".get_generate_outward", function (event) {
        event.preventDefault();
        listingPendingPolicy();
    });
    /* reset filter */
    $("body").on("click", ".reset_filter", function (event) {
        event.preventDefault();
        $("#generate_outward_form").trigger("reset");
        $(".select2").val(0).trigger("change");
        $("#generate_outward_div").hide();
    });
});
function listingPendingPolicy(){
        if ($("#generate_outward_form").valid()) {
            var data = {
                branch: $("#branch").val(),
                company: $("#company").val(),
                company_branch_name: $(".company_branch_name").val(),
                branch_imd: $("#branch_imd").val(),
                from_date: $("#from_date").val(),
                to_date: $("#to_date").val(),
            };
            $("#generate-outward_tbl").DataTable().destroy();
            $("#generate_outward_div").show();
            loadListing(data);
        }
}
