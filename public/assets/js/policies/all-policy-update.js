$(document).ready(function(){
	   if($("#"+currentLocation+"_tbl").length){
        function policy_listing($data){
            var module_name=$("#"+currentLocation+"_tbl").attr('data-name');
            $("#"+currentLocation+"_tbl").DataTable({
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
                    url: aurl + "/"+currentLocation+"/update-listing",
                },
                columns: [
                   { data: '0' },
                    { data: '1' },
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
            'from_date':$('#from_date').val(),
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
            $('#from_date').val('');
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
                formData.append("module_name", 'all');
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
            'from_date':$('#from_date').val(),
            'end_date':$('#end_date').val()
        }

        
        $.ajax({
                url: aurl + "/update-policy/export-all-data",
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
                                    $("#export").text('Export Policy');
                        }
                        else
                        {
                            $("#export").prop('disabled', false);
                             $("#export").text('Export Policy');
                                    toaster_message(response.msg,response.icon);
                        }
                    
                      },
                      error: function (ajaxContext) {
                        $("#export").prop('disabled', false);
                        $("#export").text('Export Policy');
                        $("#downloadExcel").show();
                        $("#ExcelDownloadLoader").hide();
                      },
            });

    });
    // end export

    
});

function addYears(date) {
    var start_date=$('#start_date').val();
    var policy_tenure=$('#policy_tenure').val();
    if(start_date !='' && policy_tenure != '')
    {
        var full_year=(policy_tenure == 'ABOVE15YRS' || policy_tenure == 'SHORT') ? '' : policy_tenure;
        $('.end_date').datepicker({
            format: "dd-mm-yyyy",
            todayHighlight: true,
            autoclose: true,
        });
        if(full_year!=''){
            $('#end_date').prop('readonly',true);
            var start_date = start_date.split("-").reverse().join("-");
            var date=new Date(start_date);
            var year = date.getFullYear();
            var month = date.getMonth();
            var day = date.getDate()-1;
            var c = new Date(year + parseInt(policy_tenure), month, day);
            $('#end_date').datepicker('setDate',c);
        }else{
            $('#end_date').prop('readonly',false);
        }
    }
}