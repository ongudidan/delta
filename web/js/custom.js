$(function () {
  $("#modalButton").click(function () {
    $("#mod").modal("show").find("#modalContent").load($(this).attr("value"));
  });
});
