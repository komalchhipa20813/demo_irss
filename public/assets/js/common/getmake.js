//Product Make Dependent Dropdown With Ajax
$(".product").on("change", function() {
    var product= this.value;
    $.ajax({
        url: aurl + "/make-product/get-make-name",
        type: "POST",
        data: {
            product: product,
        },
        dataType: "json",
        success: function(result) {
            var html = "<option selected disabled value='0'>Please ";
            html += result.make.length == 0 ? "First " : "";
            html += "Select ";
            html += result.make.length == 0 ? "Product" : "";
            html += "</option>";
            $(".make_id").html(html);
            $.each(result.make, function(key, value) {
                $(".make_id").append('<option value="' +value.id +'">' +value.name +"</option>");
            });
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
});

function makeAddress(data){
    
    var html = "";
    $.each(data.data.make, function(key, value) {
        html +='<option value="'+value.id +'">' +value.name +"</option>";
    });
    $(".make_id").html(html);
    $(".make_id option[value=" +data.data.model.make_id +"]").prop("selected", true);
}