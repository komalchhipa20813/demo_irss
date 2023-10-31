(function($) {
  'use strict';

  // initializing inputmask
  $(":input").inputmask();

    /* Mask for aadhaar card code */
    Inputmask("9{4}[-]9{4}[-]9{4}", {
        placeholder: "_",
        greedy: false
    }).mask('#adharcard_number');

    /* Mask for aadhaar card code */
    Inputmask("A{5}[-]9{4}[-]A{1}", {
        placeholder: "_",
        greedy: false
    }).mask('#pancard_number');

$("#rto_code").inputmask({ mask: ["AA-99", "AA-9"],placeholder: "" });

    Inputmask("A{2}[ ]9{2}[ ]A{3}[ ]9{4}", {
        placeholder: "_",
        greedy: false
    }).mask('#vehical_registration');

})(jQuery);
