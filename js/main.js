$(function () {
  var carousel = $(".jcarousel").jcarousel({ wrap: "circular" });

  $("dd.actu-video a.navig.prev").jcarouselControl({
    target: "-=1",
    carousel: carousel,
  });
  $("dd.actu-video a.navig.next").jcarouselControl({
    target: "+=1",
    carousel: carousel,
  });

  $("dd.offres-allmarathon a.navig.prev").jcarouselControl({
    target: "-=1",
    carousel: carousel,
  });
  $("dd.offres-allmarathon a.navig.next").jcarouselControl({
    target: "+=1",
    carousel: carousel,
  });

  $("div.logo-slide a.prev").jcarouselControl({
    target: "-=1",
    carousel: carousel,
  });
  $("div.logo-slide a.next").jcarouselControl({
    target: "+=1",
    carousel: carousel,
  });

  $("#my-slider").sliderPro({
    autoplay: true,
    width: "100%",
    buttons: false,
    loop: true,
    arrows: true,
    thumbnailPointer: true,
  });

  $(document).herbyCookie({
    style: "dark",
    btnText: "Accepter",
    policyText: "Politique de confidentialité",
    text: "Ce site utilise des cookies afin d'améliorer votre expérience. En continuant à naviguer sur ce site, vous serez conforme à notre ",
    scroll: false,
    link: "/politique-de-confidentialite.html",
    expireDays: 365,
  });

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
