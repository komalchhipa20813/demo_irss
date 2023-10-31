//Product Make Dependent Dropdown With Ajax
$(".model_id").on("change", function() {
    var model_id= this.value;
    $.ajax({
        url: aurl + "/product-variant/get-variant-name",
        type: "POST",
        data: {
            model_id: model_id,
        },
        dataType: "json",
        success: function(result) {
            var html = "<option selected disabled value='0'>Please ";
            html += result.variant.length == 0 ? "First " : "";
            html += "Select ";
            html += result.variant.length == 0 ? "Product model" : "";
            html += "</option>";
            $(".variant_id").html(html);
            $.each(result.variant, function(key, value) {
                $(".variant_id").append('<option value="' +value.id +'">' +value.name +"</option>");
            });
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
});

function get_variant_data()
{
    var model_id= $('#exit_model_id').val();
    var variant_id=$('#exit_variant_id').val();
    $.ajax({
        url: aurl + "/product-variant/get-variant-name",
        type: "POST",
        data: {
            model_id: model_id,
        },
        dataType: "json",
        success: function(result) {
            var html = "<option selected disabled value='0'>Please ";
            html += result.variant.length == 0 ? "First " : "";
            html += "Select ";
            html += result.variant.length == 0 ? "Product model" : "";
            html += "</option>";
            $(".variant_id").html(html);
            $.each(result.variant, function(key, value) {
                $(".variant_id").append('<option value="' +value.id +'">' +value.name +"</option>");
            });
             $(".variant_id option[value=" +variant_id+"]").prop("selected", true);
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
}

function modelAddress(data){
    $(".product option[value="+data.data.variant.model.make.product_id+"]").prop("selected", true);
    var html = "";
    $.each(data.data.model, function(key, value) {
        html +='<option value="'+value.id +'">' +value.name +"</option>";
    });
    $(".model_id").html(html);
    $(".model_id option[value=" +data.data.variant.model_id +"]").prop("selected", true);
    // append html of make select
    var  html_make="";
    $.each(data.data.make, function(key, value) {
        html_make +='<option value="'+value.id +'">' +value.name +"</option>";
    });
    $(".make_id").html(html_make);
    $(".make_id option[value=" +data.data.variant.model.make_id +"]").prop("selected", true);
}