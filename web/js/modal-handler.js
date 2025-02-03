document.addEventListener("DOMContentLoaded", function () {
  function handleButtonClick(selector) {
    $(document).on("click", selector, function () {
      const url = $(this).data("url"); // Get the dynamic URL
      const modalTitle = $(this).data("title") || "Modal"; // Get title or fallback

      $.get(url, function (data) {
        $("#modal-title").text(modalTitle);
        $("#modal-content").html(data);
        $("#custom-modal").modal("show");
      });
    });
  }

  // Initialize handlers for Add, Edit, and View using event delegation
  handleButtonClick(".add-btn");
  handleButtonClick(".edit-btn");
  handleButtonClick(".view-btn");

  // Delete confirmation with SweetAlert (use event delegation)
  // $(document).on("click", ".delete-btn", function () {
  //   const url = $(this).data("url"); // Get the delete URL
  //   Swal.fire({
  //     title: "Are you sure?",
  //     text: "You won't be able to revert this!",
  //     icon: "warning",
  //     showCancelButton: true,
  //     confirmButtonColor: "#3085d6",
  //     cancelButtonColor: "#d33",
  //     confirmButtonText: "Yes, delete it!",
  //   }).then((result) => {
  //     if (result.isConfirmed) {
  //       $.ajax({
  //         url: url,
  //         type: "POST",
  //         success: function () {
  //           $.pjax.reload({
  //             container: "#student-pjax-container",
  //             async: false,
  //           });
  //           Swal.fire("Deleted!", "The record has been deleted.", "success");
  //         },
  //         error: function () {
  //           Swal.fire(
  //             "Error!",
  //             "There was an issue deleting the record.",
  //             "error"
  //           );
  //         },
  //       });
  //     }
  //   });
  // });
});
