$(document).ready(function() {
  // Fakes the loading setting a timeout
  setTimeout(function() {
    $("html").addClass("loaded");
  }, 2000);
 
  $('#backtop').removeClass('back-top-show');
});
// datatable
$(document).ready(function() {
  "use strict";

  $(".zero-configuration").DataTable({
    dom: "Bfrtip",

    buttons: ["excelHtml5", "pdfHtml5"]
  });
});

$(".booknow").on("click", function() {
  "use strict";

  var cookies = document.cookie.split(";");

  var d = new Date();

  for (var i = 0; i < cookies.length; i++) {
    var cookie = cookies[i];

    var eqPos = cookie.indexOf("=");

    var name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;

    document.cookie = name + "=; Path=/; Expires=" + d + ";";
  }
});

// sweetalert script for logout

function logout() {
  "use strict";

  Swal.fire({
    title: "Are You Sure to Logout?",

    showDenyButton: false,

    showCancelButton: true,

    confirmButtonText: "Yes"
  }).then(result => {
    if (result.isConfirmed) {
      Swal.fire("Saved!", "", "success");
    }
  });
}

function statusupdate(nexturl) {
  "use strict";

  const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
      confirmButton: "btn btn-success mx-1",

      cancelButton: "btn btn-danger mx-1"
    },

    buttonsStyling: false
  });

  swalWithBootstrapButtons
    .fire({
      title: are_you_sure,

      icon: "warning",

      showCancelButton: true,

      confirmButtonText: yes,

      cancelButtonText: no,

      reverseButtons: true
    })
    .then(result => {
      if (result.isConfirmed) {
        location.href = nexturl;
      } else {
        result.dismiss === Swal.DismissReason.cancel;
      }
    });
}

// new design js //

$("#services-view").owlCarousel({
  loop: true,
  margin: 0,
  nav: true,
  dots: false,
  autoplay: false,
  // autoplayTimeout: 3000,
  navText: [
    "<i class='fa-solid fa-arrow-left-long'></i>",
    "<i class='fa-solid fa-arrow-right-long'></i>"
  ],
  responsive: {
    0: {
      items: 1
    },
    600: {
      items: 2
    },
    1000: {
      items: 3
    }
  }
});

//====== img zoom GLightbox ======//
const lightbox = GLightbox({
  touchNavigation: true,
  loop: true,
  width: "90vw",
  height: "90vh"
});
$(".mobile-number").on("keyup", function() {
  "use strict";
  var val = $(this).val();
  if (isNaN(val)) {
    val = val.replace(/[^0-9\.+.-]/g, "");
    if (val.split(".").length > 2) {
      val = val.replace(/\.+$/, "");
    }
  }
  $(this).val(val);
});

function managefavorite(service_id, vendor_id, f_url) {
  "use Strict";
  if (is_logedin == 2) {
    location.href = loginurl;
  } else {
    $("#preload").show();
    $.ajax({
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
      },
      url: f_url,
      type: "post",
      dataType: "json",
      data: {
        service_id: service_id,
        vendor_id: vendor_id
      },
      success: function(response) {
        $("#preload").hide();
        if (response.status == 0) {
          toastr.error(response.message);
        } else {
          location.reload();
        }
      }
    });
  }
}
$('#btndecline').on('click', function() {
  if (localStorage.getItem("modalsubscribe") != "shown") {
      setTimeout(function() {
          $('#subsciptionmodal').modal('show');
      }, 1000);
      localStorage.setItem("modalsubscribe", "shown");
  }
})
$('#btnagree').on('click', function() {
  if (localStorage.getItem("modalsubscribe") != "shown") {
      setTimeout(function() {
          $('#subsciptionmodal').modal('show');
      }, 1000);
      localStorage.setItem("modalsubscribe", "shown");
  }
})
