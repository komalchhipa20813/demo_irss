//Country State Dependent Dropdown With Ajax
$(".country_name").on("change", function() {
    var idcountry = this.value;
    $.ajax({
        url: aurl + "/country/get-state-name",
        type: "POST",
        data: {
            country: idcountry,
        },
        dataType: "json",
        success: function(result) {
            var html = "<option selected disabled value='0'>Please ";
            html += result.state.length == 0 ? (idcountry==0?"First Select Country" : "First Enter State"):"Select";
            html += "</option>";
            $(".state_name").html(html);
            $.each(result.state, function(key, value) {
                $(".state_name").append('<option value="' +value.id +'">' +value.name +"</option>");
            });
        },
        error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
        },
    });
});
function stateAddress(data){
    $(".country_name option[value="+data.data.city.state.country.id+"]").prop("selected", true);
    var html = "";
    $.each(data.data.states, function(key, value) {
        html +='<option value="'+value.id +'">' +value.name +"</option>";
    });
    $(".state_name").html(html);
    $(".state_name option[value=" +data.data.city.state_id +"]").prop("selected", true);
}