$(document).ready(function(){
    
    if($("#fdo_detail").length){
        $("#fdo_detail").validate({
            rules: {
                "select_column[]":{
                    required: true,
                },
                export_type:{
                    required:true,
                },
               
            },
            messages: {
                "select_column[]":{
                    required:"Please select at least one Detail",
                },
                export_type:{
                    required:"Please Select Export",
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
      
    $(".submit_fdo_detail").on("click", function(event){
        event.preventDefault();
        var form = $('#fdo_detail')[0];
        var formData = new FormData(form);

         var export_type=$('#export_type').val();
         var status=$('#status').val();
         var from_date=$('#from_date').val();
         var end_date=$('#end_date').val();
         var code=($('#code').is(":checked"))?$('#code').val():'';
         var name=($('#name').is(":checked"))?$('#name').val():'';
         var account_no=($('#account_no').is(":checked"))?$('#account_no').val():'';
         var bank_name=($('#bank_name').is(":checked"))?$('#bank_name').val():'';
         var ifsc_code=($('#ifsc_code').is(":checked"))?$('#ifsc_code').val():'';
         var pan_no=($('#pan_no').is(":checked"))?$('#pan_no').val():'';

         if($("#fdo_detail").valid())
         {
             if(export_type == 'PDF')
             {
            
                $.ajax({
                    url: aurl + "/fdo-details/fdos-export",
                    type: 'POST',
                    data:{
                        export_type:export_type,
                        status:status,
                        from_date:from_date,
                        end_date:end_date,
                        fdo_code:code,
                        name:name,
                        account_no:account_no,
                        bank_name:bank_name, 
                        ifsc_code:ifsc_code,
                        pan_no:pan_no,

                    },
                     xhrFields: {
                    responseType: 'blob'
                    },
                    beforeSend: function() {
                    $(".submit_fdo_detail").prop('disabled', true);
                    $(".submit_fdo_detail").text('PDF downloading..');
                     },
                    success: function(response) {
                       
                            var blob = new Blob([response]);
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "FDODetail.pdf";
                            link.click();
                            $(".submit_fdo_detail").prop('disabled', false);
                            $(".submit_fdo_detail").text('Export');
                          
                        },
                        error: function(request) {
                            $(".submit_fdo_detail").prop('disabled', false);
                            $(".submit_fdo_detail").text('Export');
                            toaster_message('Something Went Wrong! Please Try Again.', 'error');
                        },
                });
            }
            else if(export_type == 'Excel')
            {
                $.ajax({
                url: aurl + "/fdo-details/fdos-export",
                type: 'POST',
                dataType: "JSON",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".submit_fdo_detail").prop('disabled', true);
                    $(".submit_fdo_detail").text('Excel downloading..');
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
                        $(".submit_fdo_detail").prop('disabled', false);
                        $(".submit_fdo_detail").text('Export');
                     }
                     else
                     {
                        $(".submit_fdo_detail").prop('disabled', false);
                        $(".submit_fdo_detail").text('Export');
                        toaster_message('No data available', 'error');
                     }

                    },
                    error: function(request) {
                        toaster_message('Something Went Wrong! Please Try Again.', 'error');
                    },
                });
            }
         }

        
    });    
});

