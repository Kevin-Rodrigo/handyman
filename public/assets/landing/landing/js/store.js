$("#country").change(function() {
    "use strict";
    var country_id = $("#country option:selected").attr("data-id");
    $("#city").empty();
    var cityselect;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        url: cityurl,
        type: "post",
        dataType: "json",
        data: {
            country: country_id
        },
        success: function(result) {
            $('#city').html('<option value="">' + select + '</option>');
            $.each(result.city, function(key, value) {

                if (cityname == value.city) {
                    cityselect = "selected";
                } else {
                    cityselect = "";
                }
                $("#city").append('<option value="' + value.city + '"' + cityselect +
                    '>' + value.city + '</option>');
            });
        }
    });
}).change();