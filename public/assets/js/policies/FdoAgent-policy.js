$(document).ready(function () {
    // Listing User Details
    if ($("#motor-policy_tbl").length) {
        function policy_listing(filterdata) {
            $("#motor-policy_tbl").DataTable({
                processing: true,
                serverSide: true,
                searching: true,
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
                    url: aurl + "/fdo-agent/motor-policy/listing",
                },
                columns: [
                    { data: "5" },
                    { data: "0" },
                    { data: "1" },
                    { data: "2" },
                    { data: "3" },
                    { data: "6" },
                    { data: "7" },
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
    // Listing health policy Details
    if($("#health-policy_tbl").length){
        function policy_listing($data){
            $("#health-policy_tbl").DataTable({
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
                    url: aurl + "/fdo-agent/health-policy/listing",
                },
                columns: [
                    { data: '4' },
                    { data: '0' },
                    { data: '1' },
                    { data: '2' },
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
                    url: aurl + "/fdo-agent/sme-policy/listing",
                },
                columns: [
                    { data: '4' },
                    { data: '0' },
                    { data: '1' },
                    { data: '2' },
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
});