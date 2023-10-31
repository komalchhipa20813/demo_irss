$(document).ready(function(){
    var module_name=$("#"+currentLocation+"_tbl").attr('data-name');
	   if($("#"+currentLocation+"_tbl").length && module_name != 'motor'){
        function policy_listing($data){
            $("#"+currentLocation+"_tbl").DataTable({
                processing: true,
                serverSide: true,
                aLengthMenu: [
                    [10, 30, 50, -1],
                    [10, 30, 50, "All"],
                ],
                iDisplayLength: 10,
                language: {
                    search: "",
                },

                ajax: {
                    type: "POST",
                    data:$data,
                    url: aurl + "/"+currentLocation+ "/"+module_name+"/update-listing",
                },
                columns: [
                    { data: '0' },
                    { data: '1' },
                    { data: '12' },
                    { data: '2' },
                    { data: '3' },
                    { data: '4' },
                    { data: '5' },
                    { data: '6' },
                    { data: '7' },
                    { data: '8' },
                    { data: '9' },
                    { data: '10' },
                    { data: '11' },
                ],
                columnDefs: [
                {
                    render: function (data, type, full, meta) {
                        return "<div  class='text-wrap width-200'>" + data + "</div>";
                    },
                    targets: 2
                }
             ],
                drawCallback:function(){
                    select()
                    datepickerInit();
        		}
            });
        }
        policy_listing({});
    }

    if($("#"+currentLocation+"_tbl").length && module_name == 'motor'){
        function policy_listing($data){
            var module_name=$("#"+currentLocation+"_tbl").attr('data-name');
            $("#"+currentLocation+"_tbl").DataTable({
                processing: true,
                serverSide: true,
                aLengthMenu: [
                    [10, 30, 50, -1],
                    [10, 30, 50, "All"],
                ],
                iDisplayLength: 10,
                language: {
                    search: "",
                },

                ajax: {
                    type: "POST",
                    data:$data,
                    url: aurl + "/"+currentLocation+ "/"+module_name+"/update-listing",
                },
                columns: [
                    { data: '0' },
                    { data: '1' },
                    { data: '14' },
                    { data: '2' },
                    { data: '3' },
                    { data: '4' },
                    { data: '5' },
                    { data: '6' },
                    { data: '7' },
                    { data: '8' },
                    { data: '9' },
                    { data: '10' },
                    { data: '11' },
                    { data: '12' },
                    { data: '13' },
                ],
                drawCallback:function(){
                    select()
                    datepickerInit();
                }
            });
        }
        policy_listing({});
    }
    $('body').on('click','#filter_policy',function(){
        $data = {
            'agent': $('#agent').val(),
            'name':$('#name').val(),
            'branch':$('#branch').val(),
            'company':$('#company').val(),
            'cheque_no':$('#cheque_no').val(),
            'inward_no':$('#inward_no').val(),
            'policy_no':$('#policy_no').val(),
            'engine_no':$('#engine_no').val(),
            'chasis_no':$('#chasis_no').val(),
            'registration_no':$('#registration_no').val(),
            'product':$('#product').val(),
            'policy_start_date':$('#policy_start_date').val(),
            'policy_end_date':$('#policy_end_date').val(),
            'start_date':$('#start_date').val(),
            'end_date':$('#end_date').val()

            }
            $('#search_criteria').modal('hide');
            $("#"+currentLocation+"_tbl").DataTable().destroy();
            policy_listing($data);
    });
    $('body').on('click','#reset_filter',function(){
        $("#agent").val('0').trigger("change");
            $('#name').val('');
            $('#branch').val('0').trigger("change");
            $('#company').val('0').trigger("change");
            $('#cheque_no').val('');
            $('#inward_no').val('');
            $('#policy_no').val('');
            $('#engine_no').val('');
            $('#chasis_no').val('');
            $('#registration_no').val('');
            $('#product').val('0').trigger("change");
            $('#policy_start_date').val('');
            $('#policy_end_date').val('');
            $('#start_date').val('');
            $('#end_date').val('');
            $('#search_criteria').modal('hide');
            $("#"+currentLocation+"_tbl").DataTable().destroy();
            policy_listing({});
    });

    $("body").on("click", ".update-selected-policy", function(event) {
   
    	 var selected_data_no=new Array();
		  $(".checkbox:checked").each(function()
		  {
		      var sr=$(this).attr('data-series');
		      selected_data_no.push(sr);
		  });

		  if(selected_data_no.length == 0)
		  {
		      error_toaster_message('Please Select Records','error',''); 
		  }
		  else
		  {
			   var form = $('#policy_updation_form')[0];
	    		var formData = new FormData(form);
	    		formData.append("selected_data_no", selected_data_no);
	    		$.ajax({
	            url: aurl + "/" + currentLocation + "/store-data",
	            type: 'POST',
	            dataType: "JSON",
	            data:formData,
	            cache:false,
	            contentType: false,
	            processData: false,
	            success: function(data) {
	                toaster_message(data.message,data.icon,data.redirect_url);
	            },
	            error: function(request) {
	                toaster_message('Something Went Wrong! Please Try Again.', 'error');
	            },
	        });
    	}
    });

    // export data
    $("#export").click(function(event){
        var module_name=$('.module').val();
        var url_pera='export-'+ module_name +'-data';
        var export_btn_text='';

        if(module_name == 'motor')
        {
            export_btn_text='Export Motor Policy';
        }
        else if(module_name == 'health')
        {
           export_btn_text='Export Health Policy'; 
        }
        else
        {
            export_btn_text='Export SME Policy'; 
        }
        
            var data = {
            'agent': $('#agent').val(),
            'name':$('#name').val(),
            'branch':$('#branch').val(),
            'company':$('#company').val(),
            'cheque_no':$('#cheque_no').val(),
            'inward_no':$('#inward_no').val(),
            'policy_no':$('#policy_no').val(),
            'engine_no':$('#engine_no').val(),
            'chasis_no':$('#chasis_no').val(),
            'registration_no':$('#registration_no').val(),
            'product':$('#product').val(),
            'policy_start_date':$('#policy_start_date').val(),
            'policy_end_date':$('#policy_end_date').val(),
            'start_date':$('#start_date').val(),
            'end_date':$('#end_date').val()
            }
        
        
        $.ajax({
                url: aurl + "/update-policy/"+url_pera,
                type: "POST",
                data: {
                        data:data,
                      },
                cache: false,
                 beforeSend: function() {

                     $("#export").prop('disabled', true);
                     $("#export").text('Excel downloading..');
                    },
                success: function(response) {
                        if(response.status)
                        {
                            var a = document.createElement("a");
                            a.href = response.file; 
                            a.download = response.name;
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                                    $("#export").prop('disabled', false);
                                    $("#export").text(export_btn_text);
                        }
                        else
                        {
                                     $("#export").prop('disabled', false);
                                    $("#export").text(export_btn_text);
                                    toaster_message(response.msg,response.icon);
                        }
                    
                      },
                      error: function (ajaxContext) {
                        $("#export").prop('disabled', false);
                        $("#export").text(export_btn_text);
                        $("#downloadExcel").show();
                        $("#ExcelDownloadLoader").hide();
                      },
            });

    });
});