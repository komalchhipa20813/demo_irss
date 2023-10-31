$(document).ready(function(){
    
    if($("#agent_detail").length){
        $("#agent_detail").validate({
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
    

    $(".submit_agent_detail").on("click", function(event){
        event.preventDefault();
        var form = $('#agent_detail')[0];
        var formData = new FormData(form);

         var export_type=$('#export_type').val();
         var fdo=$('#fdo').val();
         var status=$('#status').val();
         var from_date=$('#from_date').val();
         var end_date=$('#end_date').val();
         var code=($('#code').is(":checked"))?$('#code').val():'';
         var name=($('#name').is(":checked"))?$('#name').val():'';
         var account_no=($('#account_no').is(":checked"))?$('#account_no').val():'';
         var bank_name=($('#bank_name').is(":checked"))?$('#bank_name').val():'';
         var dob=($('#dob').is(":checked"))?$('#dob').val():'';
         var ifsc_code=($('#ifsc_code').is(":checked"))?$('#ifsc_code').val():'';
         var pan_no=($('#pan_no').is(":checked"))?$('#pan_no').val():'';
         var created_on=($('#created_on').is(":checked"))?$('#created_on').val():'';

         if($("#agent_detail").valid())
         {
             if(export_type == 'PDF')
             {
            
                $.ajax({
                    url: aurl + "/agent-details/agents-export",
                    type: 'POST',
                    data:{
                        export_type:export_type,
                        fdo:fdo,
                        status:status,
                        from_date:from_date,
                        end_date:end_date,
                        agent_code:code,
                        name:name,
                        account_no:account_no,
                        bank_name:bank_name, 
                        dob:dob,
                        ifsc_code:ifsc_code,
                        pan_no:pan_no,
                        created_on:created_on,

                    },
                     xhrFields: {
                    responseType: 'blob'
                    },
                    beforeSend: function() {
                        $(".submit_agent_detail").prop('disabled', true);
                        $(".submit_agent_detail").text('PDF downloading..');
                    },
                    success: function(response) {
                       
                            var blob = new Blob([response]);
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.download = "AgentDetail.pdf";
                            link.click();
                            $(".submit_agent_detail").prop('disabled', false);
                            $(".submit_agent_detail").text('Export');
                          
                        },
                        error: function(request) {
                            $(".submit_agent_detail").prop('disabled', false);
                            $(".submit_agent_detail").text('Export');
                            toaster_message('Something Went Wrong! Please Try Again.', 'error');
                        },
                });
            }
            else if(export_type == 'Excel')
            {
                $.ajax({
                url: aurl + "/agent-details/agents-export",
                type: 'POST',
                dataType: "JSON",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".submit_agent_detail").prop('disabled', true);
                    $(".submit_agent_detail").text('Excel downloading..');
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
                        $(".submit_agent_detail").prop('disabled', false);
                        $(".submit_agent_detail").text('Export');
                     }
                     else
                     {
                        $(".submit_agent_detail").prop('disabled', false);
                        $(".submit_agent_detail").text('Export');
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

