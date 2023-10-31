//Product Make Dependent Dropdown With Ajax
$(".make_id").on("change", function() {
    var make_id= this.value;
    $.ajax({
        url: aurl + "/product-model/get-model-name",
        type: "POST",
        data: {
            make_id: make_id,
        },
        dataType: "json",
        success: function(result) {
            var html = "<option selected disabled value='0'>Please ";
            html += result.model.length == 0 ? "First " : "";
            html += "Select ";
            html += result.model.length == 0 ? "Product Make" : "";
            html += "</option>";
            $(".model_id").html(html);
            $.each(result.model, function(key, value) {
                $(".model_id").append('<option value="' +value.id +'">' +value.name +"</option>");
            });
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
});

function get_model_data()
{
    var make_id= $('#exit_make_id').val();
    var model_id=$('#exit_model_id').val();
    $.ajax({
        url: aurl + "/product-model/get-model-name",
        type: "POST",
        data: {
            make_id: make_id,
        },
        dataType: "json",
        success: function(result) {
            var html = "<option selected disabled value='0'>Please ";
            html += result.model.length == 0 ? "First " : "";
            html += "Select ";
            html += result.model.length == 0 ? "Product Make" : "";
            html += "</option>";
            $(".model_id").html(html);
            $.each(result.model, function(key, value) {
                $(".model_id").append('<option value="' +value.id +'">' +value.name +"</option>");
            });
             $(".model_id option[value=" +model_id+"]").prop("selected", true);
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