/*DataTable*/
if ($("#public-holiday_tbl").length == 1) {
    $("#public-holiday_tbl").DataTable({
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
            url: aurl + "/public-holiday/listing",
        },
        columns: [
            { data: "no" },
            { data: "title" },
            { data: "date" },
            { data: "holiday_type" },
            { data: "action" },
        ],
    });
}

$(document).ready(function() {
    /* Validation Of Country Form */
    $("#holiday_form").validate({
        rules: {
            title: {
                required: true,
                holiday_check: true,
            },
            date: {
                required: true,
            },
            holiday_type: {
                required: true,
            },
        },

        messages: {
            title: {
                required: "Please Enter Holiday Title",
            },
            date: {
                required: "Please Select Holiday Date",
            },
            holiday_type: {
                required: "Please Select Holiday Type",
            },
        },

        errorPlacement: function(error, element) {
            if (
                element.parents("div").hasClass("has-feedback") ||
                element.hasClass("select2-hidden-accessible")
            ) {
                error.appendTo(element.parent());
            } else if (
                element.parent().hasClass("uploader") ||
                element.parents().hasClass("input-group")
            ) {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function(element) {
            $(element).removeClass("error");
        },
    });

    $.validator.addMethod(
        "holiday_check",
        function(value) {
            var x = 0;
            var id = $("#id").val();
            var x = $.ajax({
                url: aurl + "/public-holiday/holiday-check",
                type: "POST",
                async: false,
                data: {
                    title: value,
                    id: id,
                },
            }).responseText;
            if (x != 0) {
                return false;
            } else return true;
        },
        "Please Enter Another Holiday"
    );

    /* Add Country Modal */
    $("body").on("click", ".add_holiday", function() {
        $("#holiday_form").validate().resetForm();
        $("#holiday_form").trigger("reset");
        $("#holiday_modal").modal("show");
        $(".holiday_type").val("0").trigger("change");

        $(".id").val($(this).data("id"));
        $(".editholiday").html(
            '<input class="switcherye" type="checkbox" id="" name="status" checked>'
        );
        var elems = Array.prototype.slice.call(
            document.querySelectorAll(".switcherye")
        );
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
        $("#title_holiday_modal").text("Add Holiday");
        $(".submit_holiday").text("Add Holiday");
    });

    /* Adding And Updating Country Modal */
    $(".submit_holiday").click(function(event) {
        event.preventDefault();
        var form = $("#holiday_form")[0];
        var formData = new FormData(form);
        if ($("#holiday_form").valid()) {
            $.ajax({
                url: aurl + "/public-holiday",
                type: "POST",
                dataType: "JSON",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    $("#holiday_modal").modal("hide");
                    toaster_message(data.message, data.icon);
                },
                error: function(request) {
                    $("#country_name_error").html(
                        request.responseJSON.errors.country_name
                    );
                },
            });
        }
    });

    /* Display Update Country Modal*/
    $("body").on("click", ".edit_holiday", function(event) {
        var id = $(this).data("id");
        $(".id").val(id);
        event.preventDefault();
        $.ajax({
            url: aurl + "/public-holiday/" + id + "/edit",
            type: "GET",
            data: { id: id },
            dataType: "JSON",
            success: function(data) {
                if (data.status) {
                    $("#holiday_form").trigger("reset");
                    $("#holiday_form").validate().resetForm();
                    $("#holiday_modal").modal("show");
                    $("#title_holiday_modal").text("Update Holiday");
                    $(".submit_holiday").text("Update Holiday");
                    $("#title").val(data.data.holiday.title);
                    $("#date").val(data.data.holiday.date);
                    $("#holiday_type").select2(
                        "val",
                        data.data.holiday.holiday_type
                    );
                    var checked =
                        data.data.holiday.status == 1 ? "checked" : "";
                    $(".editholiday").html(
                        '<input class="switcherye" type="checkbox" ' +
                        checked +
                        ' id="editstatus" name="status" >'
                    );

                    var elems = Array.prototype.slice.call(
                        document.querySelectorAll(".switcherye")
                    );

                    elems.forEach(function(html) {
                        var switchery = new Switchery(html);
                    });
                } else {
                    toaster_message(data.message, data.icon);
                }
            },
        });
    });

    $(".holiday-pdf").on("click", function(event){
        event.preventDefault();
        var form = '';
                $.ajax({
                    url: aurl + "/public-holiday/generate-pdf",
                    type: 'POST',
                    data:{
                        form:form

                    },
                     xhrFields: {
                    responseType: 'blob'
                    },
                    beforeSend: function() {
                    $(".holiday-pdf").prop('disabled', true);
                    $(".holiday-pdf").text('PDF downloading..');
                     },
                    success: function(response) {
                       
                            var blob = new Blob([response]);
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "HolidayDetail.pdf";
                            link.click();
                            $(".holiday-pdf").prop('disabled', false);
                            $(".holiday-pdf").text('Download PDF');
                          
                        },
                        error: function(request) {
                            $(".holiday-pdf").prop('disabled', false);
                            $(".holiday-pdf").text('Download PDF');
                            toaster_message('Something Went Wrong! Please Try Again.', 'error');
                        },
                });
    });
});