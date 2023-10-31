/* payment change event */
$("body").on("change", "#payment_type", function() {
    payment_type();
});

function payment_type() {
    var payment_type = parseInt($("#payment_type").val());
    var policy_id = $(".policy_id").val();
    var update_policy = $("#update_policy").val();
    var exit_payment_type = $("#exit_payment_type").val() != "" ? $("#exit_payment_type").val() : 0;

    $(".payment_details").html("");

    switch (payment_type) {
        case 1:
            if (update_policy == 1 && exit_payment_type == 1) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",   
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        $.each(result.data.payments, function(key, value) {
                            $(".payment_details").html(
                                getDiv(
                                    "Cash Amount",
                                    "cash_amount",
                                    value.amount
                                )
                            );
                        });
                    },
                    error: function(request) {

                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getDiv("Cash Amount", "cash_amount")
                );
            }
            break;
        case 2:
            if (update_policy == 1 && exit_payment_type == 2) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getBank("cheque_bank") +
                                        getDiv(
                                            "Account Number",
                                            "cheque_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "Cheque Number",
                                            "cheque_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Cheque Date",
                                            "cheque_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Cheque Amount",
                                            "cheque_amount",
                                            value.amount
                                        ) +
                                        addButton("Add Cheque", "check", 1)
                                    );
                                    $(
                                        ".cheque_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        '<div class="duplicate row">' +
                                        getBank("cheque_bank_1") +
                                        getDiv(
                                            "Account Number",
                                            "cheque_account_number_1",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "Cheque Number",
                                            "cheque_number_1",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Cheque Date",
                                            "cheque_date_1",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Cheque Amount",
                                            "cheque_amount_1",
                                            value.amount
                                        ) +
                                        addButton("Remove", "remove", 1) +
                                        "</div>"
                                    );
                                    $(
                                        ".cheque_bank_1 option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                    $(".check").attr("disabled", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getBank("cheque_bank") +
                    getDiv("Account Number", "cheque_account_number") +
                    getDiv("Cheque Number", "cheque_number") +
                    getDatePicker("Cheque Date", "cheque_date") +
                    getDiv("Cheque Amount", "cheque_amount") +
                    addButton("Add Cheque", "check", 1)
                );
            }

            update_js();
            break;
        case 3:
            if (update_policy == 1 && exit_payment_type == 3) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getBank("dd_bank") +
                                        getDiv(
                                            "Account Number",
                                            "dd_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "DD Number",
                                            "dd_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "DD Date",
                                            "dd_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "DD Amount",
                                            "dd_amount",
                                            value.amount
                                        ) +
                                        addButton("Add DD", "check", 2)
                                    );
                                    $(
                                        ".dd_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        '<div class="duplicate row">' +
                                        getBank("dd_bank_1") +
                                        getDiv(
                                            "Account Number",
                                            "dd_account_number_1",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "DD Number",
                                            "dd_number_1",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "DD Date",
                                            "dd_date_1",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "DD Amount",
                                            "dd_amount_1",
                                            value.amount
                                        ) +
                                        addButton("Remove", "remove", 1) +
                                        "</div>"
                                    );
                                    $(
                                        ".dd_bank_1 option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                    $(".check").attr("disabled", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getBank("dd_bank") +
                    getDiv("Account Number", "dd_account_number") +
                    getDiv("DD Number", "dd_number") +
                    getDatePicker("DD Date", "dd_date") +
                    getDiv("DD Amount", "dd_amount") +
                    addButton("Add DD", "check", 2)
                );
            }
            update_js();
            break;
        case 4:
            if (update_policy == 1 && exit_payment_type == 4) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getBank("transaction_bank") +
                                        getDiv(
                                            "Transaction Number",
                                            "transaction_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Transaction Date",
                                            "transaction_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Online Amount",
                                            "online_amount",
                                            value.amount
                                        ) +
                                        addButton("Add Cheque", "check", 3)
                                    );
                                    $(
                                        ".transaction_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        '<div class="duplicate row">' +
                                        getBank("transaction_bank_1") +
                                        getDiv(
                                            "Transaction Number",
                                            "transaction_number_1",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Transaction Date",
                                            "transaction_date_1",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Online Amount",
                                            "online_amount_1",
                                            value.amount
                                        ) +
                                        addButton("Remove", "remove", 1) +
                                        "</div>"
                                    );
                                    $(
                                        ".transaction_bank_1 option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                    $(".check").attr("disabled", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getBank("transaction_bank") +
                    getDiv("Transaction Number", "transaction_number") +
                    getDatePicker("Transaction Date", "transaction_date") +
                    getDiv("Online Amount", "online_amount") +
                    addButton("Add Cheque", "check", 3)
                );
            }

            update_js();
            break;
        case 5:
            if (update_policy == 1 && exit_payment_type == 5) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getDiv(
                                            "Cash Amount",
                                            "cash_amount",
                                            value.amount
                                        )
                                    );
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        getBank("cheque_bank") +
                                        getDiv(
                                            "Account Number",
                                            "cheque_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "Cheque Number",
                                            "cheque_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Cheque Date",
                                            "cheque_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Cheque Amount",
                                            "cheque_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".cheque_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getDiv("Cash Amount", "cash_amount") +
                    "</br>" +
                    getBank("cheque_bank") +
                    getDiv("Account Number", "cheque_account_number") +
                    getDiv("Cheque Number", "cheque_number") +
                    getDatePicker("Cheque Date", "cheque_date") +
                    getDiv("Cheque Amount", "cheque_amount")
                );
            }

            update_js();
            break;
        case 6:
            if (update_policy == 1 && exit_payment_type == 6) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getBank("cheque_bank") +
                                        getDiv(
                                            "Account Number",
                                            "cheque_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "Cheque Number",
                                            "cheque_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Cheque Date",
                                            "cheque_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Cheque Amount",
                                            "cheque_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".cheque_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }

                                if (key == 1) {
                                    $(".payment_details").append(
                                        getBank("dd_bank") +
                                        getDiv(
                                            "Account Number",
                                            "dd_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "DD Number",
                                            "dd_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "DD Date",
                                            "dd_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "DD Amount",
                                            "dd_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".dd_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getBank("cheque_bank") +
                    getDiv("Account Number", "cheque_account_number") +
                    getDiv("Cheque Number", "cheque_number") +
                    getDatePicker("Cheque Date", "cheque_date") +
                    getDiv("Cheque Amount", "cheque_amount") +
                    getBank("dd_bank") +
                    getDiv("Account Number", "dd_account_number") +
                    getDiv("DD Number", "dd_number") +
                    getDatePicker("DD Date", "dd_date") +
                    getDiv("DD Amount", "dd_amount")
                );
            }

            update_js();
            break;
        case 7:
            if (update_policy == 1 && exit_payment_type == 7) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getDiv(
                                            "Cash Amount",
                                            "cash_amount",
                                            value.amount
                                        )
                                    );
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        getBank("dd_bank") +
                                        getDiv(
                                            "Account Number",
                                            "dd_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "DD Number",
                                            "dd_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "DD Date",
                                            "dd_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "DD Amount",
                                            "dd_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".dd_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getDiv("Cash Amount", "cash_amount") +
                    getBank("dd_bank") +
                    getDiv("Account Number", "dd_account_number") +
                    getDiv("DD Number", "dd_number") +
                    getDatePicker("DD Date", "dd_date") +
                    getDiv("DD Amount", "dd_amount")
                );
            }
            update_js();
            break;
        case 8:
            if (update_policy == 1 && exit_payment_type == 8) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getDiv(
                                            "Cash Amount",
                                            "cash_amount",
                                            value.amount
                                        )
                                    );
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        getBank("transaction_bank") +
                                        getDiv(
                                            "Transaction Number",
                                            "transaction_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Transaction Date",
                                            "transaction_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Online Amount",
                                            "online_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".transaction_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getDiv("Cash Amount", "cash_amount") +
                    getBank("transaction_bank") +
                    getDiv("Transaction Number", "transaction_number") +
                    getDatePicker("Transaction Date", "transaction_date") +
                    getDiv("Online Amount", "online_amount")
                );
            }

            update_js();
            break;
        case 9:
            if (update_policy == 1 && exit_payment_type == 9) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getBank("transaction_bank") +
                                        getDiv(
                                            "Transaction Number",
                                            "transaction_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Transaction Date",
                                            "transaction_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Online Amount",
                                            "online_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".transaction_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        getBank("cheque_bank") +
                                        getDiv(
                                            "Account Number",
                                            "cheque_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "Cheque Number",
                                            "cheque_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Cheque Date",
                                            "cheque_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Cheque Amount",
                                            "cheque_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".cheque_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getBank("transaction_bank") +
                    getDiv("Transaction Number", "transaction_number") +
                    getDatePicker("Transaction Date", "transaction_date") +
                    getDiv("Online Amount", "online_amount") +
                    getBank("cheque_bank") +
                    getDiv("Account Number", "cheque_account_number") +
                    getDiv("Cheque Number", "cheque_number") +
                    getDatePicker("Cheque Date", "cheque_date") +
                    getDiv("Cheque Amount", "cheque_amount")
                );
            }

            update_js();
            break;
        case 10:
            if (update_policy == 1 && exit_payment_type == 10) {
                $.ajax({
                    async: false,
                    url: aurl + "/" + currentLocation + "/get-payment-data",
                    type: "POST",
                    dataType: "json",
                    data: { policy_id: policy_id },
                    success: function(result) {
                        if (result.status) {
                            $.each(result.data.payments, function(key, value) {
                                if (key == 0) {
                                    $(".payment_details").html(
                                        getBank("transaction_bank") +
                                        getDiv(
                                            "Transaction Number",
                                            "transaction_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "Transaction Date",
                                            "transaction_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "Online Amount",
                                            "online_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".transaction_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                                if (key == 1) {
                                    $(".payment_details").append(
                                        getBank("dd_bank") +
                                        getDiv(
                                            "Account Number",
                                            "dd_account_number",
                                            value.account_number
                                        ) +
                                        getDiv(
                                            "DD Number",
                                            "dd_number",
                                            value.number
                                        ) +
                                        getDatePicker(
                                            "DD Date",
                                            "dd_date",
                                            value.payment_date
                                        ) +
                                        getDiv(
                                            "DD Amount",
                                            "dd_amount",
                                            value.amount
                                        )
                                    );
                                    $(
                                        ".dd_bank option[value=" +
                                        value.bank_id +
                                        "]"
                                    ).prop("selected", true);
                                }
                            });
                        }
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            } else {
                $(".payment_details").html(
                    getBank("transaction_bank") +
                    getDiv("Transaction Number", "transaction_number") +
                    getDatePicker("Transaction Date", "transaction_date") +
                    getDiv("Online Amount", "online_amount") +
                    getBank("dd_bank") +
                    getDiv("Account Number", "dd_account_number") +
                    getDiv("DD Number", "dd_number") +
                    getDatePicker("DD Date", "dd_date") +
                    getDiv("DD Amount", "dd_amount")
                );
            }

            update_js();
            break;
    }
}

function getDiv(name, class_name, value = "") {
    var maxlength_string=class_name=='cheque_number'?'maxlength="6"':' ';
    var value = class_name=='transaction_number'?'111111':value;
    data =
        `<div class="col-md-2">
    <div class="mb-3">
      <label for="` +class_name +`" class="control-label">` +name +` <span class="text-danger"> * </span></label>
      <input type="text" class="form-control" name="` +class_name +`" id="` +class_name +`" value="` +value +`" autocomplete="off" placeholder="Enter ` +name +`"`+maxlength_string+` >
    </div>
  </div>`;
    return data;
}

function getDatePicker(name, class_name, value = "") {
    dateData =
        `<div class="col-md-2">
    <div class="mb-3">
      <label for="" class="control-label">` +
        name +
        ` <span class="text-danger"> * </span></label>
      <div class="input-group ">
        <input type="text" name="` +
        class_name +
        `"  class="form-control datepicker ` +
        class_name +
        `" autocomplete="off" id="` +
        class_name +
        `" value="` +
        value +
        `">
        <span class="input-group-text input-group-addon"><i data-feather="calendar"></i></span>
      </div>
    </div>
  </div>`;
    return dateData;
}

function getBank(class_name,is_online) {
    var bankResponse = "";
    $.ajax({
        async: false,
        url: aurl + "/bank/data",
        type: "POST",
        dataType: "json",
        success: function(result) {
            bankResponse = result;
        },
        error: function(request) {
            toaster_message("Something Went Wrong! Please Try Again.", "error");
        },
    });
    var html =
        `<div class="col-md-2"><div class="mb-3">
                <label for="` +
        class_name +
        `" class="  control-label">Bank  <span class="text-danger"> * </span></label>
                <select class="form-select form-control select2 bank ` +
        class_name +
        `" id="" name="` +
        class_name +
        `">
                <option selected disabled class="input-cstm">Please Select</option>`;
    $.each(bankResponse, function(key, value) {
        if(class_name=='transaction_bank'|| class_name=='transaction_bank_1'){
            selcted_value=(value.id==1?'selected':'')
        }else{
            selcted_value=''
        }
        html += '<option value="' + value.id + '" '+selcted_value+'>' + value.name + "</option>";
    });
    html += "</select></div></div>";

    return html;
}

function addButton(name, class_name, id) {
    buttonData =
        `<div class="col-md-2">
    <div class="mb-3">
        <label class="">&nbsp;</label>
        <button class="btn btn-primary ` +
        class_name +
        `" data-id="` +
        id +
        `" type="button">` +
        name +
        `</button>
    </div>
  </div>`;
    return buttonData;
}

function update_js() {
    datepickerInit();
    feather.replace();
}

$("body").on("click", ".check", function() {
    switch ($(this).data("id")) {
        case 1:
            $(".payment_details").append(
                '<div class="duplicate row">' +
                getBank("cheque_bank_1") +
                getDiv("Account Number", "cheque_account_number_1") +
                getDiv("Cheque Number", "cheque_number_1") +
                getDatePicker("Cheque Date", "cheque_date_1") +
                getDiv("Cheque Amount", "cheque_amount_1") +
                addButton("Remove", "remove", 1) +
                "</div>"
            );
            update_js();
            break;
        case 2:
            $(".payment_details").append(
                '<div class="duplicate row">' +
                getBank("dd_bank_1") +
                getDiv("Account Number", "dd_account_number_1") +
                getDiv("DD Number", "dd_number_1") +
                getDatePicker("DD Date", "dd_date_1") +
                getDiv("DD Amount", "dd_amount_1") +
                addButton("Remove", "remove", 1) +
                "</div>"
            );
            update_js();
            break;
        case 3:
            $(".payment_details").append(
                '<div class="duplicate row">' +
                getBank("transaction_bank_1") +
                getDiv("Transaction Number", "transaction_number_1") +
                getDatePicker("Transaction Date", "transaction_date_1") +
                getDiv("Online Amount", "online_amount_1") +
                addButton("Remove", "remove", 1) +
                "</div>"
            );
            update_js();
            break;
    }
    $(".check").attr("disabled", true);
});
$("body").on("click", ".remove", function() {
    $(".duplicate").remove();
    $(".check").attr("disabled", false);
});
