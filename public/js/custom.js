$(function () {
  //Initialize Select2 Elements
  $('.select2').select2();
  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });

  // Restricts input for the set of matched elements to the given inputFilter function.
  (function ($) {
    $.fn.inputFilter = function (callback, errMsg) {
      return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function (e) {
        if (callback(this.value)) {
          // Accepted value
          if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
            $(this).removeClass("input-error");
            this.setCustomValidity("");
          }
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          // Rejected value - restore the previous one
          $(this).addClass("input-error");
          this.setCustomValidity(errMsg);
          this.reportValidity();
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          // Rejected value - nothing to restore
          this.value = "";
        }
      });
    };
  }(jQuery));

  $("#g_id, #gc_id, #gclidfieldid, #ref_url").inputFilter(function (value) {
    return /^\d*$/.test(value); // Allow digits only, using a RegExp
  }, "Only digits allowed");

  // dataTable();
});