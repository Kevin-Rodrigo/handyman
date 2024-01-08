$(window).on("load", function() {
    "use strict";
    $('#preloader').fadeOut('slow')
    if ($(".multimenu").find(".active")) {
        $(".multimenu").find(".active").parent().parent().addClass("show");
        $(".multimenu").find(".active").parent().parent().parent().attr("aria-expanded", true);
    }
});
$('#name').on('blur', function() {
    "use strict";
    $('#slug').val($('#name').val().split(" ").join("-").toLowerCase());
});