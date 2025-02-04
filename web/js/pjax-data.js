// pjax related code START //////////////////////////////////////
$(document).on("click", ".pjax-link", function (e) {
  e.preventDefault();
  let url = $(this).attr("href");
  $.pjax({
    url: url,
    container: "#pjax-container1",
    timeout: 5000,
  });
});

// Reinitialize sidebar dropdowns after PJAX completes
$(document).on("pjax:end", function () {
  $(".submenu > a")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      let $parent = $(this).parent();
      if (!$parent.hasClass("active")) {
        $(".submenu").removeClass("active").find("ul").slideUp();
      }
      $parent.toggleClass("active").find("ul").slideToggle();
    });
});

$(document).on("pjax:end", function () {
  $(".submenu > a")
    .off("click")
    .on("click", function (e) {
      e.preventDefault();
      let $parent = $(this).parent();
      if (!$parent.hasClass("active")) {
        $(".submenu").removeClass("active").find("ul").slideUp();
      }
      $parent.toggleClass("active").find("ul").slideToggle();
    });
});
