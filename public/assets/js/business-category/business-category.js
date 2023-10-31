/* Datatable */
$('#business-category_tbl').DataTable({
  "aLengthMenu": [
    [10, 30, 50, -1],
    [10, 30, 50, "All"]
  ],
  "iDisplayLength": 10,
  "language": {
    search: ""
  },
  'ajax': {
    type:'POST',
    url: aurl + "/business-category/listing", 
},
'columns': [
    { data: 'id' },
    { data: 'name' },
    { data: 'action' },
  ]
});

$(document).ready(function(){
  
  /* Validation Of Business Category Form */
  $("#business_category_form").validate({
      rules: {
          name: {
              required: true,
              maxlength: 35,
              business_category: true,
              normalizer: function(value) {
                return $.trim(value);
              },
          },
      },
      messages: {
          name: {
              required: "Please Enter Business Category Name",
              business_category: "Business Category Name Already Exists",
          },
      },
      highlight: function(element) {
        $(element).removeClass("error");
    },
  });

  // Business Category Already In Data
  $.validator.addMethod("business_category", function(value) {
    var x = 0;
    var id = $(".business_category_id").val();
    var x = $.ajax({
        url: aurl + "/business-category/business-category-check",
        type: "POST",
        async: false,
        data: { name: value, id: id },
    }).responseText;
    if (x != 0) {
        return false;
    } else return true;
});

  /* Business Category Modal Show */
  $('body').on("click", ".add_business_category", function(){
      $("#business_category_form").validate().resetForm();
      $("#business_category_form").trigger('reset');
      $('#business_category_modal').modal('show');
      $('.business_category_id').val($(this).data('id'));
      $('#title_business_category').text("Add Business Category");
      $('.submit_business_category').text("Add Business Category");
  });

  /* Add Or Update Business Category Data */    
  $(".submit_business_category").on("click", function(event){
      event.preventDefault();
      var form = $('#business_category_form')[0];
      var formData = new FormData(form);
      if($("#business_category_form").valid()){   
          $.ajax({
              url: aurl + "/business-category",
              type: 'POST',
              dataType: "JSON",
              data:formData,
              cache:false,
              contentType: false,
              processData: false,
              success: function(data) {
                  $('#business_category_modal').modal('hide');
                  toaster_message(data.message,data.icon);
              },
              error: function(request) {
                toaster_message('Something Went Wrong! Please Try Again.', 'error');
            },
          });
      }
  });

  /* Update Business Category Data */
  $("body").on("click", ".business_category_edit", function(event) {
    event.preventDefault();
      var id = $(this).data("id");
    $(".business_category_id").val(id);
      $.ajax({
          url: aurl + "/business-category/{" + id + "}",
          type: "GET",
          data: { id: id },
          dataType: "JSON",
          success: function(data){
            if(data.status){
                $("#business_category_form").validate().resetForm();
                $("#business_category_form").trigger('reset');
                $('#title_business_category').text("Update Business Category");
                $('#business_category_modal').modal('show');
                $('.submit_business_category').text("Update Business Category");
                $('.name').val(data.name);
            }else{
                toaster_message(data.message,data.icon);
            }
          },
          error: function(request) {
            toaster_message('Something Went Wrong! Please Try Again.', 'error');
          },
      });
  });
});