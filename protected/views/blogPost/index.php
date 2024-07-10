<?php
/* @var $this BlogPostController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Blog Posts';
?>

<input type="text" id="search-bar" class="form-control" placeholder="Search...">
<!-- <a href="/create-post" class="btn btn-primary">Create Post</a> -->

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Content</th>
            <th>Author</th>
            <th>Created At</th>
            <th>Likes</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="blog-post-table-body">
        <!-- Blog post data will be populated here -->
    </tbody>
</table>

<div id="updateModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Update Blog Post</h4>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
  


function fetchBlogPosts(searchQuery = '') {
    $.ajax({
        url: '<?php echo Yii::app()->createUrl("blogPost/search"); ?>',
        type: 'GET',
        dataType: 'json',
        data: {
            <?php echo Yii::app()->request->csrfTokenName; ?>: '<?php echo Yii::app()->request->csrfToken; ?>',
            search: searchQuery
        },
        success: function(data) {
            var tableBody = $('#blog-post-table-body');
            tableBody.empty();

            $.each(data, function(index, post) {
                var liked = post.user_liked;
                var likeButton = liked ? '<button class="btn btn-success like-btn" data-id="' + post.id + '">Unlike</button>' :
                                         '<button class="btn btn-success like-btn" data-id="' + post.id + '">Like</button>';

                var row = '<tr data-id="' + post.id + '">' +
                            '<td>' + post.id + '</td>' +
                            '<td>' + post.title + '</td>' +
                            '<td>' + post.content + '</td>' +
                            '<td>' + post.user.username + '</td>' +
                            '<td>' + post.created_at + '</td>' +
                            '<td>' + post.likes.length + '</td>' +
                            '<td>' +
                                likeButton +
                                '<button class="btn btn-primary update-btn" data-id="' + post.id + '">Update</button>' +
                                '<button class="btn btn-danger delete-btn" data-id="' + post.id + '">Delete</button>' +
                            '</td>' +
                            '</tr>';
                tableBody.append(row);
            });

            bindEvents();
        }
    });
}
        // function bindEvents() {
        //     $('.like-btn').click(function() {
        //         var id = $(this).data('id');
        //         $.ajax({
        //             url: '<?php echo Yii::app()->createUrl("blogPost/like", array("id" => '')); ?>' + id,
        //             type: 'POST',
        //             dataType: 'json',
        //             data: {
        //                 id: id,
        //                 <?php echo Yii::app()->request->csrfTokenName; ?>: '<?php echo Yii::app()->request->csrfToken; ?>'
        //             },
        //             success: function(response) {
		// 				fetchBlogPosts();
        //                 if (response.success) {
        //                     var row = $('#blog-post-table-body').find('tr[data-id="' + id + '"]');
        //                     row.find('td').eq(5).text(response.likes);
        //                 } else {
        //                     alert('Failed to like the post.');
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error('Error liking the post:', error);
        //             }
        //         });
		// 		fetchBlogPosts();
        //     });

        //     $('.update-btn').click(function() {
        //         var id = $(this).data('id');
        //         var url = '<?php echo Yii::app()->createUrl("blogPost/update"); ?>' + '&id=' + id;
        //         openUpdateModal(url);
        //     });

        //     $('.delete-btn').click(function() {
        //         var id = $(this).data('id');
        //         var url = '<?php echo Yii::app()->createUrl("blogPost/delete"); ?>' + '&id=' + id;
        //         confirmDelete(url);
        //     });

        //     $('#search-bar').on('input', function() {
        //         var query = $(this).val();
        //         fetchBlogPosts(query);
        //     });
        // }
		function bindEvents() {
    $('.like-btn').click(function() {
        var id = $(this).data('id');
        var likeButton = $(this);

        $.ajax({
            url: '<?php echo Yii::app()->createUrl("blogPost/like", array("id" => '')); ?>' + id,
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                <?php echo Yii::app()->request->csrfTokenName; ?>: '<?php echo Yii::app()->request->csrfToken; ?>'
            },
            success: function(response) {
				fetchBlogPosts();
                if (response.success) {
                    var row = $('#blog-post-table-body').find('tr[data-id="' + id + '"]');
                    row.find('td').eq(5).text(response.likes);

                    if (response.liked) {
                        likeButton.text('Unlike');
                    } else {
                        likeButton.text('Like');
                    }
                } else {
                    alert(response.message || 'Failed to like/unlike the post.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error liking/unliking the post:', error);
            }
        });
    });


    // Other event bindings...
    
    $('.update-btn').click(function() {
                var id = $(this).data('id');
                var url = '<?php echo Yii::app()->createUrl("blogPost/update"); ?>' + '&id=' + id;
                openUpdateModal(url);
            });

            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var url = '<?php echo Yii::app()->createUrl("blogPost/delete"); ?>' + '&id=' + id;
                confirmDelete(url);
            });

            $('#search-bar').on('input', function() {
                var query = $(this).val();
                fetchBlogPosts(query);
            });
}

        fetchBlogPosts();

        function openUpdateModal(url) {
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    $('#updateModal .modal-body').html(response);
                    $('#updateModal').modal('show');

                    $('#update-form').on('submit', function(e) {
						fetchBlogPosts();
                        e.preventDefault();
                        var form = $(this);
                        $.ajax({
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
								fetchBlogPosts();
                                if (response.success) {
                                    $('#updateModal').modal('hide');
                                    Swal.fire(
                                        'Updated!',
                                        'Your post has been updated.',
                                        'success'
                                    );

                                    fetchUpdatedPost(response.id);
                                } else {
                                    $('#updateModal .modal-body').html(response);
                                }
                                
                            }
                        });
                    });
                }
            });
        }

        function fetchUpdatedPost(postId) {
            $.ajax({
                url: '<?php echo Yii::app()->createUrl("blogPost/getPost"); ?>',
                type: 'GET',
                dataType: 'json',
                data: { id: postId },
                success: function(post) {
                    fetchBlogPosts();
                    var row = $('#blog-post-table-body').find('tr[data-id="' + post.id + '"]');
                    row.find('td').eq(1).text(post.title);
                    row.find('td').eq(2).text(post.content);
                    row.find('td').eq(3).text(post.author);
                    row.find('td').eq(4).text(post.created_at);
                    row.find('td').eq(5).text(post.likes);
                }
            });
        }

        function confirmDelete(url) {
            Swal.fire({
                title: 'Are you sure?',
                text:
                "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    fetchBlogPosts();
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            <?php echo Yii::app()->request->csrfTokenName; ?>: '<?php echo Yii::app()->request->csrfToken; ?>'
                        },
                        success: function(response) {
                            console.log(response);
                            // if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your post has been deleted.',
                                    'success'
                                );
                                fetchBlogPosts();
                            // } 
                          
                        },
                        error: function(xhr, status, error) {
                            console.error('Error deleting the post:', error);
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the post.',
                                'error'
                            );
                        }
                    });
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    Swal.fire(
                        'Cancelled',
                        'Your post is safe :)',
                        'error'
                    );
                }
            });
        }
    });
</script>
