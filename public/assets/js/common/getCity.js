//State City Dependent Dropdown With Ajax
$(".state_name").on("change", function() {
    var state_id = this.value;

    $.ajax({
        url: aurl + "/state/get-city-name",
        type: "POST",
        data: {
            state_id: state_id,
        },
        dataType: "json",
        success: function(result) {
            var html = "<option selected disabled value='0'>Please ";
            html +=
                result.city.length == 0 ?
                state_id == 0 ?
                "First Select State" :
                "First Enter City" :
                "Select";
            html += "</option>";
            $(".city_name").html(html);
            $.each(result.city, function(key, value) {
                $(".city_name").append(
                    '<option value="' +
                    value.id +
                    '">' +
                    value.name +
                    "</option>"
                );
            });
        },
        error: function(request) {
            toaster_message("Something Went Wrong! Please Try Again.", "error");
        },
    });
});

function fullAddress(data) {
    stateAddress(data);
    var html = "";
    $.each(data.data.cities, function(key, value) {
        html += '<option value="' + value.id + '">' + value.name + "</option>";
    });
    $(".city_name").html(html);
    $(".city_name option[value=" + data.data.city_id + "]").prop(
        "selected",
        true
    );
}