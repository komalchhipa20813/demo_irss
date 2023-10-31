$("#product").on("change", function() {
    var product_id= this.value;
    $.ajax({
        url: aurl + "/product/get-data",
        type: "POST",
        data: {
            product_id: product_id,
        },
        dataType: "json",
        success: function(result) {
            if(result.status){

                var html = "<option selected disabled value='0'> ";
                html += result.company.length == 0 ? "Please First Enter Company" : "Please Select";
                html += "</option>";
                $(".company").html(html);
                $.each(result.company, function(key, value) {
                    $(".company").append('<option value="' +value.id +'">' +value.name +"</option>");
                });
                var html = "<option selected disabled value='0'> ";
                html += result.sub_product.length == 0 ? "Please First Enter Sub Product" : "Please Select";
                html += "</option>";
                $("#sub_product").html(html);
                $.each(result.sub_product, function(key, value) {
                    $("#sub_product").append('<option value="' +value.id +'">' +value.name +"</option>");
                });
                if(result.product_make){
                    var html = "<option selected disabled value='0'> ";
                    html += result.product_make.length == 0 ? "Please First Enter Make" : "Please Select";
                    html += "</option>";
                    $(".make_id").html(html);
                    $.each(result.product_make, function(key, value) {
                        $(".make_id").append('<option value="' +value.id +'">' +value.name +"</option>");
                    });

                    var html = "<option selected disabled value='0'> ";
                    html += result.product_type.length == 0 ? "Please First Enter Product Type" : "Please Select";
                    html += "</option>";
                    $(".product_type").html(html);
                    $.each(result.product_type, function(key, value) {
                        $(".product_type").append('<option value="' +value.id +'">' +value.type +"</option>");
                    });
                }
            }
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
});

function get_product_data()
{
    var product_id= $('#product').val();
    var company_id=$('#exit_company').val();
    var sub_product=$('#exit_sub_product').val();
    var product_type_id=$('#exit_product_type').val();
    var pre_company_id=$('#exit_pre_company').val();
    var has_pre_company_id=$('#has_exit_pre_company').val();
    var renewal_pre_company_id=$('#exit_renewal_pre_company').val();
    var make_id=$('#exit_make_id').val();
    $.ajax({
        url: aurl + "/product/get-data",
        type: "POST",
        data: {
            product_id: product_id,
        },
        dataType: "json",
        success: function(result) {
            if(result.status){
                var html = "<option selected disabled value='0'> ";
                html += result.company.length == 0 ? "Please First Enter Company" : "Please Select";
                html += "</option>";
                $(".company").html(html);
                $.each(result.company, function(key, value) {
                    $(".company").append('<option value="' +value.id +'">' +value.name +"</option>");
                });
                
                $(".company option[value=" +company_id+"]").prop("selected", true);
                if(pre_company_id){
                    $(".pre_company option[value=" +pre_company_id+"]").prop("selected", true);
                }
                
                if($('input[name="policy_type"]:checked').val() == 'fresh' && has_pre_company_id)
                {
                    $(".has_policy_pre_company option[value=" +has_pre_company_id+"]").prop("selected", true);
                }
                
                if(renewal_pre_company_id){
                    $("#previouspolicycompany option[value=" +renewal_pre_company_id+"]").prop("selected", true);
                }
                var html = "<option selected disabled value='0'> ";
                html += result.sub_product.length == 0 ? "Please First Enter Sub Product" : "Please Select";
                html += "</option>";
                $("#sub_product").html(html);
                $.each(result.sub_product, function(key, value) {
                    $("#sub_product").append('<option value="' +value.id +'">' +value.name +"</option>");
                });
                if(sub_product != ''){
                    $("#sub_product option[value=" +sub_product+"]").prop("selected", true);
                }
                if(result.product_make){
                    var html = "<option selected disabled value='0'> ";
                    html += result.product_make.length == 0 ? "Please First Enter Make" : "Please Select";
                    html += "</option>";
                    $(".make_id").html(html);
                    $.each(result.product_make, function(key, value) {
                        $(".make_id").append('<option value="' +value.id +'">' +value.name +"</option>");
                    });

                     $(".make_id option[value=" +make_id+"]").prop("selected", true);

                    var html = "<option selected disabled value='0'> ";
                    html += result.product_type.length == 0 ? "Please First Enter Product Type" : "Please Select";
                    html += "</option>";
                    $(".product_type").html(html);
                    $.each(result.product_type, function(key, value) {
                        $(".product_type").append('<option value="' +value.id +'">' +value.type +"</option>");
                    });
                    if(product_type_id)
                    $(".product_type option[value=" +product_type_id+"]").prop("selected", true);
                    if(currentLocation=='motor-policy')
                    sub_product_change($('#sub_product').find("option:selected").text());
                }
            }
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
}
