"use strict";
$(document).ready(function() {

    $(".select2-select-00").select2({
        allowClear: true
    });
    $(".select2-select-01").select2({
        minimumInputLength: 3
    });
    $(".select2-select-02").select2({
        tags: ["Sport", "Gadget", "Politics"]
    });
});