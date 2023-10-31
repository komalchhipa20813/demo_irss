$(document).ready(function() {
    $(".policy_type").on("change", function() {
        var idpolicy_type = this.value;
        $.ajax({
            url: aurl + "/product-type/get-product-name",
            type: "POST",
            data: {
                policy_type: idpolicy_type,
            },
            dataType: "json",
            success: function(result) {
                var html =
                    '<option selected disabled class="input-cstm">Please ';
                html += result.product.length == 0 ? "First " : "";
                html += "Select ";
                html += result.product.length == 0 ? "Policy" : "";
                html += "</option>";
                $(".product").html(html);
                $.each(result.product, function(key, value) {
                    $(".product").append(
                        '<option value="' +
                        value.id +
                        '">' +
                        value.name +
                        "</option>"
                    );
                });
            },
        });
    });
});