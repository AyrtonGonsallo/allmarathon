$(function () {
  


  if (window.matchMedia("(min-width: 1120px)").matches) {
    $().UItoTop({ easingType: "easeOutQuart" });
  }

  if (window.matchMedia("(min-width: 768px) and (max-width: 801px)").matches) {
    $(".ban_160-600").click(function () {
      window.open(
        "/PDF_frame-2017-02-12_101128538_presentation.pdf",
        "_blank"
      );
    });

    $(".ban").click(function () {
      window.open("https://shop.alljudo.net/", "_blank");
    });
  }
});

var button = document.getElementById("hamburger-menu"),
  span = button.getElementsByTagName("span")[0];

button.onclick = function () {
  span.classList.toggle("hamburger-menu-button-close");
};

$("#hamburger-menu").on("click", toggleOnClass);

function toggleOnClass(event) {
  var toggleElementId = "#" + $(this).data("toggle"),
    element = $(toggleElementId);

  element.toggleClass("on");
}

// close hamburger menu after click a
$(".menu li a").on("click", function () {
  $("#hamburger-menu").click();
});





document.addEventListener("DOMContentLoaded", function() {
    const hamburgerButton = document.getElementById("hamburger-menu");
    const navCategory = document.querySelector(".nav-category");
    const header = document.querySelector(".header"); // Select the header element

    hamburgerButton.addEventListener("click", function() {
        // Toggle the menu open class
        navCategory.classList.toggle("open");

        // Toggle the fixed-header class
        if (navCategory.classList.contains("open")) {
            header.classList.add("fixed-header");
            document.body.classList.add("menu-open"); // Prevent scrolling
        } else {
            header.classList.remove("fixed-header");
            document.body.classList.remove("menu-open"); // Allow scrolling
        }
    });

    // Close the menu when clicking outside of it
    document.addEventListener("click", function(event) {
        if (!hamburgerButton.contains(event.target) && !navCategory.contains(event.target)) {
            navCategory.classList.remove("open");
            header.classList.remove("fixed-header");
            document.body.classList.remove("menu-open");
        }
    });
    console.log("fixed header chargé")
});
















