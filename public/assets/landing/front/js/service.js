$(document).ready(function() {
  "use strict";
  /*  APPOINTMENTS CALENDER  */
  if (document.getElementById("sl-calendar")) {
    jQuery("#sl-calendar").fullCalendar({
      height: "auto",
      dayRender: function(date, cell) {
        // It's an example, do your own test here
        if (cell.hasClass("fc-past")) {
          cell.addClass("disabled");
        }
      },
      dayClick: function(date, jsEvent, view) {
        var selected_date = moment(date).format("YYYY-MM-DD");
        var newdate = new Date();
        var current_date = moment(newdate).format("YYYY-MM-DD");
        if (selected_date < current_date) {
          return false;
        }
        $("#date_select_msg").hide();
        $("#timelist").removeClass(
          "d-flex align-items-center justify-content-center"
        );
        setdatecookie(selected_date);
        settimecookie("");
        callajaxtimeslot(selected_date);
      }
    });
    
    $("#timelist").addClass(
      "d-flex align-items-center justify-content-center"
    );
   
    $("#sl-calendar")
      .find(".fc-state-highlight")
      .removeClass("fc-today fc-state-highlight text-white");

    $(".sl-appointmentPopup").on("shown.bs.modal", function() {
      $("#sl-calendar").fullCalendar("render");
    });
  }
  checkyearmonth();
  checkdataexist();
  staticinfo();
  checkbooking();
});

$("body").on("click", "button.fc-prev-button", function() {
  "use strict";
  checkyearmonth();
});
$("body").on("click", "button.fc-next-button", function() {
  "use strict";

  checkyearmonth();
});
function checkyearmonth() {
  "use strict";
  const date = new Date();
  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December"
  ];
  const calandertext = $(".fc-left h2").html();
  if (
    date.getFullYear() == calandertext.split(" ")[1] &&
    monthNames[date.getMonth()] == calandertext.split(" ")[0]
  ) {
    $(".fc-prev-button").attr("disabled", true);
  } else {
    $(".fc-prev-button").attr("disabled", false);
  }
}
$(".copy").on("click", function() {
  "use strict";
  $("#offer_code").val($(this).attr("data-code"));
});
// first tab next button click
$("#first_tab_next_btn").on("click", function() {
  "use strict";
  if (
    is_logedin == 2 &&
    validate_date_time() &&
    login_required == 1 &&
    customer_login == 1
  ) {
    $("#loginmodal").modal("show");
  } else {
    const nextTabLinkEl = $(".nav-tabs .active")
      .closest("li")
      .next("li")
      .find("a")[0];
    const nextTab = new bootstrap.Tab(nextTabLinkEl);
    if (validate_date_time()) {
      staticinfo();
      $("#loginmodal").modal("hide");
      nextTab.show();
    }
  }
});
$("#btnguest").on("click", function() {
  const nextTabLinkEl = $(".nav-tabs .active")
    .closest("li")
    .next("li")
    .find("a")[0];
  const nextTab = new bootstrap.Tab(nextTabLinkEl);
  staticinfo();
  $("#loginmodal").modal("hide");
  nextTab.show();
});
function staticinfo() {
  "use strict";
  if (is_logedin == 1) {
    $("#first_name").val(name);
    $("#email").val(email);
  }
  if (sendbox == "sendbox") {
    if (is_logedin == 1) {
      $("#first_name").val(name);
      $("#email").val(email);
    } else {
      $("#first_name").val("Juliana");
      $("#last_name").val("Jones");
      $("#email").val("JulianaFJones@yopmail.com");
    }
    $("#address").val("2409 Medical Center Drive Sarasota, FL");
    $("#landmark").val("Bee Ridge Rd");
    $("#postalcode").val("34236");
    $("#city").val("Los Angeles");
    $("#state").val("California");
    $("#country").val("United States");
    $("#mobile").val("9413651362");
    $("#message").val(
      "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
    );
  }
}
// second tab next button click
$("#second_tab_next_btn").on("click", function() {
  "use strict";
  const nextTabLinkEl = $(".nav-tabs .active")
    .closest("li")
    .next("li")
    .find("a")[0];
  const nextTab = new bootstrap.Tab(nextTabLinkEl);
  if (validate_information()) {
    store_information();
    nextTab.show();
  }
});
// second tab previous button
$("#previous_btn_tab_2").on("click", function() {
  "use strict";
  const prevTabLinkEl = $(".nav-tabs .active")
    .closest("li")
    .prev("li")
    .find("a")[0];
  const prevTab = new bootstrap.Tab(prevTabLinkEl);
  prevTab.show();
});
// third tab previous button
$("#previous_btn_tab_3").on("click", function() {
  "use strict";
  const prevTabLinkEl = $(".nav-tabs .active")
    .closest("li")
    .prev("li")
    .find("a")[0];
  const prevTab = new bootstrap.Tab(prevTabLinkEl);
  prevTab.show();
});
// check validation
function validate_date_time() {
  "use strict";
  var valid = false;
  if ($("input[name=time]:checked").length == 0) {
    toastr.error(validatetime);
  } else {
    $(".date_time_error").html("");
    valid = true;
  }
  return valid;
}
// TO STORE values IN COOCKIE
function setdatecookie(selectdate) {
  "use strict";
  var date = new Date();
  date.setTime(date.getTime() + 365 * 24 * 60 * 60 * 1000);
  document.cookie =
    "booking_date" +
    "=" +
    selectdate +
    ";expires=" +
    date.toUTCString() +
    ";path=/";
  $(".booking_date").html(selectdate);
}
function settimecookie(selecttime) {
  "use strict";
  var date = new Date();
  date.setTime(date.getTime() + 365 * 24 * 60 * 60 * 1000);
  document.cookie =
    "booking_time" +
    "=" +
    selecttime +
    ";expires=" +
    date.toUTCString() +
    ";path=/";
  $(".booking_time").html(selecttime);
}
function store_information() {
  "use strict";
  $("#pills-profile .form-control").each(function(e) {
    const date = new Date();
    date.setTime(date.getTime() + 365 * 24 * 60 * 60 * 1000);
    document.cookie =
      this.id +
      "=" +
      $(this).val() +
      ";expires=" +
      date.toUTCString() +
      ";path=/";
    $(".show_" + this.id).html($(this).val());
  });
}
// TO DISPALY ALL COOKIE VALUES
$(".booking_date").html(getCookie("booking_date"));
$(".booking_time").html(getCookie("booking_time"));
$("#pills-profile .form-control").each(function(e) {
  "use strict";
  var coockie = getCookie(this.id);
  $("#pills-profile").find("#" + this.id).val(coockie);
  $(".show_" + this.id).html(coockie);
});
//  TO GET COOKIE VALUE BY NAME
function getCookie(name) {
  "use strict";
  var nameEQ = name + "=";
  var ca = document.cookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}
