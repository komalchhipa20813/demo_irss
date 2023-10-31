$(document).ready(function () {
    // Listing User Details
    if ($("#cancel-policy_tbl").length) {
        function policy_listing(filterdata) {
            $("#cancel-policy_tbl").DataTable({
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
                    url: aurl + "/cancel-policy/health",
                },
                columns: [
                    { data: "0" },
                    { data: "1" },
                    { data: "2" },
                    { data: "3" },
                    { data: "4" },
                    { data: "5" },
                ],
            });
        }
        policy_listing({});
    }
    $("body").on("click", "#filter_health_policy", function () {
        console.log('hii')
        var data = {
            agent: $("#agent").val(),
            name: $("#name").val(),
            branch: $("#branch").val(),
            company: $("#company").val(),
            cheque_no: $("#cheque_no").val(),
            inward_no: $("#inward_no").val(),
            policy_no: $("#policy_no").val(),
            engine_no: $("#engine_no").val(),
            chasis_no: $("#chasis_no").val(),
            registration_no: $("#registration_no").val(),
            product: $("#product").val(),
            policy_start_date: $("#policy_start_date").val(),
            policy_end_date: $("#policy_end_date").val(),
            from_date: $("#from_date").val(),
            end_date: $("#end_date").val(),
        };
        $("#search_criteria").modal("hide");
        $("#cancel-policy_tbl").DataTable().destroy();
        policy_listing(data);
    });
    $("body").on("click", "#reset_filter", function () {
        $("#health_policy_searching_form").trigger("reset");
        $("#agent").val("0").trigger("change");
        $("#branch").val("0").trigger("change");
        $("#company").val("0").trigger("change");
        $("#product").val("0").trigger("change");
        $("#search_criteria").modal("hide");
        $("#cancel-policy_tbl").DataTable().destroy();
        policy_listing({});
    });

    // export data
    $("#export").click(function (event) {
        var data = {
            agent: $("#agent").val(),
            name: $("#name").val(),
            branch: $("#branch").val(),
            company: $("#company").val(),
            cheque_no: $("#cheque_no").val(),
            inward_no: $("#inward_no").val(),
            policy_no: $("#policy_no").val(),
            engine_no: $("#engine_no").val(),
            chasis_no: $("#chasis_no").val(),
            registration_no: $("#registration_no").val(),
            product: $("#product").val(),
            policy_start_date: $("#policy_start_date").val(),
            policy_end_date: $("#policy_end_date").val(),
            from_date: $("#from_date").val(),
            end_date: $("#end_date").val(),
        };

        $.ajax({
            url: aurl + "/cancel-policy/export-health-data",
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
                    $("#export").text("Export Motor Policy");
                } else {
                    $("#export").prop("disabled", false);
                    $("#export").text("Export Motor Policy");
                    toaster_message(response.msg, response.icon);
                }
            },
            error: function (ajaxContext) {
                $("#export").prop("disabled", false);
                $("#export").text("Export Motor Policy");
                $("#downloadExcel").show();
                $("#ExcelDownloadLoader").hide();
            },
        });
    });
});
