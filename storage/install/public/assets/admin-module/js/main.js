/*---------------------------------------------
	Template name:  6amtechAdmin
	Version:        1.0
	Author:         6amtech
	Author url:     https://6amtech.com/

NOTE:
------
Please DO NOT EDIT THIS JS, you may need to use "custom.js" file for writing your custom js.
We may release future updates so it will overwrite this file. it's better and safer to use "custom.js".

[Table of Content]

    01: Main Menu
    02: Toggle Search
    03: Background Image
    04: togglePassword
    05: Preloader
    06: currentYear
    07: Perfect Scrollbar
    08: Dark, Light & RTL Switcher
    09: Settings Toggle
    10: trigger live toaster
    11: File Upload
    12: Filter Aside Toggle
    13: Edit Button Trigger Upload file
    14: Reset Button Trigger Upload file
    15: Enable tooltips
    16: Changing svg color
    17: Table Row Multi Select
    18: Collapse
----------------------------------------------*/

(function ($) {
    "use strict";

    /*===================
  01: Main Menu
  =====================*/
    /* Parent li add class */
    var body = $("body");
    $(".aside .aside-body")
        .find("ul li")
        .parents(".aside-body ul li")
        .addClass("has-sub-item");

    /* Submenu Opened */
    $(".aside .aside-body")
        .find(".has-sub-item > a")
        .on("click", function (event) {
            event.preventDefault();
            if (
                !body.hasClass("aside-folded") ||
                body.hasClass("open-aside-folded")
            ) {
                $(this).parent(".has-sub-item").toggleClass("sub-menu-opened");
                if ($(this).siblings("ul").hasClass("open")) {
                    $(this).siblings("ul").removeClass("open").slideUp("200");
                } else {
                    $(this).siblings("ul").addClass("open").slideDown("200");
                }
            }
        });

    /* Active Menu Open */
    $(window).on("load", function () {
        $(".aside .aside-body")
            .find(".sub-menu-opened a")
            .siblings("ul")
            .addClass("open")
            .show();
    });

    /* window resize trigger aide function */
    $(window).resize(function () {
        aside();
    });

    /* Aside function */
    function aside() {
        if ($(window).width() > 1199) {
            /* Remove siderbar-open */
            if (body.is(".aside-open")) {
                body.removeClass("aside-open");
            }

            /* Holded Aside on Mouseenter */
            $(".aside .aside-body").on("mouseenter", function () {
                body.addClass("open-aside-folded");
            });

            /* Holded aside on Mouseleave */
            $(".aside .aside-body").on("mouseleave", function () {
                body.removeClass("open-aside-folded");
                if (body.hasClass("aside-folded")) {
                    $(".aside")
                        .find(".aside-body .has-sub-item a")
                        .siblings("ul")
                        .removeClass("open")
                        .slideUp(0);
                }
            });

            /* Holded aside */
            $(".aside-toggle").on("click", function () {
                body.toggleClass("aside-folded");
                body.find(".aside-body .has-sub-item a")
                    .siblings("ul")
                    .removeClass("open")
                    .slideUp("fast");
            });
        } else {
            /* Remove aside-folded & open-aside-folded */
            if (body.is(".aside-folded, .open-aside-folded")) {
                body.removeClass("aside-folded open-aside-folded");
            }
            /* Open Aside */
            $(".aside-toggle, .offcanvas-overlay").on("click", function () {
                body.toggleClass("aside-open");
                $(".offcanvas-overlay").toggleClass("aside-active");
            });
        }
    }
    aside();

    /*========================
  02: Toggle Search
  ==========================*/
    $(".toggle-search-btn").on("click", function () {
        $(this).siblings(".search-form").toggleClass("active");
    });

    /*========================
  03: Background Image
  ==========================*/
    var $bgImg = $("[data-bg-img]");
    $bgImg
        .css("background-image", function () {
            return 'url("' + $(this).data("bg-img") + '")';
        })
        .removeAttr("data-bg-img")
        .addClass("bg-img");

    /*==================================
  04: togglePassword
  ====================================*/
    $(".togglePassword").on("click", function (e) {
        const password = $(this).siblings(".form-control");
        password.attr("type") === "password"
            ? $(this).html("visibility")
            : $(this).html("visibility_off");
        const type = password.attr("type") === "password" ? "text" : "password";
        password.attr("type", type);
    });

    /*==================================
  05: Preloader
  ====================================*/
    $(window).on("load", function () {
        $(".preloader").fadeOut(200);
    });

    /*==================================
  06: currentYear
  ====================================*/
    var currentYear = new Date().getFullYear();
    $(".currentYear").html(currentYear);

    /*============================================
  07: Perfect Scrollbar
  ==============================================*/
    var $scrollBar = $('[data-trigger="scrollbar"]');
    if ($scrollBar.length) {
        $scrollBar.each(function () {
            var $ps, $pos;

            $ps = new PerfectScrollbar(this);

            $pos = localStorage.getItem("ps." + this.classList[0]);

            if ($pos !== null) {
                $ps.element.scrollTop = $pos;
            }
        });

        $scrollBar.on("ps-scroll-y", function () {
            localStorage.setItem("ps." + this.classList[0], this.scrollTop);
        });
    }

    /*============================================
  08: Dark, Light & RTL Switcher
  ==============================================*/
    function themeSwitcher(className, themeName) {
        $(className).on("click", function () {
            $(".setting-box").removeClass("active");
            $(this).addClass("active");
            $("body").attr("theme", themeName);
            localStorage.setItem("theme", themeName);
        });
    }
    themeSwitcher(".setting-box.light-mode", "light");
    themeSwitcher(".setting-box.dark-mode", "dark");

    function rtlSwitcher(className, dirName) {
        $(className).on("click", function () {
            $(".setting-box").removeClass("active");
            $(this).addClass("active");
            $("html").attr("dir", dirName);
            localStorage.setItem("dir", dirName);
        });
    }
    rtlSwitcher(".setting-box.ltr-mode", "ltr");
    rtlSwitcher(".setting-box.rtl-mode", "rtl");

    // $('body').attr('theme', localStorage.getItem("theme"));
    // $('html').attr('dir', localStorage.getItem("dir"));

    /*============================================
  09: Settings Toggle
  ==============================================*/
    $(document).ready(function () {
        $(document).on("click", ".settings-toggle-icon", function (e) {
            e.stopPropagation();
            $(".settings-sidebar").toggleClass("active");
        });
        $(document).on("click", "body", function (e) {
            if (!$(e.target).is(".settings-sidebar, .settings-sidebar *"))
                $(".settings-sidebar").removeClass("active");
        });
    });

    /*============================================
  10: trigger live toaster
  ==============================================*/
    const toastTrigger = document.getElementById("liveToastBtn");
    const toastLiveExample = document.getElementById("liveToast");
    if (toastTrigger) {
        toastTrigger.addEventListener("click", () => {
            const toast = new bootstrap.Toast(toastLiveExample);

            toast.show();
        });
    }

    /*============================================
  11: File Upload
  ==============================================*/
    $(window).on("load", function () {
        $(".upload-file__input").on("change", function () {
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                let img = $(this).siblings(".upload-file__img").find("img");

                reader.onload = function (e) {
                    img.attr("src", e.target.result);
                };

                reader.readAsDataURL(this.files[0]);

                reader.addEventListener("progress", (event) => {
                    if (event.loaded && event.total) {
                        const percent = (event.loaded / event.total) * 100;
                        $("#uploadProgress").val(percent);
                        $("#progress-label").html(Math.round(percent) + "%");
                        $("#name_of_file").html(this.files[0].name);
                    }
                });
            }
        });
    });

    /*============================================
  12: Filter Aside Toggle
  ==============================================*/
    $(".filter-btn").on("click", function () {
        $(".filter-aside, .offcanvas-overlay").toggleClass("active");
        $("body").toggleClass("ov-hidden");
    });
    $(".offcanvas-overlay, .filter-aside .btn-close").on("click", function () {
        $(".filter-aside, .offcanvas-overlay").removeClass("active");
        $("body").removeClass("ov-hidden");
    });

    /*============================================
  13: Edit Button Trigger Upload file
  ==============================================*/
    $(".upload-file__edit").on("click", function () {
        $(this).siblings(".upload-file__input").click();
    });

    /*============================================
    14: Reset Button Trigger Upload file
    ==============================================*/
    var initialImages = [];
    $(window).on("load", function () {
        $("form")
            .find("img")
            .each(function (index, value) {
                initialImages.push(value.src);
            });
    });

    $(document).ready(function () {
        $("form").on("reset", function (e) {
            $("form")
                .find("img")
                .each(function (index, value) {
                    $(value).attr("src", initialImages[index]);
                });
            $("form").find("#uploadProgress").val(0);
            $("form").find("#progress-label").html("0%");
            $("form").find("#name_of_file").html("Name of the file");
        });
    });

    /*============================================
  15: Enable tooltips
  ==============================================*/
    const tooltipTriggerList = document.querySelectorAll(
        '[data-bs-toggle="tooltip"]'
    );
    const tooltipList = [...tooltipTriggerList].map(
        (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );

    /*==================================
  16: Changing svg color
  ====================================*/
    $("img.svg").each(function () {
        var $img = jQuery(this);
        var imgID = $img.attr("id");
        var imgClass = $img.attr("class");
        var imgURL = $img.attr("src");

        jQuery.get(
            imgURL,
            function (data) {
                // Get the SVG tag, ignore the rest
                var $svg = jQuery(data).find("svg");

                // Add replaced image's ID to the new SVG
                if (typeof imgID !== "undefined") {
                    $svg = $svg.attr("id", imgID);
                }
                // Add replaced image's classes to the new SVG
                if (typeof imgClass !== "undefined") {
                    $svg = $svg.attr("class", imgClass + " replaced-svg");
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr("xmlns:a");

                // Check if the viewport is set, else we gonna set it if we can.
                if (
                    !$svg.attr("viewBox") &&
                    $svg.attr("height") &&
                    $svg.attr("width")
                ) {
                    $svg.attr(
                        "viewBox",
                        "0 0 " + $svg.attr("height") + " " + $svg.attr("width")
                    );
                }

                // Replace image with new SVG
                $img.replaceWith($svg);
            },
            "xml"
        );
    });

    /*==================================
  17: Table Row Multi Select
  ====================================*/
    $(document).ready(function () {
        $(".multi-select-table td input[type=checkbox]").on(
            "change",
            function () {
                let checkedLength = $(
                    ".table td input[type=checkbox]:checked"
                ).length;
                if (this.checked) {
                    $(this).parents("tr").addClass("bg-light");
                } else {
                    $(this).parents("tr").removeClass("bg-light");
                }
                $(this)
                    .parents(".table-responsive")
                    .siblings(".multiple-select-actions")
                    .find(".checked-count")
                    .html(checkedLength);

                if (checkedLength >= 2) {
                    $(this)
                        .parents(".table-responsive")
                        .siblings(".multiple-select-actions")
                        .addClass("active");
                    $(this).parents("table").find("thead").hide();
                } else {
                    $(this)
                        .parents(".table-responsive")
                        .siblings(".multiple-select-actions")
                        .removeClass("active");
                    $(this).parents("table").find("thead").show();
                }
            }
        );
        $(".multi-checker").on("change", function () {
            let tableResponsive = $(this)
                .parents(".multiple-select-actions")
                .siblings(".table-responsive");
            if (this.checked) {
                tableResponsive
                    .find("input[type=checkbox]")
                    .prop("checked", true);
                tableResponsive.find("tr").addClass("bg-light");
                tableResponsive.find("thead").hide();
            } else {
                tableResponsive
                    .find("input[type=checkbox]")
                    .prop("checked", false);
                $(this)
                    .parents(".multiple-select-actions")
                    .removeClass("active");
                tableResponsive.find("tr").removeClass("bg-light");
                tableResponsive.find("thead").show();
            }
            $(this)
                .parents(".multiple-select-actions")
                .find(".checked-count")
                .html($(".table td input[type=checkbox]:checked").length);
        });
    });

    /*==================================
  18: Collapse
  ====================================*/
    function collapse() {
        $(document.body).on("click", '[data-toggle="collapse"]', function (e) {
            e.preventDefault();
            var target = "#" + $(this).data("target");

            $(this).toggleClass("collapsed");
            $(target).slideToggle();
        });
    }
    collapse();

    // $(".dropdown.hover").on("mouseenter", function () {
    //     $(this).find(".dropdown-hover-toggle").dropdown("toggle");
    // });
})(jQuery);
