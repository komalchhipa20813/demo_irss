let currentLocation = window.location.href.split("/")[3];
$(function() {
    showSwal = function(type) {
        "use strict";
        if (type === "basic") {
            swal.fire({
                text: "Any fool can use a computer",
                confirmButtonText: "Close",
                confirmButtonClass: "btn btn-danger",
            });
        } else if (type === "title-and-text") {
            Swal.fire(
                "The Internet?",
                "That thing is still around?",
                "question"
            );
        } else if (type === "title-icon-text-footer") {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Something went wrong!",
                footer: "<a href>Why do I have this issue?</a>",
            });
        } else if (type === "custom-html") {
            Swal.fire({
                title: "<strong>HTML <u>example</u></strong>",
                icon: "info",
                html: "You can use <b>bold text</b>, " +
                    '<a href="//github.com">links</a> ' +
                    "and other HTML tags",
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Great!',
                confirmButtonAriaLabel: "Thumbs up, great!",
                cancelButtonText: '<i data-feather="thumbs-up"></i>',
                cancelButtonAriaLabel: "Thumbs down",
            });
            feather.replace();
        } else if (type === "custom-position") {
            Swal.fire({
                position: "top-end",
                icon: "success",
                title: "Your work has been saved",
                showConfirmButton: false,
                timer: 1500,
            });
        } else if (type === "passing-parameter-execute-cancel") {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger me-2",
                },
                buttonsStyling: false,
            });

            swalWithBootstrapButtons
                .fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "me-2",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel!",
                    reverseButtons: true,
                })
                .then((result) => {
                    if (result.value) {
                        swalWithBootstrapButtons.fire(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                            "Cancelled",
                            "Your imaginary file is safe :)",
                            "error"
                        );
                    }
                });
        } else if (type === "message-with-auto-close") {
            let timerInterval;
            Swal.fire({
                title: "Auto close alert!",
                html: "I will close in <b></b> milliseconds.",
                timer: 2000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading();
                    timerInterval = setInterval(() => {
                        const content = Swal.getHtmlContainer();
                        if (content) {
                            const b = content.querySelector("b");
                            if (b) {
                                b.textContent = Swal.getTimerLeft();
                            }
                        }
                    }, 100);
                },
                willClose: () => {
                    clearInterval(timerInterval);
                },
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    console.log("I was closed by the timer");
                }
            });
        } else if (type === "message-with-custom-image") {
            Swal.fire({
                title: "Sweet!",
                text: "Modal with a custom image.",
                // imageUrl: 'https://unsplash.it/400/200',
                imageUrl: "../assets/images/others/placeholder.jpg",
                imageWidth: 400,
                imageHeight: 200,
                imageAlt: "Custom image",
            });
        } else if (type === "mixin") {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: "success",
                title: "Signed in successfully",
            });
        }
    };
});

function toaster_message(message, icon, url) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            text: message,
            icon: icon,
            confirmButtonText: "OK",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                typeof url === "undefined" ?
                    $("#" + currentLocation + "_tbl")
                    .DataTable()
                    .ajax.reload() :
                    (window.location.href = aurl + "/" + url);
            }
        });
}

function error_toaster_message(message, icon)
{
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            text: message,
            icon: icon,
            confirmButtonText: "OK",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                
            }
        });
}

function document_toaster_message(message, icon) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            text: message,
            icon: icon,
            confirmButtonText: "OK",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                $("#document_tbl").DataTable().ajax.reload();
            }
        });
}

function customer_toaster_message(message, icon,id) {
    $("#customer_modal").modal("hide");
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            text: message,
            icon: icon,
            confirmButtonText: "OK",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                $.ajax({
                    url: aurl + "/customer/get-data",
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        var html =
                            '<option selected disabled class="input-cstm">Please Select</option>';
                        $.each(data, function(key, value) {
                            var customer_code=value.customer_code!=null?value.customer_code:'';
                            var prefix=value.prefix!=null?value.prefix:'';
                            var first_name=value.first_name!=null?value.first_name:'';
                            var last_name=value.last_name!=null?value.last_name:'';
                            var middle_name=value.middle_name!=null?value.middle_name:'';
                            html +='<option value="' +value.id +'">'+customer_code+" " +prefix+" " +first_name+" "+middle_name+" " +last_name+"</option>";
                        });
                        $("#customer").html(html);
                        $("#customer option[value=" + id + "]").prop("selected",true);
                    },
                    error: function(request) {
                        toaster_message(
                            "Something Went Wrong! Please Try Again.",
                            "error"
                        );
                    },
                });
            }
        });
}

function co_sharing_toaster_message(message, icon,data) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            text: message,
            icon: icon,
            confirmButtonText: "OK",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                $("#co_sharing_detail").DataTable().ajax.reload();
                if(data.flag==1){
                    previous_od=($('#od').val()=='')?0:parseInt($('#od').val())
                    previous_terrorism=($('#terrorism').val()=='')?0:parseInt($('#terrorism').val())
                    $('#od').val(parseInt(data.policy_premium)+previous_od)
                    $('#terrorism').val(parseInt(data.policy_terrorism_premium)+previous_terrorism)
                }else{
                    previous_od=($('#od').val()=='')?0:parseInt($('#od').val())
                    previous_terrorism=($('#terrorism').val()=='')?0:parseInt($('#terrorism').val())
                    $('#od').val(previous_od-parseInt(data.policy_premium))
                    $('#terrorism').val(previous_terrorism-parseInt(data.policy_terrorism_premium))
                }
                total_premium()
            }
        });
}

function add_member_toaster_message(message, icon) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger me-2",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            text: message,
            icon: icon,
            confirmButtonText: "OK",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.value) {
                $("#member_tbl").DataTable().ajax.reload();
            }
        });
}