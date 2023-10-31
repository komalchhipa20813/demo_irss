$(function () {
    "use strict";
    if ($(".js-example-basic-single").length) {
        $(".js-example-basic-single").select2();
    }
    if ($(".js-example-basic-multiple").length) {
        $(".js-example-basic-multiple").select2();
    }
});

if ($(".select2").length) {
    $(".select2").select2();
}
function select() {
    $(".branch_imd").select2();
    $(".policy_tenure").select2();
}
function modal_dropdown() {
    $(".select_dropdown").select2({
        dropdownParent: $(".select"),
        width: "100%",
    });

    $(".modal_dropdown").select2({
        dropdownParent: $(".select"),
        width: "100%",
    });
}
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr("content");
$("#customer").select2({
    ajax: {
        url: "/customer/get-customers",
        type: "post",
        dataType: "json",
        delay: 250,
        data: function (params) {
            return {
                _token: CSRF_TOKEN,
                search: params.term, // search term
            };
        },
        processResults: function (response) {
            return {
                results: response,
            };
        },
        cache: true,
    },
    // dropdownParent: $(".select"),
    width: "100%",
});
modal_dropdown();
