$(document).ready(function() {
    // SweetAlert for delete confirmation
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('url');
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });

    // SweetAlert for selling out-of-stock product
    $('.sell-btn').on('click', function(e) {
        e.preventDefault();
        var quantity = $(this).data('quantity');

        if (quantity <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Out of Stock',
                text: 'Cannot sell product. Quantity is 0.',
            });
        } else {
            window.location.href = $(this).attr('href');
        }
    });
});
