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



// onload function for opacity effect start
    $(document).on('pjax:send', function() {
        $('#loading-overlay').addClass('show'); // Show the spinner overlay
        $('#pjax-container').css('opacity', '0.5'); // Optional fade effect
    });

    $(document).on('pjax:complete', function() {
        $('#loading-overlay').removeClass('show'); // Hide the spinner overlay
        $('#pjax-container').css('opacity', '1'); // Restore opacity
    });
    
    // Optional fade effect for other containers
    $(document).on('pjax:send', function() {
        $('#pjax-container1').css('opacity', '0.5'); // Optional fade effect
    });

    $(document).on('pjax:complete', function() {
        $('#pjax-container1').css('opacity', '1'); // Restore opacity
    });
// onload function for opacity effect end