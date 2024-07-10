<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins and SweetAlert) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>

    <!-- Bootstrap CSS and JS (for modals) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

    <!-- Content here -->
    <?php echo $content; ?>

    <!-- Additional scripts here -->
    <?php
    Yii::app()->clientScript->registerScript('customScripts', "
        function openUpdateModal(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#updateModal .modal-body').html(response);
                    $('#updateModal').modal('show');
                }
            });
        }

        function confirmDelete(url) {
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
                    $.ajax({
                        url: url,
                        type: 'POST',
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                                $.fn.yiiGridView.update('blog-post-grid');
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the post.',
                                    'error'
                                );
                            }
                        }
                    });
                }
            });
        }

        $(document).on('submit', '#update-form', function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#updateModal').modal('hide');
                        Swal.fire(
                            'Updated!',
                            'Your post has been updated.',
                            'success'
                        );
                        $.fn.yiiGridView.update('blog-post-grid');
                    } else {
                        $('#updateModal .modal-body').html(response);
                    }
                }
            });
        });
    ", CClientScript::POS_END);
    ?>
</body>
</html>
