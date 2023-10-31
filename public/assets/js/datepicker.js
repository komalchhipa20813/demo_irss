$(function() {
  'use strict';
  datepickerInit()
  if($('#dobdatePicker').length) {
    $('#dobdatePicker').datepicker({
      changeMonth: true,
      changeYear: true,
      format: "dd-mm-yyyy",
      todayHighlight: true,
      autoclose: true,
      startDate: new Date('1951', 0, 1),
      endDate:'-18y',

    });
  }
  if($('.business_date').length) {
    $('.business_date').datepicker({
      format: "mm-yyyy",
      startView: "months",
      minViewMode: "months", 
      autoclose: true,
      endDate: "currentDate",
      startDate: "-2y", 
    }).datepicker('setDate', 'today');
  }

  if($('.edit_business_date').length) {
    $('.edit_business_date').datepicker({
      format: "mm-yyyy",
      startView: "months",
      minViewMode: "months", 
      autoclose: true,
      endDate: "currentDate",
      startDate: "-2y", 
    });
  }
  if($('#manufacturing_year').length) {
    $('#manufacturing_year').datepicker({
      format: "yyyy",
      startView: "years",
      minViewMode: "years", 
      autoclose: true,
      endDate: "currentDate",
    });
  }
});
function datepickerInit(){
  if($('.datepicker').length) {
    var currentDate = new Date();
    $('.datepicker').datepicker({
      changeMonth: true,
      changeYear: true,
      format: "dd-mm-yyyy",
      todayHighlight: true,
      autoclose: true,
      maxDate: currentDate,
    });
  }
}
