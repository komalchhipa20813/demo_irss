$(document).ready(function () {
    // Listing User Details
    if ($("#solved-query_tbl").length) {
      function policy_listing(filterdata) {
          $("#solved-query_tbl").DataTable({
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
                  url: aurl + "/raise-query/solved-listing",
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
                  { data: "12" },
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
      $("#solved-query_tbl").DataTable().destroy();
      policy_listing(data);
  });
  $("body").on("click", "#reset_filter", function () {
      $("#raise_query_searching_form").trigger("reset");
      $("#company").val("0").trigger("change");
      $("#product").val("0").trigger("change");
      $("#search_criteria").modal("hide");
      $("#solved-query_tbl").DataTable().destroy();
      policy_listing({});
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
        url: aurl + "/raise-query/export-solved-query",
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
                $("#export").text("Export Solved Query");
            } else {
                $("#export").prop("disabled", false);
                $("#export").text("Export Solved Query");
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

});