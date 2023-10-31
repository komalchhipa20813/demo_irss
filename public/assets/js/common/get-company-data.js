//Company Branch Dropdown
$('body').on("change",".company_name", function() {
    var idCompany = this.value;

    $.ajax({
        url: aurl + "/branch-imd/get-company-data",
        type: "POST",
        data: {
            company_id: idCompany,
        },
        dataType: "json",
        success: function(result) {
            if(result.status){
                var html = "<option selected disabled value='0'>Please ";
                html += result.company_branch.length == 0 ? (idCompany==0?"First Select Company" : "First Enter Company Branch"):"Select";
                html += "</option>";
                $(".company_branch_name").html(html);
                $.each(result.company_branch, function(key, value) {
                    $(".company_branch_name").append('<option value="' +value.id +'">' +value.name +"</option>");
                });
            }
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
});
//Company Branch Imd Dropdown
$('body').on("change",".company_branch_name", function() {
    var branch_id = this.value;

    $.ajax({
        url: aurl + "/branch-imd/get-company-data",
        type: "POST",
        data: {
            branch_id: branch_id,
        },
        dataType: "json",
        success: function(result) {
            if(result.status){
                var html = "<option selected disabled value='0'>Please ";
                html += result.branch_imd.length == 0 ? (branch_id==0?"First Select Company" : "First Enter Company Branch"):"Select";
                html += "</option>";
                $(".branch_imd").html(html);
                $.each(result.branch_imd, function(key, value) {
                    $(".branch_imd").append('<option value="' +value.id +'">' +value.name +"</option>");
                });


            }
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
});

function get_company_name()
{
    var idCompany = $('#exit_company').val();
    var exit_branch=$('#exit_company_branch_name').val();

    $.ajax({
        url: aurl + "/branch-imd/get-company-data",
        type: "POST",
        data: {
            company_id: idCompany,
        },
        dataType: "json",
        success: function(result) {
            if(result.status){
                var html = "<option selected disabled value='0'>Please ";
                html += result.company_branch.length == 0 ? (idCompany==0?"First Select Company" : "First Enter Company Branch"):"Select";
                html += "</option>";
                $(".company_branch_name").html(html);
                $.each(result.company_branch, function(key, value) {
                    $(".company_branch_name").append('<option value="' +value.id +'">' +value.name +"</option>");
                });
                if(exit_branch)
                $(".company_branch_name option[value=" +exit_branch+"]").prop("selected", true);
            }
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
}

function get_company_branch_name()
{
    var branch_id = $('#exit_company_branch_name').val();
    var exit_branch_imd=$('#exit_branch_imd').val();
    ajax_get_company_branch_name(branch_id,exit_branch_imd)
}
function ajax_get_company_branch_name(branch_id,exit_branch_imd){
    $.ajax({
        url: aurl + "/branch-imd/get-company-data",
        type: "POST",
        data: {
            branch_id: branch_id,
        },
        dataType: "json",
        success: function(result) {
            if(result.status){
                var html = "<option selected disabled value='0'>Please ";
                html += result.branch_imd ? (result.branch_imd.length==0?"First Enter Branch IMD" : "Select"):"First Select Company Branch";
                html += "</option>";
                $(".branch_imd").html(html);
                $.each(result.branch_imd, function(key, value) {
                    $(".branch_imd").append('<option value="' +value.id +'">' +value.name +"</option>");
                });
                if(exit_branch_imd)
                $(".branch_imd option[value=" +exit_branch_imd+"]").prop("selected", true);


            }
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
}