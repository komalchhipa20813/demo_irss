$(document).ready(function(){
    
    if($("#gbr_from").length){
        $("#gbr_from").validate({
            rules: {
                insurance:{
                    required: true,
                },
               
            },
            messages: {
                insurance:{
                    required:"Please Select Insurance",
                },
                
            },
            errorPlacement: function(error, element) {
                if (element.parents("div").hasClass("has-feedback") || element.hasClass("select2-hidden-accessible") || element.hasClass("form-check-input")) {
                    error.appendTo(element.parent());
                }else if(element.parents("div").hasClass("uploader")||element.hasClass("datepicker")||element.hasClass("dobdatePicker")){
                    error.appendTo(element.parent().parent());
                }else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).removeClass("error");
            },
        });
    }
    

    $(".submit_report").on("click", function(event){
        event.preventDefault();
        var form = $('#gbr_from')[0];
        var formData = new FormData(form);

         if($("#gbr_from").valid())
         {
              $.ajax({
                url: aurl + "/gross-business/gbr-export",
                type: 'POST',
                dataType: "JSON",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".submit_report").prop('disabled', true);
                    $(".submit_report").text('Excel downloading..');
                },
                success: function(data) {
                     if(data.status)
                     {
                         var a = document.createElement("a");
                        a.href = data.file; 
                        a.download = data.name;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        $(".submit_report").prop('disabled', false);
                        $(".submit_report").text('View Report');
                     }
                     else
                     {
                        $(".submit_report").prop('disabled', false);
                        $(".submit_report").text('View Report');
                        toaster_message('No data available', 'error');
                     }

                    },
                    error: function(request) {
                        $(".submit_report").prop('disabled', false);
                        $(".submit_report").text('View Report');
                        toaster_message('Something Went Wrong! Please Try Again.', 'error');
                    },
                });
            
         }

        
    });  
});

