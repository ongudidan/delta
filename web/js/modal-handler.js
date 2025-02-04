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
  // Function to handle the delete button click
  function handleDeleteButtonClick() {
    document.querySelectorAll(".delete-btn").forEach(function (button) {
      button.addEventListener("click", function (event) {
        event.preventDefault();
        const url = this.getAttribute("data-url");
        Swal.fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = url;
          }
        });
      });
    });
  }

  // Initial binding of the delete button click event on page load
  document.addEventListener("DOMContentLoaded", handleDeleteButtonClick);

  // Rebind delete button click event after PJAX content is loaded
  $(document).on("pjax:success pjax:complete", function () {
    handleDeleteButtonClick();
  });
});