// DELETE COOKIES
function deleteCookie() {
  "use strict";
  var d = new Date();
  $("#pills-profile .form-control").each(function(e) {
    document.cookie = this.id + "=; Path=/; Expires=" + d + ";";
  });
  document.cookie = "booking_date=; Path=/; Expires=" + d + ";";
  document.cookie = "booking_time=; Path=/; Expires=" + d + ";";
}

function selecttimeslot(x) {
  "use strict";
  $(".active-time").removeClass("active-time");
  $(x).next().addClass("active-time");
  $(".date_time_error").html("");
  settimecookie($(x).val());
  checkbooking();
}

function checkbooking() {
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#slotlimiturl").val(),
    type: "post",
    dataType: "json",
    data: {
      service_id: $("#service_id").val(),
      inputdate: getCookie("booking_date"),
      time: getCookie("booking_time"),
      vendor_id: $("#vendor_id").val()
    },
    success: function(response) {
      if (response == "0") {
        toastr.error(bookingmessage);
        $("#pills-home").addClass("active show");
        $("#pills-home-tab").addClass("active");
        $("#pills-profile").removeClass("active show");
        $("#pills-profile-tab").removeClass("active");
        $("#pills-contact").removeClass("active show");
        $("#pills-contact-tab").removeClass("active");
        $("#first_tab_next_btn").addClass("disabled");
      } else {
        $("#close").hide();
        $("#first_tab_next_btn").removeClass("disabled");
      }
    }
  });
}
function onloadtimslots(selected_date, current_date) {
  "use strict";
  var set_date = selected_date;
  if (set_date == "" || set_date == null || selected_date < current_date) {
    set_date = current_date;
  }
  callajaxtimeslot(set_date);
}
function callajaxtimeslot(set_date) {
 
  ("use strict");
  $("#timelist").addClass(
    "d-flex align-items-center justify-content-center"
  );
  $("#timeslote")
    .show()
    .html(
      '<div class="text-center" id="timeslote"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
    );
  $("#sl-calendar")
    .find(".fc-state-highlight")
    .removeClass("fc-today fc-state-highlight text-white");
  $("#sl-calendar")
    .find("[data-date='" + set_date + "']")
    .addClass("fc-today fc-state-highlight text-white");
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: $("#timesloturl").val(),
    type: "post",
    dataType: "json",
    data: {
      inputDate: set_date,
      vendor_id: $("#vendor_id").val()
    },
    success: function(response) {
      let html = "";
        if (response == "1") {
          html +=
            '<label class="text-danger"><h5>' + store_close + "</h5><label>";
          $("#timelist").addClass(
            "d-flex align-items-center justify-content-center"
          );
          $("#close").show().html(html);
          $("#timeslote").hide();
          setdatecookie(set_date);
        } else {
          $("#timelist").removeClass(
            "d-flex align-items-center justify-content-center"
          );
          for (var i in response) {
            var status = "lable-disable ";
            var disable = "disabled";
            if (response[i]["status"] === "active") {
              status = "lable-active";
              disable = "";
            }
            var default_time = "";
            var time_checked = "";
            if (
              set_date == getCookie("booking_date") &&
              getCookie("booking_time") == response[i]["slot"]
            ) {
              default_time = "active-time";
              time_checked = "checked";
            }
            html +=
              '<div class="col-lg-4 col-md-6 col-12 d-flex"><span class="sl-radio next-step w-100"><input type="radio" name="time" onclick="selecttimeslot(this);" value="' +
              response[i]["slot"] +
              '" ' +
              time_checked +
              ' id="flexRadioDefault' +
              i +
              '" ' +
              disable +
              '><label for="flexRadioDefault' +
              i +
              '"class=" text-center w-100 ' +
              status +
              " " +
              default_time +
              '">' +
              response[i]["slot"] +
              "</label></span></div>";
          }
          $("#timeslote").show().html(html);
          $("#close").hide();
          setdatecookie(set_date);
        }
      }
    
  });
}
function checkdataexist() {
  "use strict";
  if (
    getCookie("booking_date") != "" &&
    getCookie("booking_date") != null &&
    (getCookie("booking_time") != "" && getCookie("booking_time") != null) &&
    checkinformationexist() == 0
  ) {
    $("#pills-home").removeClass("active show");
    $("#pills-home-tab").removeClass("active");
    $("#pills-contact").removeClass("active show");
    $("#pills-contact-tab").removeClass("active");
    $("#pills-profile").addClass("active show");
    $("#pills-profile-tab").addClass("active");
  } else if (
    getCookie("booking_date") != "" &&
    getCookie("booking_time") != "" &&
    checkinformationexist() != 0
  ) {
    $("#pills-home").removeClass("active show");
    $("#pills-home-tab").removeClass("active");
    $("#pills-contact").addClass("active show");
    $("#pills-contact-tab").addClass("active");
    $("#pills-profile").removeClass("active show");
    $("#pills-profile-tab").removeClass("active");
  }
}
function checkinformationexist() {
  "use strict";
  var check = 1;
  $("#pills-profile .info .form-control").each(function(e) {
    if (this.id == message) {
      return false;
    }
    var coockie = getCookie(this.id);
    if (coockie != null && coockie != "") {
      check = 1;
    } else {
      check = 0;
      return false;
    }
  });
  return check;
}
function validate_information() {
  "use strict";
  var isValid = true;
  $(".is-invalid").removeClass("is-invalid");
  var name = $("#first_name").val();
  var lastname = $("#last_name").val();
  var email = $("#email").val();
  var address = $("#address").val();
  var landmark = $("#landmark").val();
  var postalcode = $("#postalcode").val();
  var city = $("#city").val();
  var state = $("#state").val();
  var country = $("#country").val();
  var mobile = $("#mobile").val();
  if (name == "") {
    $("#first_name").addClass("is-invalid").focus();
    isValid = false;
  }else if(lastname == "")
  {
    $("#last_name").addClass("is-invalid").focus();
    isValid = false;
  }else if (mobile == "") {
    $("#mobile").addClass("is-invalid").focus();
    isValid = false;
  } else if (email == "") {
    $("#email").addClass("is-invalid").focus();
    isValid = false;
  }  else if (country == "") {
    $("#country").addClass("is-invalid").focus();
    isValid = false;
  } else if (state == "") {
    $("#state").addClass("is-invalid").focus();
    isValid = false;
  } else if (city == "") {
    $("#city").addClass("is-invalid").focus();
    isValid = false;
  } else if (address == "") {
    $("#address").addClass("is-invalid").focus();
    isValid = false;
  } 
  return isValid;
}
function createbooking(dataurl) {
  "use strict";
  $('#btnsave').addClass('d-none');
  $('#book_loader').removeClass('d-none');
  $.ajax({
    headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
    },
    url: dataurl,
    type: "post",
    dataType: "json",
    data: {
      slug: $("#vendor_slug").val(),
      vendor_id: $("#vendor_id").val(),
      service_id: $("#service_id").val(),
      service_image: $("#service_image").val(),
      service_name: $("#service_name").val(),
      booking_date: getCookie("booking_date"),
      booking_time: getCookie("booking_time"),
      name: $("#first_name").val(),
      email: $("#email").val(),
      mobile: $("#mobile").val(),
      city: $("#city").val(),
      state: $("#state").val(),
      country: $("#country").val(),
      landmark: $("#landmark").val(),
      postalcode: $("#postalcode").val(),
      address: $("#address").val(),
      message: $("#message").val(),
      sub_total: $("#price").val(),
      tax: $("#tax").val(),
      grand_total: $("#hidden_grand_total").val()
    },
    success: function(response) {
      $('#btnsave').ajaxStop();
      if (response.status == 0) {
        toastr.error(response.message);
      } else {
        deleteCookie();
        window.location.href = response.nexturl;
      }
    }
  });
}
